<?php
/**
 * Created by PhpStorm.
 * User: jiangheng
 * Date: 19-7-1
 * Time: 下午6:14
 */

namespace JackDou\Management\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use JackDou\Management\Models\Notify;

class ViewComposer
{
    /**
     * 激活的侧边栏
     *
     * @var array
     */
    protected $active = [
        'home_active' => '',
        'server_active' => '',
        'crontab_active' => '',
    ];

    const ACTIVE = 'active';

    public function notifies()
    {
        $guard = config('management.guard') ?: null;
        //查询系统消息数
        $notice_user = config('management.notice_user');
        if (!empty($notice_user)) {
            if (is_array($notice_user)) {
                if (in_array(Auth::guard($guard)->user()->name, $notice_user)) {
                    return $this->count(Auth::guard($guard)->user()->name);
                }
            } else {
                if (Auth::guard($guard)->user()->name == $notice_user) {
                    return $this->count($notice_user);
                }
            }
        }
        return 0;
    }

    private function count($user)
    {
        return Notify::where('notice_user', $user)
            ->where('notice_status', 0)
            ->count();
    }
}