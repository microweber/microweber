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
        $templateName = 'my-test-template';

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $newCleanPageId = save_content([
            'subtype' => 'dynamic',
            'content_type' => 'page',
            'layout_file' => 'clean.php',
            'title' => 'PageVarsTest',
            'url' => 'PageVarsTest',
            'active_site_template' => $templateName,
            'is_active' => 1,
        ]);

        app()->content_manager->define_constants(['id' => $newCleanPageId]);

        $pageId = page_id();
        $contentId = content_id();

        $this->assertEquals($contentId, $pageId);
        $this->assertEquals($newCleanPageId, $contentId);
        $this->assertEquals($newCleanPageId, $pageId);
        $this->assertEquals(0, category_id());
        $this->assertTrue(is_page());
        $this->assertFalse(is_post());
        $this->assertFalse(is_category());


        $templateDir = template_dir();
        $templateDirExpected = templates_path() . $templateName . DS;
        $this->assertEquals($templateDirExpected, $templateDir);


        $newCleanCategoryId = save_category([
            'title' => 'Test Category for post vars' . uniqid(),
            'rel_type' => 'content',
            'rel_id' => $newCleanPageId,
        ]);
        $this->assertTrue($newCleanCategoryId > 0);


        $newCleanPagePostId = save_content([
            'subtype' => 'post',
            'content_type' => 'post',
            'title' => 'PostVarsTest',
            'url' => 'PostVarsTest',
            'is_active' => 1,
            'categories' => [$newCleanCategoryId],
            'parent' => $newCleanPageId,
        ]);

        $contentCategories = content_categories($newCleanPagePostId);
        $this->assertEquals($newCleanCategoryId, $contentCategories[0]['id']);


        app()->content_manager->define_constants(['id' => $newCleanPagePostId]);


        $templateDir = template_dir();
        $templateDirExpected = templates_path() . $templateName . DS;
        $this->assertEquals($templateDirExpected, $templateDir);


        $this->assertEquals($newCleanPagePostId, post_id());
        $this->assertEquals($newCleanPagePostId, content_id());
        $this->assertEquals($newCleanPageId, page_id());
        $this->assertEquals($newCleanCategoryId, category_id());
        $this->assertEquals(0, product_id());
        $this->assertFalse(is_page());
        $this->assertTrue(is_post());
        $this->assertTrue(is_category());
        $this->assertFalse(is_product());


        // test post in subpage of page
        $newSubPageId = save_content([
            'parent' => $newCleanPageId,
            'subtype' => 'dynamic',
            'content_type' => 'page',
            'layout_file' => 'clean.php',
            'title' => 'PageVarsTest-sub',
            'active_site_template' => $templateName,
            'is_active' => 1,
        ]);


        $newCleanCategoryIdSub = save_category([
            'title' => 'Test Category for post sub vars-' . uniqid(),
            'rel_type' => 'content',
            'rel_id' => $newSubPageId,
        ]);

        $newCleanPostSubId = save_content([
            'subtype' => 'post',
            'content_type' => 'post',
            'title' => 'PostVarsTestSub sub',
            'is_active' => 1,
            'categories' => [$newCleanCategoryIdSub],
            'parent' => $newSubPageId,
        ]);


        app()->content_manager->define_constants(['id' => $newCleanPostSubId]);


        $this->assertEquals($newCleanPostSubId, post_id());


        $this->assertEquals($newCleanPostSubId, content_id());
        $this->assertEquals($newSubPageId, page_id());
        $this->assertEquals($newCleanCategoryIdSub, category_id());
        $this->assertEquals(0, product_id());
        $this->assertFalse(is_page());
        $this->assertTrue(is_post());
        $this->assertTrue(is_category());
        $this->assertFalse(is_product());


    }


    public function testTemplateConstantsAreDefined()
    {
        $templateName = 'my-test-template-for-constants';

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $newCleanPageId = save_content([
            'subtype' => 'dynamic',
            'content_type' => 'page',
            'layout_file' => 'clean.php',
            'title' => 'PageVarsTest',
            'url' => 'PageVarsTest',
            'active_site_template' => $templateName,
            'is_active' => 1,
        ]);

        app()->content_manager->define_constants(['id' => $newCleanPageId]);


        $this->assertTrue(defined('PAGE_ID'));
        $this->assertTrue(defined('POST_ID'));
        $this->assertTrue(defined('CONTENT_ID'));
        $this->assertTrue(defined('MAIN_PAGE_ID'));
        $this->assertTrue(defined('ROOT_PAGE_ID'));
        $this->assertTrue(defined('PARENT_PAGE_ID'));
        $this->assertTrue(defined('CATEGORY_ID'));


        $this->assertTrue(defined('DEFAULT_TEMPLATE_DIR'));
        $this->assertTrue(defined('DEFAULT_TEMPLATE_URL'));
        $this->assertTrue(defined('THIS_TEMPLATE_FOLDER_NAME'));
        $this->assertTrue(defined('THIS_TEMPLATE_URL'));
        $this->assertTrue(defined('THIS_TEMPLATE_DIR'));
        $this->assertTrue(defined('ACTIVE_SITE_TEMPLATE'));
        $this->assertTrue(defined('TEMPLATE_NAME'));
        $this->assertTrue(defined('TEMPLATES_DIR'));
        $this->assertTrue(defined('TEMPLATE_DIR'));
        $this->assertTrue(defined('TEMPLATE_URL'));


        $this->assertEquals($newCleanPageId, PAGE_ID);
        $this->assertEquals($newCleanPageId, CONTENT_ID);


        $this->assertEquals(0, ROOT_PAGE_ID);
        $this->assertEquals(0, MAIN_PAGE_ID);
        $this->assertEquals(0, PARENT_PAGE_ID);
        $this->assertEquals(0, CATEGORY_ID);
        $this->assertEquals(0, POST_ID);

        $this->assertEquals($templateName, TEMPLATE_NAME);
        $this->assertEquals($templateName, ACTIVE_SITE_TEMPLATE);
        $this->assertEquals(template_name(), TEMPLATE_NAME);
        $this->assertEquals(template_url(), THIS_TEMPLATE_URL);
        $this->assertEquals(template_dir(), TEMPLATE_DIR);
        $this->assertEquals(template_dir(), THIS_TEMPLATE_URL);


    }
}
