<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyBrandModelOsBrowserFieldsNullableUseRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('use_record', function (Blueprint $table) {
            // 修改字段时需要重新指定长度，不用指定备注（总的来说就是用到的方法必须整个重置）
            $table->string('model', 32)->nullable()->change();
            $table->string('brand', 32)->nullable()->change();
            $table->string('os', 32)->nullable()->change();
            $table->string('browser', 64)->nullable()->change();
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
