<?php

namespace JackDou\Management\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    //
    protected $table = 'management_notice';

    public const INFO = 'info';
    public const NOTICE = 'notice';
    public const WARNING = 'warning';
    public const ALERT = 'alert';
    public const ERROR = 'error';




}
