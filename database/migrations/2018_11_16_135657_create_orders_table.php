<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('buyer_id');
            $table->string('order_sn', 50);
            $table->integer('goods_id');
            $table->integer('group_order_id');
            $table->tinyInteger('group_header');
            $table->integer('pay_id');
            $table->string('pay_sn', 50);
            $table->tinyInteger('group_buy');
            $table->integer('province_id');
            $table->string('province_name', 50);
            $table->integer('city_id');
            $table->string('city_name', 50);
            $table->integer('district_id');
            $table->string('district_name', 50);
            $table->string('mobile', 11);
            $table->string('receive_name', 11);
            $table->string('nickname');
            $table->mediumText('order_goods');
            $table->decimal('goods_amount', 10, 2);
            $table->decimal('order_amount', 10, 2);
            $table->decimal('card_amount', 10, 2);
            $table->decimal('pay_amount', 10, 2);
            $table->string('shipping_address');
            $table->decimal('shipping_amount', 10, 2);
            $table->integer('shipping_time');
            $table->string('shipping_name', 20);
            $table->string('shipping_code', 20);
            $table->string('tracking_number', 80);
            $table->tinyInteger('order_status');
            $table->integer('order_time');
            $table->integer('pay_time');
            $table->integer('received_time');
            $table->date('start_date')->comment('预定房间的起始时间');
            $table->date('end_date')->comment('预定房间的结束时间');
            $table->string('book_name')->comment('预订房间人的姓名');
            $table->string('book_phone', 20)->comment('预订房间预留电话号码');
            $table->tinyInteger('order_type')->comment('订单类型 0购买农产品 1为预订房间 2为伊甸卡');
            $table->integer('book_days')->comment('预订房间的天数');
            $table->string('logistics');
            $table->integer('tables');
            $table->longText('sf_img');
            $table->integer('is_dian');
            $table->integer('express_fee');
            $table->integer('pay_type');
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
        Schema::dropIfExists('orders');
    }
}
