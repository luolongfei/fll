<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUseRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('use_record', function (Blueprint $table) {
            $table->dropColumn('os_version');
            $table->dropColumn('device');
            $table->string('os', 64)->change();
            $table->string('browser', 64)->change();
            $table->string('brand', 32)->comment('品牌')->after('qq');
            $table->string('model', 32)->comment('型号')->after('qq');
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
