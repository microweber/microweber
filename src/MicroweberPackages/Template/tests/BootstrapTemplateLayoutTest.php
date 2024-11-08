<?php

namespace MicroweberPackages\Template\tests;


use MicroweberPackages\Content\tests\TestHelpers;
use MicroweberPackages\Core\tests\TestCase;

class BootstrapTemplateLayoutTest extends TestCase
{
    public $template_name = 'Bootstrap';

    use TestHelpers;

    protected function assertPreConditions(): void
    {
        parent::assertPreConditions();

        $is_dir = templates_dir() . $this->template_name;
        if (!$is_dir) {
            $this->markTestSkipped('Template not found');
        }

    }


    public function testGetLayoutBootrapTemplate()
    {
        $templateName = $this->template_name;
        $newCleanPageId = save_content([
            'content_type' => 'page',
            'title' => 'getLayoutBootrapTemplateTest',
            'active_site_template' => $templateName,
            'layout_file' => 'clean.blade.php',
            'is_active' => 1,
        ]);


        $is_laravel_template = app()->template_manager->is_laravel_template($templateName);
        $template_config = app()->template_manager->get_config($templateName);
        $template_render_file = app()->template_manager->get_layout(['id' => $newCleanPageId]);


        $this->assertTrue(str_ends_with($template_render_file, 'clean.blade.php'));
        $this->assertTrue($is_laravel_template);
        $this->assertIsArray($template_config);
        $this->assertArrayHasKey('dir_name', $template_config);
        $this->assertArrayHasKey('name', $template_config);
        $this->assertArrayHasKey('is_symlink', $template_config);

    }

    public function testGetLayoutBootstrapTemplateHomePage()
    {
        $templateName = $this->template_name;
        $newCleanPageId = save_content([
            'content_type' => 'page',
            'title' => 'testGetLayoutBootstrapTemplateHomePage',
            'active_site_template' => $templateName,
            'is_home' => 1,
            'is_active' => 1,
        ]);

        $template_render_file = app()->template_manager->get_layout(['id' => $newCleanPageId]);
        $this->assertTrue(str_ends_with($template_render_file, 'index.blade.php'));
    }

    public function testGetLayoutBootstrapTemplateCleanPage()
    {
        $templateName = $this->template_name;
        $newCleanPageId = save_content([
            'content_type' => 'page',
            'title' => 'testGetLayoutBootstrapTemplateCleanPage',
            'active_site_template' => $templateName,
            'layout_file' => 'clean.blade.php',
            'is_active' => 1,
        ]);

        $template_render_file = app()->template_manager->get_layout(['id' => $newCleanPageId]);
        $this->assertTrue(str_ends_with($template_render_file, 'clean.blade.php'));
    }

    public function testGetLayoutBootstrapTemplateBlogPage()
    {
        $templateName = $this->template_name;
        $newCleanPageId = save_content([
            'content_type' => 'page',
            'title' => 'testGetLayoutBootstrapTemplateBlogPage',
            'active_site_template' => $templateName,
            'layout_file' => 'blog.blade.php',
            'subtype' => 'dynamic',
            'is_active' => 1,
        ]);

        $template_render_file = app()->template_manager->get_layout(['id' => $newCleanPageId]);
        $this->assertTrue(str_ends_with($template_render_file, 'blog.blade.php'));

        $addPostId = $this->_generatePost('testGetLayoutBootstrapTemplateBlogPage', 'testGetLayoutBootstrapTemplateBlogPage', $newCleanPageId);

        $template_render_file = app()->template_manager->get_layout(['id' => $addPostId]);
        $this->assertTrue(str_ends_with($template_render_file, 'post.blade.php'));

    }

    public function testGetLayoutBootstrapTemplateShopPage()
    {

        $templateName = $this->template_name;
        $newCleanPageId = save_content([
            'content_type' => 'page',
            'title' => 'testGetLayoutBootstrapTemplateShopPage',
            'active_site_template' => $templateName,
            'layout_file' => 'shop.blade.php',
            'is_shop' => 1,
            'is_active' => 1,
        ]);
        $template_render_file = app()->template_manager->get_layout(['id' => $newCleanPageId]);
        $this->assertTrue(str_ends_with($template_render_file, 'shop.blade.php'));

        $addProductId = $this->_generateProduct('testGetLayoutBootstrapTemplateShopPage', 'testGetLayoutBootstrapTemplateShopPage', $newCleanPageId);

        $template_render_file = app()->template_manager->get_layout(['id' => $addProductId]);
        $this->assertTrue(str_ends_with($template_render_file, 'product.blade.php'));


    }
}
