<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('subscription_plans', 'is_active')) {
            return;
        }

        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('sort_order');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('subscription_plans', 'is_active')) {
            return;
        }

        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
