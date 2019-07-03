<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagementServerClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_server_client', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("server_id")->default(0)->comment("服务id");
            $table->string("client_ip", 32)->default("")->comment("客户机ip");
            $table->tinyInteger("client_status")->default(1)->comment("状态1正常0删除");
            $table->string("creator", 32)->default('')->comment('添加人');
            $table->dateTime("push_date")->comment("下发时间");
            $table->timestamps();
        });
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `management_server` comment '服务客户端信息表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('management_server_client');
    }
}
