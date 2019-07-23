<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagementNoticeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_notice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('notice_title', 32)->default('')->comment('通知标题');
            $table->string('notice_user', 16)->default('')->comment('通知人');
            $table->string('notice_level', 10)->default('')->comment('通知等级');
            $table->string('notice_text', 255)->default('')->comment('通知内容');
            $table->string('notice_url', 128)->default('')->comment('跳转地址');
            $table->tinyInteger('notice_status')->default(0)->comment('通知状态0未读1已读');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('management_notice');
    }
}
