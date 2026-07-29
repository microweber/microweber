<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * One-time upgrade: move option-table font favourites into template_fonts.
 *
 * Safe on fresh installs (no options rows) and multi-driver (sqlite/mysql/pgsql).
 */
return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        if (!Schema::hasTable('template_fonts')) {
            return;
        }

        // Already has data — do not re-import
        if (DB::table('template_fonts')->count() > 0) {
            return;
        }

        if (!Schema::hasTable('options')) {
            return;
        }

        try {
            $row = DB::table('options')
                ->where('option_key', 'enabled_custom_fonts')
                ->where('option_group', 'template')
                ->first();
        } catch (\Throwable) {
            return;
        }

        if ($row === null || empty($row->option_value)) {
            return;
        }

        $fonts = json_decode((string) $row->option_value, true);
        if (!is_array($fonts)) {
            return;
        }

        $now = now();
        $sort = 0;
        foreach ($fonts as $family) {
            if (!is_string($family) || trim($family) === '') {
                continue;
            }
            $family = trim($family);

            $exists = DB::table('template_fonts')
                ->where('family', $family)
                ->where('provider', 'google')
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('template_fonts')->insert([
                'family' => $family,
                'provider' => 'google',
                'category' => null,
                'is_enabled' => true,
                'file_path' => null,
                'file_url' => null,
                'css_path' => null,
                'css_url' => null,
                'meta' => null,
                'sort_order' => $sort++,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        // Non-destructive: leave template_fonts rows in place
    }
};
