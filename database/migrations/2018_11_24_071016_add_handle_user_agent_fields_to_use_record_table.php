<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHandleUserAgentFieldsToUseRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('use_record', function (Blueprint $table) {
            $table->tinyInteger('handle')->default('0')->comment('是否已被解析batch处理 0:未处理 1:已处理')->after('browser');
            $table->timestamp('handle_time')->nullable()->comment('batch处理时间，仅记录解析userAgent batch的处理时间')->after('browser');
            $table->string('user_agent', 255)->nullable()->comment('userAgent')->after('browser');
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
