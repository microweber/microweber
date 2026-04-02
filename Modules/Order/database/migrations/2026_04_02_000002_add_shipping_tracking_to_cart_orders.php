<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_orders', function (Blueprint $table) {
            $table->string('shipping_tracking_number')->nullable()->after('shipping_amount');
            $table->string('shipping_tracking_url')->nullable()->after('shipping_tracking_number');
        });
    }

    public function down(): void
    {
        Schema::table('cart_orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_tracking_number', 'shipping_tracking_url']);
        });
    }
};
