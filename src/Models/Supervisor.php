<?php
/**
 * Supervisor
 *
 * User: jackdou
 * Date: 19-7-8
 * Time: 下午5:08
 */

namespace JackDou\Management\Models;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    protected $table = 'management_server_supervisor';

    /**
     * 构建supervisor项目配置文件内容
     *
     * @param $server_name
     *
     * @return array
     */
    public function makeConf($server_name)
    {
        $conf = [];
        $conf[] = "[program:{$server_name}]";
        $conf[] = "command = {$this->s_command}";
        $conf[] = "stdout_logfile = {$this->s_stdout}";
        $conf[] = "stdin_logfile = {$this->s_stdin}";
        $conf[] = "user = {$this->s_user}";
        $conf[] = "autostart = 1";
        $conf[] = "autorestart = 1";
        $conf[] = "startsecs = 10";
        $conf[] = "stdout_logfile_maxbytes = " . config('management.supervisor.stdout_logfile_maxbytes', '500MB');
        $conf[] = "stdout_logfile_backups = " . config('management.supervisor.stdout_logfile_backups', 10);
        $conf[] = "stderr_logfile_maxbytes = " . config('management.supervisor.stderr_logfile_maxbytes', '500MB');
        $conf[] = "stderr_logfile_backups = " . config('management.supervisor.stderr_logfile_backups', 10);
        return $conf;
    }
}