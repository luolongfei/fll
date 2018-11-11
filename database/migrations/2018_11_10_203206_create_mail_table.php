<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content')->comment('邮件内容');
            $table->string('qq', 32)->comment('用户QQ')->default('未知');
            $table->string('ip', 32)->comment('用户IP');
            $table->string('area', 32)->comment('地区')->nullable();
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
        Schema::dropIfExists('mail');
    }
}
