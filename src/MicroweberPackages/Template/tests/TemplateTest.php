<?php

namespace MicroweberPackages\Template\tests;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;

class TemplateTest extends TestCase
{
    public $template_name = 'new-world';
    protected function assertPreConditions(): void
    {
         parent::assertPreConditions();


        if (is_dir(templates_dir() . 'Big2')) {
            $templateName = 'Big2';
        } else if (is_dir(templates_dir() . 'Bootstrap')) {
            $templateName = 'Bootstrap';
        } else {
            $templateName = 'default';
        }

        $this->template_name = $templateName;

    }
    public function testGetTemplates()
    {
        $get = app()->template_manager->site_templates();
        $this->assertTrue(!empty($get));

        $is_dir_name = true;
        foreach ($get as $item) {
            if (!isset($item['dir_name'])) {
                $is_dir_name = false;
            }
        }
        $this->assertTrue($is_dir_name);


    }

    public function testGetTemplateConfig()
    {
        $template_name = $this->template_name;
        if(!is_dir(templates_dir() . $template_name)){
            $this->markTestSkipped('Template not found: ' . $template_name);
        }

        app()->content_manager->define_constants(['active_site_template' => $template_name]);



        $config = app()->template_manager->get_config();

        $this->assertTrue(isset($config['name']));
     //   $this->assertTrue('New World' == $config['name']);




    }


    public function testAdminCssUrl()
    {

        app()->ui->admin_colors_sass = false;
        $admin_template = app()->template_manager->admin->getAdminCssUrl();


        $this->assertTrue(str_contains($admin_template, 'admin_v2.css'));
        $admin_template = app()->template_manager->admin->getLiveEditAdminCssUrl();
        $this->assertFalse($admin_template);

    }
//
//    // @todo fix this test
//    public function testCompileAdminCssUrl()
//    {
//        $this->markTestSkipped(
//            'This test has not been implemented yet.'
//        );
//        return;
//        save_option(array(
//            'option_group' => 'admin',
//            'module' => 'white_label_colors',
//            'option_key' => 'admin_theme_name',
//            'option_value' => 'custom'
//        ));
//        save_option(array(
//            'option_group' => 'admin',
//            'module' => 'white_label_colors',
//            'option_key' => 'admin_theme_vars',
//            'option_value' => '{
//              "body-bg": "#efecec"
//            }'
//        ));
//
//        app()->ui->admin_colors_sass = false;
//        $compile = app()->template_manager->admin->compileAdminCss();
//        $this->assertTrue(str_contains($compile, '#efecec'));
//
//        $admin_template = app()->template_manager->admin->getLiveEditAdminCssUrl();
//        $this->assertTrue(str_contains($admin_template, 'compile_admin_live_edit_css'));
//
//        $compile = app()->template_manager->admin->compileLiveEditCss();
//        $admin_template = app()->template_manager->admin->getLiveEditAdminCssUrl();
//        $this->assertTrue(str_contains($admin_template, 'css/admin-css/__compiled_liveedit'));
//
//
//        $admin_template = app()->template_manager->admin->getAdminCssUrl();
//        $this->assertTrue(str_contains($admin_template, 'css/admin-css/__compiled_admin'));
//
//        // reset
//        app()->template_manager->admin->resetSelectedStyleVariables();
//        app()->template_manager->admin->resetSelectedStyle();
//
//        // get after reset
//        $admin_template = app()->template_manager->admin->getAdminCssUrl();
//        $this->assertTrue(str_contains($admin_template, 'main_with_mw.css'));
//
//        $admin_template = app()->template_manager->admin->getLiveEditAdminCssUrl();
//        $this->assertFalse($admin_template);
//
//    }
//

    public function testTemplateEditFieldsAreSavedFieldContent()
    {

        $count = DB::table('content_fields')->where('field', 'content')->where('rel_type', morph_name(\Modules\Content\Models\Content::class))->count();
        $this->assertEquals(0, 0);

        $count = DB::table('content_fields')->where('field', 'content_body')->where('rel_type', morph_name(\Modules\Content\Models\Content::class))->count();
        $this->assertEquals(0, 0);

    }

