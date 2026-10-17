<?php

declare(strict_types=1);

namespace MicroweberPackages\Module\tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Characterization tests pinning the CURRENT behaviour of ModuleManager's
 * read-only / pure helper methods. These lock in observed behaviour (not
 * necessarily ideal behaviour) so the 1098-line god class can be safely
 * decomposed into traits without silent regressions. All assertions here are
 * read-only against the real module filesystem — no DB mutation.
 */
class ModuleManagerCharacterizationTest extends TestCase
{
    private function mm()
    {
        return app()->module_manager;
    }

    #[Test]
    public function exists_reports_real_and_missing_modules(): void
    {
        $this->assertTrue($this->mm()->exists('content'));
        $this->assertFalse($this->mm()->exists('definitely_not_a_module_xyz'));
    }

    #[Test]
    public function dir_path_and_locate_resolve_the_module_folder(): void
    {
        $expected = normalize_path(base_path('Modules/Content/'), true);
        $norm = fn ($p) => normalize_path($p, true);

        $this->assertSame($expected, $norm($this->mm()->dir('content')));
        $this->assertSame($expected, $norm($this->mm()->path('content')));
        // locate() historically did view fallback; now delegates to dir().
        $this->assertSame($expected, $norm($this->mm()->locate('content')));
    }

    #[Test]
    public function is_installed_true_for_core_module(): void
    {
        $this->assertTrue($this->mm()->is_installed('content'));
    }

    #[Test]
    public function css_class_slugifies_and_prefixes_module(): void
    {
        $this->assertSame('module-content', $this->mm()->css_class('content'));
        $this->assertSame('module-shop-products', $this->mm()->css_class('shop/products'));
        // separators collapse to hyphen: / \ space %20 _
        $this->assertSame('module-a-b-c-d', $this->mm()->css_class('a_b c/d'));
    }

    #[Test]
    public function format_attr_escapes_and_cascades_the_ampersand_last(): void
    {
        // The '&' replacement runs LAST, so entities emitted by earlier rules get
        // their '&' re-escaped (and ';' becomes &#59;). This pins that cascade.
        $this->assertSame('&amp&#59;quot&#59;', $this->mm()->format_attr('"'));
        $this->assertSame(
            'a&amp&#59;lt&#59;b&amp&#59;gt&#59;&amp&#59;&#91&#59;c&#93&#59;&#123&#59;d&#125&#59;&#96&#59;&#59;e',
            $this->mm()->format_attr('a<b>&[c]{d}`;e')
        );
    }

    #[Test]
    public function info_returns_module_descriptor_for_core_module(): void
    {
        $info = $this->mm()->info('content');
        $this->assertSame('content', $info['module']);
        $this->assertSame('Content', $info['name']);
        $this->assertArrayHasKey('description', $info);
        $this->assertArrayHasKey('icon', $info);
    }
}
