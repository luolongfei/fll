<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyQqFieldNullableToUseRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('use_record', function (Blueprint $table) {
            // 已经给过默认值的字段，只能重置默认值，不能再设置为可空。但可以用原生sql实现。
            $table->string('qq', 32)->default('')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('use_record', function (Blueprint $table) {
            //
        });
    }
}
