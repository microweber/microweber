<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cart_coupons', function (Blueprint $table) {
            $table->dateTime('valid_from')->nullable()->after('is_active');
            $table->dateTime('valid_to')->nullable()->after('valid_from');
        });
    }

    public function down(): void
    {
        Schema::table('cart_coupons', function (Blueprint $table) {
            $table->dropColumn(['valid_from', 'valid_to']);
        });
    }
};
