<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaylogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paylogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pay_sn')->default('');
            $table->integer('order_id')->default(0);
            $table->decimal('pay_amount', 10, 2)->default(0);
            $table->integer('pay_start_time')->default(0);
            $table->integer('pay_done_time')->default(0);
            $table->string('transaction_id')->default('');
            $table->string('refound_id')->default('');
            $table->string('pay_type')->default('');
            $table->tinyInteger('pay_status')->default(0);
            $table->string('prepay_id')->default('');
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
        Schema::dropIfExists('paylogs');
    }
}
