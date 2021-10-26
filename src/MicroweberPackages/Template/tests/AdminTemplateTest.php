<?php

namespace MicroweberPackages\Template\tests;


use MicroweberPackages\Core\tests\TestCase;

class AdminTemplateTest extends TestCase
{

    public function testAdminCssUrl()
    {
        $admin_template = app()->template->admin->getAdminCssUrl();
        $this->assertTrue(str_contains($admin_template, 'main_with_mw.css'));

        $admin_template = app()->template->admin->getLiveEditAdminCssUrl();
        $this->assertFalse($admin_template);

    }


}
