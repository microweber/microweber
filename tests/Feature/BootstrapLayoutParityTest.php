<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-09-17 — Bootstrap layout parity + the layout-discovery regression.
 *
 * 1. Bootstrap now ships native section layouts for every category Base/Big
 *    have (header, call-to-action, contacts, gallery, team, testimonials,
 *    price_lists, grids, videos, misc, design, animation-bg), with canonical
 *    category labels so they merge into the right picker tab.
 * 2. The registry must actually DISCOVER template layouts. It previously
 *    returned only the 3 base-module layouts for every template because
 *    CmsHelpers::templatesRepository() guarded on isset($app->templates) — false
 *    for a container magic property — so getTemplates() scanned no template.
 */
class BootstrapLayoutParityTest extends TestCase
{
    private string $layoutsDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->layoutsDir = base_path('Templates/Bootstrap/resources/views/modules/layouts/templates');
    }

    #[Test]
    public function bootstrap_ships_native_layouts_for_every_category(): void
    {
        // category folder => canonical category label (must match Base/Big)
        $categories = [
            'header'         => 'Header',
            'call-to-action' => 'Call to Action',
            'contacts'       => 'Contact Us',
            'gallery'        => 'Gallery',
            'team'           => 'Team',
            'testimonials'   => 'Testimonials',
            'price_lists'    => 'Price Lists',
            'grids'          => 'Grids',
            'videos'         => 'Videos',
            'misc'           => 'Misc',
            'design'         => 'Design',
            'animation-bg'   => 'Animated Backgrounds',
        ];

        foreach ($categories as $folder => $label) {
            $dir = $this->layoutsDir . '/' . $folder;
            $this->assertDirectoryExists($dir, "Bootstrap is missing the native '{$folder}' layout category");

            $files = glob($dir . '/*.blade.php');
            $this->assertNotEmpty($files, "Bootstrap '{$folder}' category has no layout skins");

            $src = file_get_contents($files[0]);
            $this->assertStringContainsString('type: layout', $src, "{$folder} must declare type: layout");
            $this->assertStringContainsString('categories: ' . $label, $src,
                "{$folder} front-matter must use the canonical category label '{$label}'");
            $this->assertStringContainsString('<x-layout-section', $src,
                "{$folder} must use the shared x-layout-section wrapper");
        }
    }

    #[Test]
    public function layout_registry_discovers_template_layouts_not_just_base(): void
    {
        // Regression guard for the isset($app->templates) magic-property bug that
        // collapsed every template's catalog to the 3 base-module layouts.
        $layouts = app()->microweber->getTemplates('layouts', 'bootstrap');

        $this->assertGreaterThan(50, count($layouts),
            'Layout registry must discover template layouts (regressed to 3 when templatesRepository() used isset())');

        $cats = [];
        foreach ($layouts as $layout) {
            $c = $layout['categories'] ?? ($layout['category'] ?? null);
            if (is_array($c)) {
                $cats = array_merge($cats, $c);
            } elseif ($c) {
                $cats[] = $c;
            }
        }
        $cats = array_map('strval', $cats);

        foreach (['Header', 'Team', 'Gallery', 'Testimonials'] as $needed) {
            $this->assertContains($needed, $cats,
                "Registry should expose the native Bootstrap '{$needed}' category");
        }
    }
}
