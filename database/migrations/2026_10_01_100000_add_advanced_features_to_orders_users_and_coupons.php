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
        // 1. Create coupons table
        if (!Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('discount_type')->default('percent'); // percent or fixed
                $table->decimal('discount_value', 8, 2);
                $table->decimal('min_order_amount', 8, 2)->default(0);
                $table->string('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->dateTime('expires_at')->nullable();
                $table->timestamps();
            });
        }

        // 2. Add payment and address fields to orders table
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending_verification')->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('orders', 'bank_receipt_url')) {
                $table->text('bank_receipt_url')->nullable()->after('payment_reference');
            }
            if (!Schema::hasColumn('orders', 'is_subscription')) {
                $table->boolean('is_subscription')->default(false)->after('bank_receipt_url');
            }
            if (!Schema::hasColumn('orders', 'coupon_code')) {
                $table->string('coupon_code')->nullable()->after('is_subscription');
            }
            if (!Schema::hasColumn('orders', 'discount_amount')) {
                $table->decimal('discount_amount', 8, 2)->default(0.00)->after('coupon_code');
            }
            if (!Schema::hasColumn('orders', 'dinner_address')) {
                $table->string('dinner_address')->nullable()->after('delivery_address');
            }
        });

        // 3. Add dinner_address to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'dinner_address')) {
                $table->string('dinner_address')->nullable()->after('address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'payment_reference',
                'bank_receipt_url',
                'is_subscription',
                'coupon_code',
                'discount_amount',
                'dinner_address',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('dinner_address');
        });
    }
};
