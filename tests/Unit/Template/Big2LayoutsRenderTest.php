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
        $layout = '<module template="content.skin-1" id="mw-module-test-1" data-type="layouts"  />';
        $render = app()->parser->process($layout);

        $this->assertStringContainsString('id="background-layout--mw-module-test-1"', $render);
        $this->assertStringContainsString('layout-content-skin-1-mw-module-test-1-mw-module-test-1-btn', $render);
    }
}
