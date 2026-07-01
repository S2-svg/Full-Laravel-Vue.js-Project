<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index('discount_start_at');
            $table->index('discount_end_at');
        });

        Schema::table('admin_notifications', function (Blueprint $table) {
            $table->index(['type', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['discount_start_at']);
            $table->dropIndex(['discount_end_at']);
        });

        Schema::table('admin_notifications', function (Blueprint $table) {
            $table->dropIndex(['type', 'read_at']);
        });
    }
};
