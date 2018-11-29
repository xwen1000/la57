<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('region_name', 50)->default('');
            $table->integer('parent_id')->default(0);
            $table->tinyInteger('status')->default(2);
            $table->tinyInteger('level')->default(0);
            $table->integer('express')->nullable();
            $table->float('heavy', 8, 2)->nullable();
            $table->float('oexpress', 8, 2)->nullable()->comment('续费');
            $table->float('oheavy', 8, 2)->nullable()->comment('续重');
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
        Schema::dropIfExists('regions');
    }
}
