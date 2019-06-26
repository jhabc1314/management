<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagementServerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_server', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("server_name", 32)->default("")->comment("唯一服务名");
            $table->json("server_node")->comment("服务节点列表");
            $table->tinyInteger("server_status")->default(1)->comment("服务状态1开启0关闭");
            $table->unique("server_name", "idx_server_name");
            $table->primary("id", "pidx_id");
            $table->timestamps();
        });
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `management_server` comment '服务信息表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('management_server');
    }
}
