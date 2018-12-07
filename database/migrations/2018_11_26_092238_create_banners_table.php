<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('banner_name')->default('');
            $table->string('image_url')->default('');
            $table->string('target_url')->default('');
            $table->integer('start_time')->default(0);
            $table->integer('end_time')->default(0);
            $table->tinyInteger('banner_type')->default(0);
            $table->integer('cate_id')->default(0);
            $table->integer('banner_sort')->default(0);
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
        Schema::dropIfExists('banners');
    }
}
