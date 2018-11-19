<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsCatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_cates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cate_name', 50);
            $table->integer('parent_cate');
            $table->integer('sort');
            $table->integer('time');
            $table->string('image_url')->nullable();
            $table->smallInteger('state')->nullable()->comment('1开启2关闭');
            $table->tinyInteger('is_delete')->nullable()->comment('1正常2已删除');
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
        Schema::dropIfExists('goods_cates');
    }
}
