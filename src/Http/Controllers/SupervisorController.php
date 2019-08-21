<?php

namespace JackDou\Management\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use JackDou\Management\Events\SupervisorStatus;
use JackDou\Management\Models\Client;
use JackDou\Management\Models\Server;
use JackDou\Management\Models\Supervisor;
use JackDou\Swoole\Management\ManagementService;
use JackDou\Swoole\Facade\Service;
use JackDou\Swoole\Services\SwooleService;

class SupervisorController extends Controller
{
    public $guard;

    public function __construct()
    {
        $this->guard = config('management.guard') ?: null;
        $this->middleware('auth:' . $this->guard);
    }
    /**
     * 指定服务的supervisor 页面
     *
     * @param $id integer
     *
     * @return View
     */
    public function supervisor($id)
    {
        $server = Server::where('server_status', 1)->findOrFail($id);
        $supervisor = Supervisor::where('server_id', $id)->first();
        $nodes = [];
        if (!empty($server->server_node)) {
            $nodes = json_decode($server->server_node);
            $server->nodeStatus($nodes);
        }
        $ip = '';
        return view('management::servers.supervisor', compact( 'server', 'supervisor', 'nodes', 'ip'));
    }

    /**
     * 保存supervisor配置
     *
     * @param Request $request
     * @param $id
     *
     * @return View
     */
    public function store(Request $request, $id)
    {
        $server = Server::where('server_status', 1)->findOrFail($id);
        $supervisor = Supervisor::where('server_id', $id)->first();
        empty($supervisor) and $supervisor = new Supervisor();
        $supervisor->server_id = $server->id;
        $supervisor->s_command = $request->post('s_command');
        $supervisor->s_stdout = $request->post('s_stdout');
        $supervisor->s_stdin = $request->post('s_stdin');
        $supervisor->s_user = $request->post('s_user');
        $supervisor->save();
        $request->session()->flash('success', '配置成功');
        return redirect(route('supervisor.index', $id));
    }

    /**
     * 推送配置文件到指定服务节点机器下
     *
     * @param $request Request
     * @param $id
     * @param $ip
     *
     * @return View
     */
    public function push(Request $request, $id, $ip)
    {
        $server = Server::where('server_status', 1)->findOrFail($id);
        $supervisor = Supervisor::where('server_id', $server->id)->first();
        //判断是否存在该节点
        $has = false;
        $nodes = json_decode($server->server_node, true) ?: [];
        foreach ($nodes as $node) {
            if ($node['ip'] == $ip) {
                $has = true;
                break;
            }
        }
        //下发
        $has and Service::getInstance(SwooleService::NODE_MANAGER, $ip)
            ->call('ManagementService::pushSupervisorConf', $server->server_name, $supervisor)
            ->getResult();
        $request->session()->flash('success', 'push success');
        return redirect(route('supervisor.index', $id));
    }

    /**
     * 推送supervisor配置到所有节点
     * 
     * @param $id
     * @param $request Request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pushAll(Request $request, $id)
    {
        $server = Server::where('server_status', 1)->findOrFail($id);
        $supervisor = Supervisor::where('server_id', $id)->first();
        $nodes = json_decode($server->server_node) ?: [];
        if (!empty($nodes)) {
            foreach ($nodes as $node) {
                //下发
                Service::getInstance(SwooleService::NODE_MANAGER, $node->ip)
                    ->call('ManagementService::pushSupervisorConf', $server->server_name, $supervisor)
                    ->getResult();
            }
        }
        $request->session()->flash('success', 'push all success');
        return redirect(route('supervisor.index', $id));
    }

    /**
     * 下线节点
     *
     * @param Request $request
     * @param $id
     * @param $ip
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function offline(Request $request, $id, $ip)
    {
        //下线配置
        $server = Server::where('server_status', 1)->findOrFail($id);
        $nodes = json_decode($server->server_node, true) ?: [];
        $need_push = false;
        foreach ($nodes as &$node) {
            if ($node['ip'] == $ip && $node['status'] == 'online') {
                $node['status'] = 'offline';
                $server->server_node = json_encode($nodes);
                $server->modifier = Auth::guard($this->guard)->user()->name;
                $server->save();
                $need_push = true;
                break;
            }
        }
        //推送配置到客户端
        if ($need_push) {
            \event(new SupervisorStatus($server));
        }
        $request->session()->flash('success', 'offline success');
        return redirect(route('supervisor.index', $id));
    }

    /**
     * restart node
     *
     * @param Request $request
     * @param $id
     * @param $ip
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restart(Request $request, $id, $ip)
    {
        //执行重启
        $server = Server::where('server_status', 1)->findOrFail($id);
        $res = Service::getInstance(SwooleService::NODE_MANAGER, $ip)
            ->call('ManagementService::restart', $server->server_name)
            ->getResult(20);//重启比较耗时
        $msg = '';
        if ($server->server_name == SwooleService::CRON_MANAGER) {
            $msg = " don't forget to restart crontab";
        }
        $request->session()->flash('success', !empty($res['data']) ? $res['data'] . $msg  : 'restart success' . $msg);
        return redirect(route('supervisor.index', $id));
    }

    /**
     * start
     *
     * @param Request $request
     * @param $id
     * @param $ip
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function start(Request $request, $id, $ip)
    {
        $server = Server::where('server_status', 1)->findOrFail($id);
        $res = Service::getInstance(SwooleService::NODE_MANAGER, $ip)
            ->call('ManagementService::start', $server->server_name)
            ->getResult(20);
        $request->session()->flash('success', !empty($res['data']) ? $res['data'] : '启动状态未知');
        return redirect(route('supervisor.index', $id));
    }

    /**
     * stop
     *
     * @param Request $request
     * @param $id
     * @param $ip
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function stop(Request $request, $id, $ip)
    {
        $server = Server::where('server_status', 1)->findOrFail($id);
        $res = Service::getInstance(SwooleService::NODE_MANAGER, $ip)
            ->call('ManagementService::stop', $server->server_name)
            ->getResult(10);
        $msg = '';
        if ($server->server_name == SwooleService::CRON_MANAGER) {
            $msg = " please notice crontab";
        }
        $request->session()->flash('success', !empty($res['data']) ? $res['data'] . $msg : '状态未知' . $msg);
        return redirect(route('supervisor.index', $id));
    }

    /**
     * online
     *
     * @param Request $request
     * @param $id
     * @param $ip
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function online(Request $request, $id, $ip)
    {
        $server = Server::where('server_status', 1)->findOrFail($id);
        $nodes = json_decode($server->server_node, true) ?: [];
        $need_push = false;
        foreach ($nodes as &$node) {
            if ($node['ip'] == $ip && $node['status'] == 'offline') {
                $node['status'] = 'online';
                $server->server_node = json_encode($nodes);
                $server->modifier = Auth::guard($this->guard)->user()->name;
                $server->save();
                $need_push = true;
                break;
            }
        }

        //推送配置到客户端
        if ($need_push) {
            \event(new SupervisorStatus($server));
        }

        $request->session()->flash('success', '上线成功');
        return redirect(route('supervisor.index', $id));
    }
}
