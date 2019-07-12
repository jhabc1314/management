<?php
/**
 * Crontab model
 * User: jackdou
 * Date: 19-7-11
 * Time: 下午3:56
 */

namespace JackDou\Management\Models;

use Illuminate\Database\Eloquent\Model;

class Crontab extends Model
{
    protected $table = 'management_crontab';

    public const CRONTAB_STOP_STATUS = 0;
    public const CRONTAB_START_STATUS = 1;
    public const CRONTAB_DEL_STATUS = 9;
}