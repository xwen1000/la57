<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mid')->comment('用户id');
            $table->integer('cid')->comment('用户购买的卡id');
            $table->string('cname')->nullable()->comment('折扣卡的名称');
            $table->float('discount', 11, 2)->nullable()->comment('折扣率');
            $table->float('all_balance', 11, 2)->nullable()->comment('原卡金额');
            $table->float('balance', 11, 2)->nullable()->comment('折扣卡的余额');
            $table->date('start_date')->nullable()->comment('月卡的起始日期');
            $table->date('end_date')->nullable()->comment('月卡的结束日期');
            $table->tinyInteger('ctype');
            $table->tinyInteger('status')->comment('用户card状态 0不可用 1可用');
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
        Schema::dropIfExists('member_cards');
    }
}
