<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('require_num')->default(0);
            $table->integer('people')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->integer('create_time')->default(0);
            $table->integer('expire_time')->default(0);
            $table->integer('success_time')->default(0);
            $table->integer('owner_id')->default(0);
            $table->tinyInteger('notify')->default(0);
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
        Schema::dropIfExists('groups');
    }
}
