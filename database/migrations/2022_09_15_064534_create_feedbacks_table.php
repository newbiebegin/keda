<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('informer_id');
            $table->unsignedInteger('customer_id')->nullable();
            $table->string('message');
            $table->boolean('is_feedback')->default(1);
            $table->string('status')->default('new');
            $table->timestamps();

            $table->foreign('informer_id')->references('id')->on('users');
			$table->foreign('customer_id')->references('id')->on('users');

            $table->index(['customer_id']);
            $table->index(['informer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedbacks');
    }
}
