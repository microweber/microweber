<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if table exists
        if (!Schema::hasTable('shipping_providers')) {
            return;
        }

        Schema::table('shipping_providers', function (Blueprint $table) {
            // Add is_default column if it doesn't exist
            if (!Schema::hasColumn('shipping_providers', 'is_default')) {
                $table->integer('is_default')->nullable()->after('is_active');
            }

            // Add description column if it doesn't exist
            if (!Schema::hasColumn('shipping_providers', 'description')) {
                $table->text('description')->nullable()->after('name');
            }

            // Add icon column if it doesn't exist
            if (!Schema::hasColumn('shipping_providers', 'icon')) {
                $table->string('icon')->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('shipping_providers')) {
            return;
        }

        Schema::table('shipping_providers', function (Blueprint $table) {
            if (Schema::hasColumn('shipping_providers', 'is_default')) {
                $table->dropColumn('is_default');
            }
            if (Schema::hasColumn('shipping_providers', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('shipping_providers', 'icon')) {
                $table->dropColumn('icon');
            }
        });
    }
};
