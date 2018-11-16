<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('open_id');
            $table->string('wx_open_id');
            $table->string('access_token');
            $table->integer('expires');
            $table->string('refresh_token');
            $table->string('unionid');
            $table->string('nickname', 50);
            $table->tinyInteger('subscribe');
            $table->tinyInteger('sex');
            $table->string('headimgurl');
            $table->tinyInteger('disablle');
            $table->integer('time');
            $table->string('phone', 11);
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
        Schema::dropIfExists('members');
    }
}
