<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('digital_product_downloads')) {
            return;
        }
        if (Schema::hasColumn('digital_product_downloads', 'notified_at')) {
            return;
        }

        Schema::table('digital_product_downloads', function (Blueprint $table) {
            // When the buyer was emailed the secure download link for this grant.
            // NULL = not yet emailed. Used to keep the per-product "email download
            // link on order" delivery idempotent across repeated paid/updated events.
            $table->timestamp('notified_at')->nullable()->after('last_downloaded_at');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('digital_product_downloads')
            && Schema::hasColumn('digital_product_downloads', 'notified_at')) {
            Schema::table('digital_product_downloads', function (Blueprint $table) {
                $table->dropColumn('notified_at');
            });
        }
    }
};
