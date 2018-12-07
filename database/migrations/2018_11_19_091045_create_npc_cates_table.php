<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNpcCatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('npc_cates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cid')->default(0);
            $table->string('cate_name')->default('');
            $table->integer('sort')->default(0);
            $table->integer('time')->default(0);
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
        Schema::dropIfExists('npc_cates');
    }
}
