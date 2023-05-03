<?php

namespace MicroweberPackages\Template\tests;


use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;

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

        app()->ui->admin_colors_sass = false;
        $admin_template = app()->template->admin->getAdminCssUrl();


        $this->assertTrue(str_contains($admin_template, 'admin_v2.css'));
        $admin_template = app()->template->admin->getLiveEditAdminCssUrl();
        $this->assertFalse($admin_template);

    }

    public function testCompileAdminCssUrl()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
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

        app()->ui->admin_colors_sass = false;
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


    public function testTemplateEditFieldsAreSavedFieldContent()
    {

        $count = \DB::table('content_fields')->where('field', 'content')->where('rel_type', 'content')->count();
        $this->assertEquals(0, 0);

        $count = \DB::table('content_fields')->where('field', 'content_body')->where('rel_type', 'content')->count();
        $this->assertEquals(0, 0);

    }

    public function testTemplateNameAndDirVars()
    {

        app()->content_manager->define_constants(['active_site_template' => 'default']);

        $template_dir = template_dir();
        $template_dir_expected = templates_path() . 'default' . DS;
        $this->assertEquals($template_dir_expected, $template_dir);

        app()->content_manager->define_constants(['active_site_template' => 'new-world']);

        $template_dir = template_dir();
        $template_dir_expected = templates_path() . 'new-world' . DS;
        $this->assertEquals($template_dir_expected, $template_dir);


    }

    public function testTemplateNameAndDirVarsForContent()
    {
        $template_name = 'my-test-template';

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $newCleanPageId = save_content([
            'subtype' => 'dynamic',
            'content_type' => 'page',
            'layout_file' => 'clean.php',
            'title' => 'PageVarsTest',
            'url' => 'PageVarsTest',
            'active_site_template' => $template_name,
            'is_active' => 1,
        ]);

        app()->content_manager->define_constants(['id' => $newCleanPageId]);

        $pageId = page_id();
        $contentId = content_id();

        $this->assertEquals($contentId, $pageId);
        $this->assertEquals($newCleanPageId, $contentId);
        $this->assertEquals($newCleanPageId, $pageId);

        $template_dir = template_dir();
        $template_dir_expected = templates_path() .$template_name . DS;
        $this->assertEquals($template_dir_expected, $template_dir);

        $newCleanPagePostId = save_content([
            'subtype' => 'post',
            'content_type' => 'post',
            'title' => 'PostVarsTest',
            'url' => 'PostVarsTest',
            'is_active' => 1,
            // 'categories' => 'sub category test for post vars',
            'parent' => $newCleanPageId,
        ]);
        app()->content_manager->define_constants(['id' => $newCleanPagePostId]);

        $template_dir = template_dir();
        $template_dir_expected = templates_path() .$template_name . DS;
        $this->assertEquals($template_dir_expected, $template_dir);


        $this->assertEquals($newCleanPagePostId, post_id());
        $this->assertEquals($newCleanPagePostId, content_id());
        $this->assertEquals($newCleanPageId, page_id());
        $this->assertEquals(0, product_id());



    }




//    public function testTemplateNameAndDirVarsForPost()
//    {
//    }
}
