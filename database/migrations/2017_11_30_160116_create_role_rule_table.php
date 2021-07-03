<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleRuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_rule', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id');
            $table->integer('rule_id');
            $table->timestamps();
            $table->index(['role_id', 'rule_id'],'index_role_id_rule_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_rule');
    }
}
