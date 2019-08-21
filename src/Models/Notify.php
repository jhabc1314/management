<?php
/**
 * 消息通知
 * User: jackdou
 * Date: 19-8-20
 * Time: 下午8:28
 */

namespace JackDou\Management\Models;

use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    protected $table = 'management_notice';

    const WARNING = 'warning';
    const ERROR = 'error';
    const NOTICE = 'notice';
}