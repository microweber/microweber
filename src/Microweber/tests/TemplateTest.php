<?php

namespace Microweber\tests;

class TemplateTest extends TestCase
{
    public function testGetTemplateName()
    {
        $current_template = mw()->template->name();
        $this->assertEquals('default', $current_template);
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
