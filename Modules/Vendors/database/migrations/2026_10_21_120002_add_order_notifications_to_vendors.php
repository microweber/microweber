<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('vendors')) {
            return;
        }

        Schema::table('vendors', function (Blueprint $table) {
            if (! Schema::hasColumn('vendors', 'notify_on_order')) {
                // Master opt-in: email/notify this vendor when one of their
                // products is ordered.
                $table->boolean('notify_on_order')->default(true);
            }
            if (! Schema::hasColumn('vendors', 'notify_channels')) {
                // Which channels the vendor receives order notifications on,
                // as a JSON array, e.g. ["mail","database"]. NULL = defaults.
                $table->text('notify_channels')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('vendors')) {
            return;
        }
        Schema::table('vendors', function (Blueprint $table) {
            foreach (['notify_on_order', 'notify_channels'] as $col) {
                if (Schema::hasColumn('vendors', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
