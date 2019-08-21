<?php

namespace JackDou\Management\Http\Controllers;

use JackDou\Management\Events\SupervisorName;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use JackDou\Management\Models\Client;
use JackDou\Management\Models\Server;
use JackDou\Management\Models\Supervisor;
use JackDou\Swoole\Facade\Service;
use JackDou\Swoole\Management\ManagementService;
use JackDou\Swoole\Services\SwooleService;

class ServersController extends Controller
{

    public $guard;

    public function __construct()
    {
        $this->guard = config('management.guard') ?: null;
        $this->middleware('auth:' . $this->guard);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //如果配置了超级用户，则非超级用户无法看到关键服务
        $super_user = config('management.super_user');
        $servers = Server::where('server_status', 1);
        $login_user = Auth::guard($this->guard)->user()->name;
        if (is_array($super_user) && !in_array($login_user, $super_user)) {
            $servers->whereNotIn('server_name', config('management.kernel_servers'));
        } elseif (!empty($super_user) && $super_user != $login_user) {
            $servers->whereNotIn('server_name', config('management.kernel_servers'));
        }
        $servers = $servers->paginate(10);

        return view("management::servers.index", compact('servers'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("management::servers.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $server = new Server();
        $server->server_name = $request->post('server_name');
        $server->server_desc = $request->post('server_desc');
        $server->server_node = $request->post('server_node');
        $server->auto_governance = $request->post('auto_governance') == 'on' ? 1 : 0;
        $server->creator = Auth::guard($this->guard)->user()->name;
        $server->save();
        $request->session()->flash('success', '添加成功');
        return redirect(route('servers.index'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $server = Server::find($id);
        return view("management::servers.edit", compact('server', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $server = Server::findOrFail($id);
        $put = $request->all();
        //server_name 发生变化就触发supervisor名称变更
        if ($server->server_name != $put['server_name']) {
            event(new SupervisorName($server, $put['server_name']));
        }
        $server->server_name = $put['server_name'];
        $server->server_desc = $put['server_desc'];
        $server->server_node = $put['server_node'];
        $server->auto_governance = (isset($put['auto_governance']) && $put['auto_governance'] == 'on') ? 1 : 0;
        $server->modifier = Auth::guard($this->guard)->user()->name;
        $server->save();
        $request->session()->flash('success', '更新成功');
        return redirect(route('servers.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //TODO
    }

    /**
     * 客户端列表
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clients($id)
    {
        $server = Server::findOrFail($id);
        $clients = Client::where('server_id', $id)
            ->where('client_status', 1)
            ->get();
        return view("management::servers.client", compact('clients', 'id', 'server'));
    }

    /**
     * 添加客户端
     *
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clientsStore(Request $request, $id)
    {
        $server = Server::findOrFail($id);
        $client = new Client();
        $client->server_id = $server->id;
        $client->client_ip = $request->post('ip');
        $client->creator = Auth::guard($this->guard)->user()->name;
        $client->save();
        $request->session()->flash('success', '添加成功');
        return Response::redirectTo(route('clients.index', $id));
    }

    /**
     * 下发配置到客户端
     *
     * @param $request Request
     * @param $id
     * @param $cid
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clientsPush(Request $request,$id, $cid)
    {
        $server = Server::findOrFail($id);
        $client = Client::where('id', $cid)
            ->where('server_id', $id)
            ->first();
        if ($server->server_name == SwooleService::NODE_MANAGER) {
            //下发节点管理服务的配置时先下发到当前机器，防止找不到其他的机器节点ip
            ManagementService::pushServerNode($server->server_name, $server->server_node);
        }
        //请求对应客户机器的node_manager 下发配置内容
        Service::getInstance(SwooleService::NODE_MANAGER, $client->client_ip)
            ->call('ManagementService::pushServerNode', $server->server_name, $server->server_node)
            ->getResult();
        $request->session()->flash('success', 'push success');

        return back();
    }

    /**
     * 删除客户端
     *
     * @param $request Request
     * @param $id
     * @param $cid
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clientsDestroy(Request $request, $id, $cid)
    {
        $client = Client::where('id', $cid)
            ->where('server_id', $id)
            ->first();
        $client->client_status = 0;
        $client->save();
        $request->session()->flash('success', 'delete success');

        return back();
    }

    /**
     * 下发配置到所有客户端
     *
     * @param $request Request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clientsPushAll(Request $request, $id)
    {
        $server = Server::findOrFail($id);
        $clients = Client::where('server_id', $id)
            ->where('client_status', 1)
            ->get();
        if ($server->server_name == SwooleService::NODE_MANAGER) {
            //下发节点管理服务的配置时先下发到当前机器，防止找不到其他的机器节点ip
            ManagementService::pushServerNode($server->server_name, $server->server_node);
        }
        foreach ($clients as $client) {
            //请求对应客户机器的node_manager 下发配置内容
            Service::getInstance(SwooleService::NODE_MANAGER, $client->client_ip)
                ->call('ManagementService::pushServerNode', $server->server_name, $server->server_node)
                ->getResult();
        }
        $request->session()->flash('success', 'push all success');
        return back();
    }
}
