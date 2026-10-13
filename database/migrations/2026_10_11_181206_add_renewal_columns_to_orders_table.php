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
            $table->unsignedBigInteger('renewed_from_order_id')->nullable()->after('is_subscription');
            $table->unsignedBigInteger('renewed_to_order_id')->nullable()->after('renewed_from_order_id');
            $table->boolean('auto_renew')->default(true)->after('renewed_to_order_id');
            $table->timestamp('renewed_at')->nullable()->after('auto_renew');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['renewed_from_order_id', 'renewed_to_order_id', 'auto_renew', 'renewed_at']);
        });
    }
};
