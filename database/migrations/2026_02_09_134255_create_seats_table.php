<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->foreignUuid('hall_id')->constrained()->cascadeOnDelete();
        $table->foreignUuid('seat_type_id')->constrained('seat_types');
        $table->string('row_label', 2);
        $table->integer('seat_number');
        $table->integer('pos_x');
        $table->integer('pos_y');
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
