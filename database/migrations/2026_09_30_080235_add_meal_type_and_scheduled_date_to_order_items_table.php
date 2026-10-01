<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('meal_type')->nullable()->after('price'); // 'breakfast', 'lunch', 'dinner'
            $table->date('scheduled_date')->nullable()->after('meal_type');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['meal_type', 'scheduled_date']);
        });
    }
};
