<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagementCrontabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_crontab', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cron_node_ip', 32)->default('')->comment('运行调度任务的机器ip');
            $table->integer('cron_id')->default(0)->comment('设置的任务id');
            $table->tinyInteger('cron_node_status')->default(0)->comment('机器状态0停止1运行9删除');
            $table->string('cron_command', 255)->default('')->comment('调度任务命令');
            $table->integer('cron_timer')->default(60)->comment('每隔多少秒执行');
            $table->string('creator', 32)->default('')->comment('创建人');
            $table->string('modifier', 32)->default('')->comment('修改人');
            $table->timestamps();
            $table->index('cron_node_ip', 'idx_node_ip');
        });
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `management_server` comment '调度任务记录表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('management_crontab');
    }
}
