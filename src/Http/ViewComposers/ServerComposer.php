<?php
/**
 * Created by PhpStorm.
 * User: jiangheng
 * Date: 19-7-1
 * Time: 下午6:12
 */

namespace JackDou\Management\Http\ViewComposers;

use Illuminate\View\View;

class ServerComposer extends ViewComposer
{
    public function compose(View $view)
    {
        $this->active['server_active'] = self::ACTIVE;
        $view->with('active', $this->active);
    }
}