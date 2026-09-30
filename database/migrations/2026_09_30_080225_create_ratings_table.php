<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('food_id')->constrained('food')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('rating', 2, 1)->unsigned()->default(5.0);
            $table->text('review')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'food_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
