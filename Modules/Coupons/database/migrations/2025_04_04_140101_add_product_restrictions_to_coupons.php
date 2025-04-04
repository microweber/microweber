<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cart_coupons', function (Blueprint $table) {
            $table->text('product_ids')->nullable()->after('uses_per_customer');
        });
    }

    public function down(): void
    {
        Schema::table('cart_coupons', function (Blueprint $table) {
            $table->dropColumn('product_ids');
        });
    }
};
