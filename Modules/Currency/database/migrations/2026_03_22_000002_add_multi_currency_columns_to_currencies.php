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
        Schema::table('currencies', function (Blueprint $table) {
            // Add multi-currency support columns if they don't exist
            if (!Schema::hasColumn('currencies', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('swap_currency_symbol');
            }
            if (!Schema::hasColumn('currencies', 'is_default')) {
                $table->boolean('is_default')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('currencies', 'position')) {
                $table->integer('position')->default(0)->after('is_default');
            }
        });

        // Set the first currency as default if none exists
        if (Schema::hasTable('currencies')) {
            $defaultExists = \Illuminate\Support\Facades\DB::table('currencies')
                ->where('is_default', true)
                ->exists();

            if (!$defaultExists) {
                \Illuminate\Support\Facades\DB::table('currencies')
                    ->limit(1)
                    ->update(['is_default' => true]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('currencies', function (Blueprint $table) {
            if (Schema::hasColumn('currencies', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('currencies', 'is_default')) {
                $table->dropColumn('is_default');
            }
            if (Schema::hasColumn('currencies', 'position')) {
                $table->dropColumn('position');
            }
        });
    }
};
