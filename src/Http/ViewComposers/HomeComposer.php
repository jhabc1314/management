<?php
/**
 * Created by PhpStorm.
 * User: jiangheng
 * Date: 19-7-1
 * Time: 下午6:12
 */

namespace JackDou\Management\Http\ViewComposers;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeComposer extends ViewComposer
{

    public function compose(View $view)
    {
        $this->active['home_active'] = self::ACTIVE;
        $view->with('active', $this->active);

        $view->with('notifies', $this->notifies());
    }
}