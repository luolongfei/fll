<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyMailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mail', function (Blueprint $table) {
            $table->dropColumn('device');
            $table->dropColumn('os_version');

            $table->string('model', 32)->nullable()->comment('型号')->after('area');
            $table->string('brand', 32)->nullable()->comment('品牌')->after('area');
            $table->string('os', 32)->default('')->comment('操作系统')->change();
            $table->string('browser', 64)->default('')->comment('浏览器')->change();
            $table->string('user_agent', 255)->nullable()->comment('userAgent')->after('area');
            $table->tinyInteger('handle')->default('0')->comment('是否已被batch处理 0:未处理 1:已处理')->after('area');
            $table->timestamp('handle_time')->nullable()->comment('batch处理时间')->after('area');

            $table->string('qq', 32)->nullable()->comment('用户QQ')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
