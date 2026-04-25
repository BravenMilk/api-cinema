<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('movie_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned()->comment('1-5');
            $table->text('comment')->nullable();
            $table->timestamps();

            // 1 user hanya bisa 1 ulasan per film
            $table->unique(['user_id', 'movie_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
