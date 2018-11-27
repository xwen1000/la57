<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cid')->default(0)->comment('套餐产品id');
            $table->integer('gid')->default(0)->comment('商品id');
            $table->float('rate', 8, 2)->default(0)->comment('套餐商品数量');
            $table->string('gname')->default('')->comment('商品名称');
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
        Schema::dropIfExists('cards_goods');
    }
}
