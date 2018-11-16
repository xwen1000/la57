<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasesetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basesets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('web_name')->default('')->comment('网站名称');
            $table->string('key_word')->default('')->comment('关键词');
            $table->string('web_description')->default('')->comment('网站描述');
            $table->string('web_version')->default('')->comment('网站版本');
            $table->string('web_right')->default('')->comment('网站版权');
            $table->text('web_logo')->nullable()->comment('logo图片地址');
            $table->string('hot_search')->default('')->comment('热门搜索词');
            $table->string('smtp_server', 80)->default('')->comment('邮件发送服务器');
            $table->smallInteger('smtp_port')->default(0)->comment('服务器(SMTP)端口');
            $table->string('smtp_user', 80)->default('')->comment('邮箱账号');
            $table->string('smtp_pwd', 80)->default('')->comment('邮箱密码/授权码');
            $table->string('test_eamil', 80)->default('')->comment('测试接收的邮件地址');
            $table->text('smtp_con')->nullable()->comment('邮件模板');
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
        Schema::dropIfExists('basesets');
    }
}
