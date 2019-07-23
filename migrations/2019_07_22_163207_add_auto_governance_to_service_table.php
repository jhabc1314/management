<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAutoGovernanceToServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('management_server', function (Blueprint $table) {
            //
            $table->tinyInteger('auto_governance')->default(0)->comment('是否自动服务治理0否1是');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('management_server', function (Blueprint $table) {
            //
        });
    }
}
