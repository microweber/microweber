<?php

namespace Modules\WordPressMigration\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Modules\WordPressMigration\Filament\Widgets\WordPressImportCtaWidget;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Phase-11 coverage for the dashboard empty-state CTA.
 *
 * The widget should ONLY render when the live `content` table is
 * empty — the point is to catch brand-new installs at the moment
 * they are most likely coming from WordPress. Once any content row
 * exists (imported or authored), the tile disappears so it doesn't
 * turn into permanent chrome.
 */
class WordPressImportCtaWidgetTest extends TestCase
{
    private const CTA_MARKER = 'wp-import-cta-test-row';

    protected function setUp(): void
    {
        parent::setUp();
        // Snapshot: the widget's visibility gate is a raw table count,
        // so the tests must own their fixture rows — leaking a test
        // row here would make the "content is empty" branch false for
        // every subsequent suite.
        DB::table('content')
            ->where('title', self::CTA_MARKER)
            ->delete();
    }

    protected function tearDown(): void
    {
        DB::table('content')
            ->where('title', self::CTA_MARKER)
            ->delete();
        parent::tearDown();
    }

    #[Test]
    public function widget_is_visible_when_content_table_is_empty(): void
    {
        // Explicitly clear any residual content rows so the
        // emptiness check is deterministic.
        $residualIds = DB::table('content')->pluck('id')->all();
        if (! empty($residualIds)) {
            // Don't actually delete other people's content — instead skip
            // the emptiness branch test when the install already has content.
            // The next test (widget hides with content present) still runs.
            $this->markTestSkipped('content table is not empty on this install — the empty-state branch can only be tested on a clean DB');
        }

        $this->assertTrue(WordPressImportCtaWidget::canView(),
            'CTA widget must be visible when content is empty');
    }

    #[Test]
    public function widget_is_hidden_once_any_content_row_exists(): void
    {
        DB::table('content')->insert([
            'title' => self::CTA_MARKER,
            'content_type' => 'post',
            'subtype' => 'post',
            'url' => 'wp-cta-test-row',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertFalse(WordPressImportCtaWidget::canView(),
            'CTA widget must hide once any content row exists');
    }

    #[Test]
    public function widget_points_at_the_migration_resource(): void
    {
        $widget = new WordPressImportCtaWidget();
        $url = $widget->getImportUrl();

        $this->assertStringContainsString('/admin/word-press-migration-resource', $url,
            'CTA tile should link to the Filament migration resource');
    }

    #[Test]
    public function widget_is_registered_against_the_admin_dashboard(): void
    {
        $widgets = \MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry::getWidgets(
            \App\Filament\Admin\Pages\Dashboard::class,
            'admin'
        );

        $this->assertContains(
            WordPressImportCtaWidget::class,
            $widgets,
            'CTA widget must be registered on the admin dashboard panel'
        );
    }
}
