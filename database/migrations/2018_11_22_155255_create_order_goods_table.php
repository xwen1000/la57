<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('orders_id')->comment('订单id');
            $table->integer('goods_id')->comment('商品id');
            $table->integer('quantity')->default(1)->comment('购买商品的数量');
            $table->double('goods_price', 15, 8)->default(0)->comment('商品的单价');
            $table->double('goods_pay', 15, 8)->default(0)->comment('本次购买此商品的总花费');
            $table->double('card_balance', 15, 8)->default(0)->comment('伊甸卡面值');
            $table->string('image_url')->default('')->comment('商品图片');
            $table->string('goods_name')->default('')->comment('商品名称');
            $table->date('goods_date')->nullable()->comment('预定房间的日期');
            $table->integer('type')->default(1)->comment('0普通商品1套餐商品');
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
        Schema::dropIfExists('order_goods');
    }
}
