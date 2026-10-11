<?php

namespace MicroweberPackages\Core\tests;

use PHPUnit\Framework\Attributes\Test;


class TemplateTest extends TestCase
{
    public $template_name = 'Bootstrap';

    #[Test]

    public function it_get_template_name(): void {
        if (!defined('TEMPLATE_NAME')) {
            define('TEMPLATE_NAME', $this->template_name);
        }
        save_option('current_template', $this->template_name,'template');

        $current_template = app()->option_manager->get('current_template', 'template');
        $this->assertEquals($this->template_name, $current_template);
    }

    #[Test]

    public function it_get_all_templates(): void {
        $templates = site_templates();
        $this->assertTrue(!empty($templates), true);
        $this->assertTrue(!empty($templates[0]), true);
        $this->assertTrue(isset($templates[0]['name']), true);
        $this->assertTrue(isset($templates[0]['dir_name']), true);
    }
}
