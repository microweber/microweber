<?php

namespace Tests\Unit\Template;

use Tests\TestCase;

/*
 * Tracked mirror of `Templates/Big2/Tests/Unit/LayoutsRenderTest.php`.
 *
 * The Big2 template lives under `Templates/Big2/` which is fully gitignored
 * (see `.gitignore:94 + .gitignore:136`). The original test file therefore
 * never ships with the repo — a fresh clone has no Big2 test and a Big2-only
 * clone has a stale test that asserts against the WRONG active template
 * because `MicroweberPackages\Core\tests\TestCase` boots with the default
 * Bootstrap template, not Big2.
 *
 * This tracked variant lives in `tests/Unit/Template/` so it survives clone
 * refreshes, marks itself skipped when Big2 isn't installed locally, and
 * activates Big2 in setUp() before exercising the parser so the assertion
 * against Big2's `content.skin-1` btn module is reproducible.
 *
 * It pins the NEW parser (LayoutProcessor) explicitly via
 * `microweber.use_legacy_parser = false`, so the test is deterministic
 * regardless of the global default. Under the new processor nested module ids
 * come from the collision-safe ModuleIdAllocator (`module-btn`, …) rather than
 * the legacy `layout-content-skin-1-<id>-<id>-btn` scheme — so the assertions
 * verify the layout's edit-field scope and that its nested `btn` module renders
 * with its params, not the legacy id string.
 */
class Big2LayoutsRenderTest extends TestCase
{
    public $template_name = 'Big2';

    protected function setUp(): void
    {
        parent::setUp();

        $templatePath = templates_path() . $this->template_name . DS;
        if (! is_dir($templatePath)) {
            $this->markTestSkipped(
                'Big2 template is not installed at ' . $templatePath
                . ' — gitignored; install via the marketplace or composer to run this test.'
            );
        }

        $skinPath = $templatePath . 'resources' . DS . 'views' . DS . 'modules'
            . DS . 'layouts' . DS . 'templates' . DS . 'content' . DS . 'skin-1.blade.php';
        if (! is_file($skinPath)) {
            $this->markTestSkipped("Big2 content.skin-1 not found at {$skinPath}.");
        }

        if (! defined('TEMPLATE_DIR')) {
            define('TEMPLATE_DIR', $templatePath);
        }

        app()->template_manager->boot_template();
        save_option('current_template', $this->template_name, 'template');
    }

    public function testRender(): void
    {
        // Exercise the NEW parser (LayoutProcessor) explicitly so the test does
        // not depend on whatever the global default happens to be.
        \Illuminate\Support\Facades\Config::set('microweber.use_legacy_parser', false);

        $layout = '<module template="content.skin-1" id="mw-module-test-1" data-type="layouts"  />';
        $render = app()->parser->process($layout);

        // Layout shell + nested modules from content.skin-1 render.
        $this->assertStringContainsString('id="background-layout--mw-module-test-1"', $render);

        // The layout's editable region keeps its field scope.
        $this->assertStringContainsString('field="layout-content-skin-1-mw-module-test-1"', $render);

        // The nested <module type="btn" …/> renders as a btn module with its params.
        $this->assertStringContainsString('module-btn', $render);
        $this->assertStringContainsString('data-type="btn"', $render);
        $this->assertStringContainsString('btn-primary', $render);
        $this->assertStringContainsString('Learn more', $render);
    }
}
