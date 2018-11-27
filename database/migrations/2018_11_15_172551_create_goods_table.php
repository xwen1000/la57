<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cate_id')->default(0);
            $table->integer('ncp_cate_id')->default(0);
            $table->string('goods_name', 50)->default('');
            $table->string('image_url')->default('');
            $table->mediumText('goods_desc')->nullable();
            $table->text('goods_imgs')->nullable();
            $table->decimal('market_price', 10, 2)->default(0);
            $table->decimal('group_price', 10, 2)->default(0);
            $table->decimal('alone_price', 10, 2)->default(0);
            $table->integer('group_number')->default(0);
            $table->integer('sell_count')->default(0);
            $table->tinyInteger('limit_buy')->default(0);
            $table->integer('goods_stock')->default(0);
            $table->tinyInteger('in_selling')->default(0);
            $table->smallInteger('goods_sort')->default(0);
            $table->tinyInteger('sell_type')->default(0);
            $table->integer('time')->default(0);
            $table->integer('card_balance')->default(0);
            $table->tinyInteger('is_delete')->default(1)->comment('是否删除1正常2已删除');
            $table->string('unit', 50)->default('');
            $table->string('goods_sn', 60)->default('');
            $table->tinyInteger('is_recommend')->default(0);
            $table->tinyInteger('is_new')->default(0);
            $table->mediumText('desc')->nullable();
            $table->float('heavy', 8, 2)->default(0);
            $table->integer('mane')->default(0);
            $table->integer('manjian')->default(0);
            $table->string('room_cw')->default('');
            $table->string('room_mj')->default('');
            $table->string('room_rs')->default('');
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
        Schema::dropIfExists('goods');
    }
}
