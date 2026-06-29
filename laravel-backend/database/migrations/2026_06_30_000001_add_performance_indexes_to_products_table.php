<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Index for discount date queries (ProcessDiscounts command, discount_status accessor)
            $table->index('discount_start_at');
            $table->index('discount_end_at');

            // Composite index for the most common discount query pattern
            // WHERE discount_percent > 0 AND discount_end_at IS NOT NULL AND discount_end_at <= now
            $table->index(['discount_percent', 'discount_end_at']);

            // Index for stock queries (low stock checks, AdminNotification::checkLowStock)
            $table->index('stock');

            // Index for category filtering in product listing (already has FK index, but explicit)
            $table->index('category_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            // Index for user orders listing
            $table->index('user_id');

            // Index for ordering by latest
            $table->index('created_at');
        });

        Schema::table('order_items', function (Blueprint $table) {
            // Index for order items lookup
            $table->index('order_id');
            $table->index('product_id');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('product_id');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('user_id');
        });

        Schema::table('admin_notifications', function (Blueprint $table) {
            // Index for the unread scope and type+product_id deduplication
            $table->index(['type', 'product_id']);
            $table->index('read_at');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['discount_start_at']);
            $table->dropIndex(['discount_end_at']);
            $table->dropIndex(['discount_percent', 'discount_end_at']);
            $table->dropIndex(['stock']);
            $table->dropIndex(['category_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['product_id']);
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['product_id']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('admin_notifications', function (Blueprint $table) {
            $table->dropIndex(['type', 'product_id']);
            $table->dropIndex(['read_at']);
        });
    }
};
