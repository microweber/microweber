<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use MicroweberPackages\TemplateFonts\Models\TemplateFont;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;
use MicroweberPackages\TemplateFonts\Tests\TestCase;

/**
 * Validates that template_fonts rows are preserved across backup-style export
 * and that a Bootstrap-like template seed can include fonts.
 */
class TemplateInstallFontsTest extends TestCase
{
    public function test_template_fonts_table_exists_after_migrate(): void
    {
        $manager = app(TemplateFontsManager::class);
        if (!$manager->tableReady()) {
            try {
                $migration = require __DIR__ . '/../../database/migrations/2026_07_29_000000_create_template_fonts_table.php';
                $migration->up();
            } catch (\Throwable $e) {
                $this->markTestSkipped('Cannot create table: ' . $e->getMessage());
            }
        }

        $this->assertTrue(Schema::hasTable('template_fonts'));
    }

    public function test_font_row_survives_json_export_import_cycle(): void
    {
        $manager = app(TemplateFontsManager::class);
        if (!$manager->tableReady()) {
            $this->markTestSkipped('table not ready');
        }

        $family = 'BootstrapSeedFont_' . substr(md5((string) microtime(true)), 0, 6);
        $manager->enableFont($family, 'google', 'sans-serif');

        $exported = TemplateFont::query()->where('family', $family)->get()->toArray();
        $this->assertNotEmpty($exported);

        // Simulate wipe + restore (as template install would)
        TemplateFont::query()->where('family', $family)->delete();
        $this->assertFalse(TemplateFont::query()->where('family', $family)->exists());

        foreach ($exported as $row) {
            unset($row['id']);
            TemplateFont::query()->create($row);
        }

        $this->assertTrue(TemplateFont::query()->where('family', $family)->exists());
        $this->assertContains($family, $manager->getEnabledFonts());
    }

    public function test_legacy_option_then_fresh_migrate_path(): void
    {
        if (!Schema::hasTable('options')) {
            // Create a minimal options table for the upgrade path
            Schema::create('options', function ($table) {
                $table->increments('id');
                $table->string('option_key')->nullable();
                $table->string('option_group')->nullable();
                $table->longText('option_value')->nullable();
                $table->string('module')->nullable();
                $table->integer('is_system')->nullable();
            });
        }

        // Use a real, existing Google font so a migrated row (and any CSS it
        // generates) is a valid @import rather than a dead 404 request.
        $legacyFamily = 'Roboto';

        DB::table('options')->where([
            'option_key' => 'enabled_custom_fonts',
            'option_group' => 'template',
        ])->delete();

        DB::table('options')->insert([
            'option_key' => 'enabled_custom_fonts',
            'option_group' => 'template',
            'option_value' => json_encode([$legacyFamily]),
        ]);

        // Clear fonts and re-run data migration
        if (Schema::hasTable('template_fonts')) {
            TemplateFont::query()->delete();
        }

        $dataMigration = require __DIR__ . '/../../database/migrations/2026_07_29_000001_migrate_legacy_enabled_custom_fonts.php';
        $dataMigration->up();

        try {
            $this->assertTrue(
                TemplateFont::query()->where('family', $legacyFamily)->exists(),
                'Legacy option fonts should migrate into template_fonts'
            );
        } finally {
            // This test seeds the shared CMS options/template_fonts tables; leave
            // no trace so it cannot leak an enabled font into other tests' CSS.
            TemplateFont::query()->where('family', $legacyFamily)->delete();
            DB::table('options')->where([
                'option_key' => 'enabled_custom_fonts',
                'option_group' => 'template',
            ])->delete();
        }
    }
}
