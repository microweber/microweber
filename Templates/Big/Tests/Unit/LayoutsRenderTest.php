<?php

namespace Templates\Big\Tests\Unit;

use Tests\TestCase;

class LayoutsRenderTest extends TestCase
{
    public $template_name = 'Big';

    protected function setUp(): void
    {
        parent::setUp();

        // Activate Big so the parser resolves `content.skin-1` to Big's
        // skin (which contains the `<module type="btn"/>` the assertions
        // check for) rather than the Bootstrap fallback (no btn module).
        if (!defined('TEMPLATE_DIR')) {
            define('TEMPLATE_DIR', templates_path() . $this->template_name . DS);
        }

        app()->template_manager->boot_template();
        save_option('current_template', $this->template_name, 'template');
    }

    public function testRender()
    {

        $layout = '<module template="content.skin-1" id="mw-module-test-1" data-type="layouts"  />';
        $render = app()->parser->process($layout);
        $this->assertStringContainsString('id="background-layout--mw-module-test-1"', $render);
        $this->assertStringContainsString('layout-content-skin-1-mw-module-test-1-mw-module-test-1-btn', $render);

    }


}
