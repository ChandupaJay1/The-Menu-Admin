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
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('paused_days_count')->default(0)->after('auto_renew');
            $table->decimal('paused_credit_amount', 10, 2)->default(0.00)->after('paused_days_count');
            $table->json('paused_dates')->nullable()->after('paused_credit_amount');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->boolean('is_paused')->default(false)->after('scheduled_date');
            $table->unsignedBigInteger('swapped_from_food_id')->nullable()->after('is_paused');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['paused_days_count', 'paused_credit_amount', 'paused_dates']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['is_paused', 'swapped_from_food_id']);
        });
    }
};
