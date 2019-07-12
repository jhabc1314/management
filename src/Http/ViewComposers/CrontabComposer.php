<?php
/**
 * crontab
 * User: jackdou
 * Date: 19-7-11
 * Time: 下午4:09
 */

namespace JackDou\Management\Http\ViewComposers;


use Illuminate\View\View;

class CrontabComposer extends ViewComposer
{
    public function compose(View $view)
    {
        $this->active['crontab_active'] = self::ACTIVE;
        $view->with('active', $this->active);
    }
}