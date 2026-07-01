<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('vat_total', 10, 2)->default(0)->after('total');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('original_price', 10, 2)->nullable()->after('price');
            $table->decimal('vat_amount', 10, 2)->default(0)->after('original_price');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('vat_total');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['original_price', 'vat_amount']);
        });
    }
};
