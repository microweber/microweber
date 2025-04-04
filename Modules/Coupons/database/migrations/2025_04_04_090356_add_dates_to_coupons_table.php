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
        Schema::table('cart_coupons', function (Blueprint $table) {
            $table->dateTime('start_date')->nullable()->after('is_active');
            $table->dateTime('end_date')->nullable()->after('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_coupons', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};
