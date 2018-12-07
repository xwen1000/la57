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
            $table->string('cate_name')->default('');
            $table->integer('parent_cate')->default(0);
            $table->integer('sort')->default(0);
            $table->integer('time')->default(0);
            $table->string('image_url')->nullable();
            $table->smallInteger('state')->default(1)->comment('1开启2关闭');
            $table->tinyInteger('is_delete')->default(1)->comment('1正常2已删除');
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