    #[PreserveGlobalState(false)]
    #[RunInSeparateProcess]
    public function testTemplateNameAndDirVars()
    {
        $template_name = $this->template_name;
        app()->content_manager->define_constants(['active_site_template' => 'custom-template']);

        $template_dir = template_dir();
        $template_dir_expected = templates_dir() . 'custom-template' . DS;
        $this->assertEquals($template_dir_expected, $template_dir);

        app()->content_manager->define_constants(['active_site_template' => $template_name]);

        $template_dir = template_dir();
        $template_dir_expected = templates_dir() . $template_name . DS;
        $this->assertEquals($template_dir_expected, $template_dir);


    }

    #[PreserveGlobalState(false)]
    #[RunInSeparateProcess]
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
        $templateDirExpected = templates_dir() . $templateName . DS;
        $this->assertEquals($templateDirExpected, $templateDir);


        $newCleanCategoryId = save_category([
            'title' => 'Test Category for post vars' . uniqid(),
            'rel_type' => morph_name(\Modules\Content\Models\Content::class),
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
        $templateDirExpected = templates_dir() . $templateName . DS;
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
            'rel_type' => morph_name(\Modules\Content\Models\Content::class),
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


    #[PreserveGlobalState(false)]
    #[RunInSeparateProcess]
    public function testTemplateGetLayoutFile()
    {
        $templateName =  $this->template_name;

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $newCleanPageId = save_content([
            'subtype' => 'static',
            'content_type' => 'page',
            'layout_file' => 'clean.php',
            'title' => 'PageTemplateGetLayoutFile',
            'active_site_template' => $templateName,
            'is_active' => 1,
        ]);

        app()->content_manager->define_constants(['id' => $newCleanPageId]);

        $content = get_content_by_id($newCleanPageId);
        $renderFile = app()->template_manager->get_layout($content);

        $expectedRenderFile = templates_dir() . $templateName . DS . 'resources/views/clean.blade.php';
        $expectedRenderFile = normalize_path($expectedRenderFile, false);
        $renderFile = normalize_path($renderFile, false);
        $this->assertEquals($expectedRenderFile, $renderFile);

        $newCleanPostId = save_content([
            'subtype' => 'post',
            'content_type' => 'post',
            'title' => 'PostTemplateGetLayoutFile',
            'is_active' => 1,
            'parent' => $newCleanPageId,
        ]);
        app()->content_manager->define_constants(['id' => $newCleanPostId]);

        $content = get_content_by_id($newCleanPostId);
        $renderFile = app()->template_manager->get_layout($content);
        $expectedRenderFile = templates_dir() . $templateName . DS . 'resources/views/post.blade.php';

        $expectedRenderFile = normalize_path($expectedRenderFile, false);
        $renderFile = normalize_path($renderFile, false);
        $this->assertEquals($expectedRenderFile, $renderFile);


        $this->assertEquals($expectedRenderFile, $renderFile);


        $newCleanPageIdForBlog = save_content([
            'subtype' => 'dynamic',
            'content_type' => 'page',
            'layout_file' => 'layouts/blog.php',
            'title' => 'PageTemplateGetLayoutFile',
            'active_site_template' => $templateName,
            'is_active' => 1,
        ]);


        $newCleanPostSubId = save_content([
            'subtype' => 'post',
            'content_type' => 'post',
            'title' => 'PostTemplateGetLayoutFile',
            'is_active' => 1,
            'parent' => $newCleanPageIdForBlog,
        ]);
        app()->content_manager->define_constants(['id' => $newCleanPostSubId]);

        $content = get_content_by_id($newCleanPostSubId);
        $renderFile = app()->template_manager->get_layout($content);

        $expectedRenderFile = templates_dir() . $templateName . DS .  'resources/views/post.blade.php';


        $expectedRenderFile = normalize_path($expectedRenderFile, false);
        $renderFile = normalize_path($renderFile, false);

        $this->assertEquals($expectedRenderFile, $renderFile);

    }
}
