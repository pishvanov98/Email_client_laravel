<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmailQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_queue', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('pattern');
            $table->string('hash');
            $table->boolean('pause');
            $table->integer('status');
            $table->integer('counter');//нумерация отправления
            $table->integer('sender');//id отправителя,smtp
            $table->dateTime('date_send');//дата отправки
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
        Schema::dropIfExists('email_queue');
    }
}
