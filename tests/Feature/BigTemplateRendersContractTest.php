<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use FilesystemIterator;
use Tests\TestCase;

/**
 * Contract test for the in-core Big template.
 *
 * Verifies the template is discoverable/registered and that EVERY one of its
 * layout skins renders through the real Microweber parser pipeline with no
 * exception, no un-compiled `<x-…>` component leak, and no literal `&quot;`
 * attribute-escape artifact. Catches the "a migrated skin fatals / leaks" and
 * "an `Undefined variable $layout_classes` style" regressions across the whole
 * template in one pass.
 */
class BigTemplateRendersContractTest extends TestCase
{
    private const TEMPLATE = 'Big';

    private function skinNames(): array
    {
        $base = base_path('Templates/' . self::TEMPLATE . '/resources/views/modules/layouts/templates');
        $this->assertDirectoryExists($base, 'Big template layouts dir missing — is the template installed in core?');

        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS));
        $skins = [];
        foreach ($rii as $f) {
            if ($f->getExtension() !== 'php') {
                continue;
            }
            $rel = substr($f->getPathname(), strlen($base) + 1);
            if (!str_ends_with($rel, '.blade.php')) {
                continue;
            }
            $skins[] = substr($rel, 0, -strlen('.blade.php'));
        }
        sort($skins);

        return $skins;
    }

    #[Test]
    public function the_big_template_is_registered_and_resolvable(): void
    {
        // Identity from the on-disk config (env-independent — get_config() couples
        // to the active-template adapter, which varies in the test boot).
        $config = require base_path('Templates/' . self::TEMPLATE . '/config/config.php');
        $this->assertSame('Big', $config['name'] ?? null, "Big template config name should be 'Big'");

        $module = json_decode((string) file_get_contents(base_path('Templates/' . self::TEMPLATE . '/module.json')), true);
        $this->assertSame('Big', $module['name'] ?? null);
        $this->assertSame('Templates\\Big\\Providers\\BigServiceProvider', $module['providers'][0] ?? null);

        // View namespace resolves (registered via the service provider).
        $this->assertTrue(view()->exists('templates.big::layouts.master'), 'templates.big::layouts.master must resolve');
        $this->assertTrue(view()->exists('templates.big::index'), 'templates.big::index must resolve');
    }

    #[Test]
    public function every_big_layout_skin_renders_clean_through_the_parser(): void
    {
        app()->template_manager->templateAdapter->templateFolderName = self::TEMPLATE;

        $skins = $this->skinNames();
        $this->assertGreaterThan(300, count($skins), 'Expected the full Big skin set (~406)');

        $failures = [];
        foreach ($skins as $skin) {
            try {
                $html = load_module('layouts', ['template' => $skin, 'id' => 'test-' . md5($skin)]);
                $rendered = app()->parser->process($html);

                if (str_contains($rendered, '<x-')) {
                    $failures[] = "{$skin}: un-compiled <x-…> component leak";
                } elseif (str_contains($rendered, '&quot;')) {
                    $failures[] = "{$skin}: literal &quot; attribute-escape artifact";
                }
            } catch (\Throwable $e) {
                $failures[] = "{$skin}: " . substr($e->getMessage(), 0, 120);
            }
        }

        $this->assertSame(
            [],
            $failures,
            count($failures) . " of " . count($skins) . " Big skins failed to render cleanly:\n - " . implode("\n - ", $failures)
        );
    }

    #[Test]
    public function migrated_skins_emit_their_component_markup(): void
    {
        app()->template_manager->templateAdapter->templateFolderName = self::TEMPLATE;

        // skin => a class the migrated component is guaranteed to emit
        $cases = [
            'titles/skin-1'          => 'text-center',     // section-heading wrapper
            'call-to-action/skin-1'  => 'cta-block',       // cta
            'features/skin-2'        => 'feature',         // feature-item
            'videos/skin-1'          => 'ratio',           // video-embed
            'price_lists/skin-19'    => 'pricing-card-title', // pricing-row
            'header/skin-1'          => 'mw-layout-container', // layout-section
        ];

        foreach ($cases as $skin => $needle) {
            $rendered = app()->parser->process(
                load_module('layouts', ['template' => $skin, 'id' => 'test-' . md5($skin)])
            );
            $this->assertStringContainsString(
                $needle,
                $rendered,
                "Big {$skin} should emit '{$needle}' from its migrated component"
            );
        }
    }
}
