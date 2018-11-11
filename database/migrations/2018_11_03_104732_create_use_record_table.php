<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUseRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('use_record', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_name', 255)->comment('商品名')->default('未知');
            $table->string('product_image', 255)->comment('商品名')->default('未知');
            $table->string('merchant_name', 32)->comment('店名')->default('未知');
            $table->string('cate_name', 32)->comment('所属分类')->default('未知');
            $table->string('sell_count', 32)->comment('销售总量')->default('未知');
            $table->string('sale_message', 32)->comment('月销量')->default('未知');
            $table->string('url', 255)->comment('商品地址');
            $table->string('shop_name', 32)->comment('商城名');
            $table->string('ip', 32)->comment('用户IP');
            $table->string('area', 32)->comment('地区')->nullable();
            $table->string('qq', 32)->comment('用户QQ')->default('未知');
            $table->string('device', 32)->comment('用户设备');
            $table->string('os', 32)->comment('操作系统');
            $table->string('os_version', 32)->comment('操作系统版本');
            $table->string('browser', 32)->comment('浏览器');
            $table->integer('deleted', false, true)->default(0)->comment('删除Flag 0:未删除 大于0：已删除');
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('use_record');
    }
}
