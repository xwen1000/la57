<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->default('');
            $table->integer('cate_id')->default(0);
            $table->string('author')->default('');
            $table->string('image')->default('');
            $table->string('description')->default('');
            $table->longText('content')->nullable();
            $table->integer('sort')->default(0);
            $table->tinyInteger('status')->default(0)->comment('文章状态 0-未审核；1-发布');
            $table->integer('pv')->default(0);
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
        Schema::dropIfExists('news');
    }
}
