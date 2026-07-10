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
        if (!Schema::hasTable('subscription_plans_features')) { return; }

        Schema::table('subscription_plans_features', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_plans_features', 'description')) {
                $table->text('description')->nullable()->after('key');
            }
            if (!Schema::hasColumn('subscription_plans_features', 'limit')) {
                $table->string('limit')->nullable()->after('value');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_plans_features', function (Blueprint $table) {
            if (Schema::hasColumn('subscription_plans_features', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('subscription_plans_features', 'limit')) {
                $table->dropColumn('limit');
            }
        });
    }
};
