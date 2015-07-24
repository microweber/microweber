<?php

namespace Microweber\tests;

class TemplateTest extends TestCase
{
    public function testSimpeSave()
    {
        $current_template = mw()->template->name();
        $this->assertEquals('default', $current_template);
    }


}