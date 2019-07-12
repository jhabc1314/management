<?php

namespace JackDou\Management\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use JackDou\Management\Models\Crontab;
use JackDou\Swoole\Facade\Service;
use JackDou\Swoole\Services\SwooleService;

class CrontabController extends Controller
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
        //
        $crontabs = Crontab::where('cron_node_status', '<', Crontab::CRONTAB_DEL_STATUS)->paginate(10);
        return view("management::crontab.index", compact('crontabs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("management::crontab.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $crontab = new Crontab();
        $crontab->cron_node_ip = $request->post('cron_node_ip');
        $crontab->cron_command = $request->post('cron_command');
        $crontab->cron_timer = $request->post('cron_timer');
        $crontab->creator = Auth::guard($this->guard)->user()->name;
        $crontab->save();
        $request->session()->flash('success', '添加成功');
        return redirect(route('crontab.index'));
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
        $crontab = Crontab::where('cron_node_status', '<', Crontab::CRONTAB_DEL_STATUS)->findOrFail($id);
        return view("management::crontab.edit", compact('crontab'));
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
        $crontab = Crontab::where('cron_node_status', '<', Crontab::CRONTAB_DEL_STATUS)->findOrFail($id);
        $cron_node_ip = $crontab->cron_node_ip;
        $put = $request->all();
        $crontab->cron_node_ip = $put['cron_node_ip'];
        $crontab->cron_command = $put['cron_command'];
        $crontab->cron_timer = $put['cron_timer'];
        $crontab->modifier = Auth::guard($this->guard)->user()->name;
        $crontab->save();
        //如果更新了调度的执行机器就停止原机器的指定调度
        if ($put['cron_node_ip'] != $cron_node_ip) {
            $res = Service::getInstance(SwooleService::CRON_MANAGER, $cron_node_ip)
                ->call('CrontabService::stop', $crontab)
                ->getResult();
            if (isset($res['code']) && $res['code'] !== 0) {
                Log::error('CrontabService::stop fail,laravel log may have more detail');
            }
        }

        $request->session()->flash('success', 'update success');
        return redirect(route('crontab.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $crontab = Crontab::findOrFail($id);
        $crontab->cron_node_status = Crontab::CRONTAB_DEL_STATUS;
        $crontab->save();
        //不管有没有定时器都停止一下
        Service::getInstance(SwooleService::CRON_MANAGER, $crontab->cron_node_ip)
            ->call('CrontabService::stop', $crontab)
            ->getResult(1);
        $request->session()->flash('success', '删除成功');
        return back();
    }

    /**
     * 启动调度任务
     *
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function start(Request $request, $id)
    {
        //下发到节点机器的crontab服务设置定时器
        $crontab = Crontab::where('cron_node_status', '<', Crontab::CRONTAB_DEL_STATUS)->findOrFail($id);
        $res = Service::getInstance(SwooleService::CRON_MANAGER, $crontab->cron_node_ip)
            ->call('CrontabService::start', $crontab)
            ->getResult(2);
        if (isset($res['code']) && $res['code'] === 0) {
            $res['data']->save();
            $request->session()->flash('success', '启动成功');
        } else {
            $request->session()->flash('fail', '启动失败');
        }
        return back();
    }

    /**
     * 停止调度任务
     *
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function stop(Request $request, $id)
    {
        //清除指定节点的定时器
        $crontab = Crontab::where('cron_node_status', '<', Crontab::CRONTAB_DEL_STATUS)->findOrFail($id);
        $res = Service::getInstance(SwooleService::CRON_MANAGER, $crontab->cron_node_ip)
            ->call('CrontabService::stop', $crontab)
            ->getResult(2);
        if (isset($res['code']) && $res['code'] === 0) {
            $res['data']->save();
            $request->session()->flash('success', '暂停成功');
        } else {
            $request->session()->flash('fail', '暂停失败');
        }
        return back();
    }
}
