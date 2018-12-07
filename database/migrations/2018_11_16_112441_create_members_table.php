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
            $table->string('open_id')->default('');
            $table->string('wx_open_id')->default('');
            $table->string('access_token')->default('');
            $table->integer('expires')->default(0);
            $table->string('refresh_token')->default('');
            $table->string('unionid')->default('');
            $table->string('nickname')->default('');
            $table->tinyInteger('subscribe')->default(0);
            $table->tinyInteger('sex')->default(0);
            $table->string('headimgurl')->default('');
            $table->tinyInteger('disablle')->default(0);
            $table->integer('time')->default(0);
            $table->string('phone')->default('');
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
