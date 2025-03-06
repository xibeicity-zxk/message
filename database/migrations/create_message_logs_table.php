<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_logs', function (Blueprint $table) {
            $table->id();
            $table->string('driver')->comment('消息驱动类型');
            $table->string('to')->comment('接收者');
            $table->text('content')->comment('消息内容');
            $table->json('data')->nullable()->comment('附加数据');
            $table->string('status')->default('pending')->comment('发送状态');
            $table->text('error')->nullable()->comment('错误信息');
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
        Schema::dropIfExists('message_logs');
    }
}