<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Tests\Unit;

use MicroweberPackages\TemplateCustomCss\Services\CssUrlRewriter;
use MicroweberPackages\TemplateCustomCss\Tests\TestCase;

class CssUrlRewriterTest extends TestCase
{
    public function test_for_storage_rewrites_userfiles_to_relative(): void
    {
        $r = new CssUrlRewriter('http://example.test/userfiles/', 'http://example.test/');
        $css = '.x { background: url(http://example.test/userfiles/media/a.png); }';
        $out = $r->forStorage($css);
        $this->assertStringContainsString('../../media/a.png', $out);
        $this->assertStringNotContainsString('http://example.test/userfiles/', $out);
    }

    public function test_for_storage_fixes_double_relative_media(): void
    {
        $r = new CssUrlRewriter('http://example.test/userfiles/');
        $css = '.x { background: url(././media/pic.jpg); }';
        $out = $r->forStorage($css);
        // After first replace becomes full userfiles URL then relative
        $this->assertStringContainsString('../../media/pic.jpg', $out);
    }

    public function test_for_display_expands_relative(): void
    {
        $r = new CssUrlRewriter('http://example.test/userfiles/');
        $css = '.x { background: url(../../media/a.png); }';
        $out = $r->forDisplay($css);
        $this->assertStringContainsString('http://example.test/userfiles/media/a.png', $out);
    }

    public function test_backup_export_import_roundtrip(): void
    {
        $r = new CssUrlRewriter('http://example.test/userfiles/', 'http://example.test/');
        $css = '/* site: http://example.test/page */ body { color: red; }';
        $exported = $r->forBackupExport($css, '{SITE_URL}');
        // If url_manager is available it may use its own token; otherwise our token
        $this->assertIsString($exported);
        $imported = $r->forBackupImport($exported, '{SITE_URL}');
        $this->assertIsString($imported);
    }

    public function test_empty_css_passthrough(): void
    {
        $r = new CssUrlRewriter('http://example.test/userfiles/');
        $this->assertSame('', $r->forStorage(''));
        $this->assertSame('', $r->forDisplay(''));
    }
}
