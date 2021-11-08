<?php

namespace MicroweberPackages\Template\tests;


use MicroweberPackages\Core\tests\TestCase;

class TemplateTest extends TestCase
{
    public function testGetTemplates()
    {
        $get = app()->template->site_templates();
        $this->assertTrue(!empty($get));

        $is_dir_name = true;
        foreach ($get as $item) {
            if (!isset($item['dir_name'])) {
                $is_dir_name = false;
            }
        }
        $this->assertTrue($is_dir_name);


    }

    public function testAdminCssUrl()
    {
        $admin_template = app()->template->admin->getAdminCssUrl();
        $this->assertTrue(str_contains($admin_template, 'main_with_mw.css'));

        $admin_template = app()->template->admin->getLiveEditAdminCssUrl();
        $this->assertFalse($admin_template);

    }

    public function testCompileAdminCssUrl()
    {

        save_option(array(
            'option_group' => 'admin',
            'module' => 'white_label_colors',
            'option_key' => 'admin_theme_name',
            'option_value' => 'custom'
        ));
        save_option(array(
            'option_group' => 'admin',
            'module' => 'white_label_colors',
            'option_key' => 'admin_theme_vars',
            'option_value' => '{
              "body-bg": "#efecec"
            }'
        ));


        $compile = app()->template->admin->compileAdminCss();
        $this->assertTrue(str_contains($compile, '#efecec'));

        $admin_template = app()->template->admin->getLiveEditAdminCssUrl();
        $this->assertTrue(str_contains($admin_template, 'compile_admin_live_edit_css'));

        $compile = app()->template->admin->compileLiveEditCss();
        $admin_template = app()->template->admin->getLiveEditAdminCssUrl();
        $this->assertTrue(str_contains($admin_template, 'css/admin-css/__compiled_liveedit'));


        $admin_template = app()->template->admin->getAdminCssUrl();
        $this->assertTrue(str_contains($admin_template, 'css/admin-css/__compiled_admin'));

        // reset
        app()->template->admin->resetSelectedStyleVariables();
        app()->template->admin->resetSelectedStyle();

        // get after reset
        $admin_template = app()->template->admin->getAdminCssUrl();
        $this->assertTrue(str_contains($admin_template, 'main_with_mw.css'));

        $admin_template = app()->template->admin->getLiveEditAdminCssUrl();
        $this->assertFalse($admin_template);

    }
}
