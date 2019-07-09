<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagementServerSupervisorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_server_supervisor', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('server_id')->default(0)->comment('服务id');
            $table->string('s_command', 255)->default('')->comment('command');
            $table->string('s_stdout', 128)->default('')->comment('stdout_logfile');
            $table->string('s_stdin', 128)->default('')->comment('stdin_logfile');
            $table->string('s_user', 32)->default('')->comment('user');

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
        Schema::dropIfExists('management_server_supervisor');
    }
}
