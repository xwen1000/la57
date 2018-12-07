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
            $table->integer('buyer_id')->default(0);
            $table->string('order_sn')->default('');
            $table->integer('goods_id')->default(0);
            $table->integer('group_order_id')->default(0);
            $table->tinyInteger('group_header')->default(0);
            $table->integer('pay_id')->default(0);
            $table->string('pay_sn')->default('');
            $table->tinyInteger('group_buy')->default(0);
            $table->integer('province_id')->default(0);
            $table->string('province_name')->default('');
            $table->integer('city_id')->default(0);
            $table->string('city_name')->default('');
            $table->integer('district_id')->default(0);
            $table->string('district_name')->default('');
            $table->string('mobile')->default('');
            $table->string('receive_name')->default('');
            $table->string('nickname')->default('');
            $table->mediumText('order_goods')->nullable();
            $table->decimal('goods_amount', 10, 2)->default(0);
            $table->decimal('order_amount', 10, 2)->default(0);
            $table->decimal('card_amount', 10, 2)->default(0);
            $table->decimal('pay_amount', 10, 2)->default(0);
            $table->string('shipping_address')->default('');
            $table->decimal('shipping_amount', 10, 2)->default(0);
            $table->integer('shipping_time')->default(0);
            $table->string('shipping_name')->default('');
            $table->string('shipping_code')->default('');
            $table->string('tracking_number')->default('');
            $table->tinyInteger('order_status')->default(0);
            $table->integer('order_time')->default(0);
            $table->integer('pay_time')->default(0);
            $table->integer('received_time')->default(0);
            $table->dateTime('start_date')->nullable()->comment('预定房间的起始时间');
            $table->dateTime('end_date')->nullable()->comment('预定房间的结束时间');
            $table->string('book_name')->nullable()->comment('预订房间人的姓名');
            $table->string('book_phone')->nullable()->comment('预订房间预留电话号码');
            $table->tinyInteger('order_type')->default(0)->comment('订单类型 0购买农产品 1为预订房间 2为伊甸卡');
            $table->integer('book_days')->nullable()->comment('预订房间的天数');
            $table->string('logistics')->default('');
            $table->integer('tables')->default(0);
            $table->longText('sf_img')->nullable();
            $table->integer('is_dian')->default(0);
            $table->integer('express_fee')->default(0);
            $table->integer('pay_type')->default(0);
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
