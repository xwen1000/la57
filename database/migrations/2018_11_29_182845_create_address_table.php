<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->default(0);
            $table->integer('country')->default(0);
            $table->string('province')->default('');
            $table->string('city')->default('');
            $table->string('district')->default('');
            $table->integer('province_id')->default(0);
            $table->integer('city_id')->default(0);
            $table->integer('district_id')->default(0);
            $table->string('address')->default('');
            $table->string('id_number')->default('');
            $table->string('mobile')->default('');
            $table->string('receive_name')->default('');
            $table->string('address_name')->default('');
            $table->string('full_address')->default('');
            $table->string('status')->default('');
            $table->integer('created_time')->default(0);
            $table->integer('updated_time')->default(0);
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
        Schema::dropIfExists('address');
    }
}
