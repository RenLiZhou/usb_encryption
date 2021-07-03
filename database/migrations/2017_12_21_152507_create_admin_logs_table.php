<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->default(0)->comment('创建者');
            $table->tinyInteger('type')->default(1)->comment('日志类型');
            $table->string('ip',30)->nullable(false)->comment('ip地址');
            $table->string('url', 100)->nullable(false)->comment('请求url');
            $table->string('method', 40)->nullable(false)->comment('请求方式');
            $table->string('param', 255)->nullable(false)->comment('请求参数');
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
        Schema::dropIfExists('oplog');
    }
}
