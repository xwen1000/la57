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
            $table->integer('cate_id');
            $table->integer('ncp_cate_id');
            $table->string('goods_name', 50);
            $table->string('image_url');
            $table->mediumText('goods_desc');
            $table->text('goods_imgs');
            $table->decimal('market_price', 10, 2);
            $table->decimal('group_price', 10, 2);
            $table->decimal('alone_price', 10, 2);
            $table->integer('group_number');
            $table->integer('sell_count');
            $table->tinyInteger('limit_buy');
            $table->integer('goods_stock');
            $table->tinyInteger('in_selling');
            $table->smallInteger('goods_sort');
            $table->tinyInteger('sell_type');
            $table->integer('time');
            $table->integer('card_balance');
            $table->tinyInteger('is_delete')->comment('是否删除1正常2已删除');
            $table->string('unit', 50);
            $table->string('goods_sn', 60);
            $table->tinyInteger('is_recommend');
            $table->tinyInteger('is_new');
            $table->mediumText('desc');
            $table->float('heavy', 8, 2);
            $table->integer('mane');
            $table->integer('manjian');
            $table->string('room_cw');
            $table->string('room_mj');
            $table->string('room_rs');
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
