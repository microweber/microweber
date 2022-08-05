<?php

namespace MicroweberPackages\Core\tests;


class TemplateTest extends TestCase
{
    public $template_name = 'default';

    public function testGetTemplateName()
    {
        if (!defined('TEMPLATE_NAME')) {
            define('TEMPLATE_NAME', $this->template_name);
        }
        save_option('current_template', $this->template_name,'template');

        $current_template = app()->option_manager->get('current_template', 'template');
        $this->assertEquals($this->template_name, $current_template);
    }

    public function testGetAllTemplates()
    {
        $templates = site_templates();
        $this->assertTrue(!empty($templates), true);
        $this->assertTrue(!empty($templates[0]), true);
        $this->assertTrue(isset($templates[0]['name']), true);
        $this->assertTrue(isset($templates[0]['dir_name']), true);
    }
}
