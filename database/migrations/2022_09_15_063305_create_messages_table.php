<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sender_id');
            $table->unsignedInteger('recipient_id');
            $table->string('message');
            $table->dateTimeTz('sent_date');
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users');
			$table->foreign('recipient_id')->references('id')->on('users');

            $table->index(['sender_id']);
            $table->index(['recipient_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
