<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quiz_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id');
            $table->string('name');
            $table->datetime('time_start')->nullable();
            $table->datetime('time_end')->nullable();
            $table->string('time_left')->nullable();
            $table->integer('life_left')->nullable();
            $table->integer('score')->nullable();
            $table->string('end_type')->nullable();
            $table->json('overlay_views')->nullable();
            $table->json('questions')->nullable();
            $table->json('client')->nullable();
            $table->timestamps();

            $table->foreign('quiz_id')
                ->references('id')
                ->on('quizzes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_users');
    }
};
