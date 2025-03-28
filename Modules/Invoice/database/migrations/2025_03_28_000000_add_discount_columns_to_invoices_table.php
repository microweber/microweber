<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->integer('discount')->nullable()->after('sub_total');
            $table->string('discount_type', 20)->nullable()->after('discount');
            $table->boolean('tax_per_item')->default(false)->after('discount_type');
            $table->boolean('discount_per_item')->default(false)->after('tax_per_item');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['discount', 'discount_type', 'tax_per_item', 'discount_per_item']);
        });
    }
};