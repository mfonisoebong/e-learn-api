<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->longText('review');
            $table->integer('rating');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('course_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('set null');
            $table->foreign('course_id')->references('id')->on('courses')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
