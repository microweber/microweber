<?php

namespace MicroweberPackages\Template\tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Single-item layout skins (alternative renderings for product & post).
 *
 * A product or post keeps its site template but can pick one of the template's
 * registered single-item skins as its layout_file (admin "Layout" tab). The
 * skins live under Templates/Big2/resources/views/single-skins/{product,post}/
 * and are self-contained full layouts (they extend master + emit the real
 * modules). The default rendering stays product.blade.php / post.blade.php,
 * which are now ALSO registered as the "Classic" skin.
 *
 * This pins the four contracts the feature rests on:
 *   1. LayoutsManager lists each type's skins under a content_type filter;
 *   2. the page / default picker never shows a single-item skin;
 *   3. the template resolver renders the chosen skin (and still falls back to
 *      the content_type default for NULL / inherit);
 *   4. every skin file is a registered, self-contained, chrome-wrapped layout.
 */
class SingleItemLayoutSkinsTest extends TestCase
{
    private string $template = 'Big2';

    private string $viewsDir = '';

    protected function setUp(): void
    {
        parent::setUp();
        $this->viewsDir = templates_dir() . $this->template . DS . 'resources' . DS . 'views' . DS;
    }

    protected function assertPreConditions(): void
    {
        parent::assertPreConditions();
        if (!is_dir(templates_dir() . $this->template)) {
            $this->markTestSkipped("Template {$this->template} not installed");
        }
    }

    /** layout_file values (DS-normalised) returned by get_all for a content_type. */
    private function layoutFilesFor($contentType = null): array
    {
        $options = ['site_template' => $this->template, 'no_cache' => true, 'no_folder_sort' => true];
        if ($contentType !== null) {
            $options['content_type'] = $contentType;
        }
        $all = app()->layouts_manager->get_all($options);
        $files = [];
        foreach (is_array($all) ? $all : [] as $l) {
            if (isset($l['layout_file'])) {
                $files[] = str_replace('\\', '/', (string) $l['layout_file']);
            }
        }
        return $files;
    }

    #[Test]
    public function the_product_picker_lists_exactly_the_product_skins(): void
    {
        $files = $this->layoutFilesFor('product');

        $this->assertContains('product.blade.php', $files, 'Classic (the default blade) must be a selectable product skin.');
        $this->assertContains('single-skins/product/materialbuildsafe.blade.php', $files);
        $this->assertContains('single-skins/product/minimal.blade.php', $files);

        // No post skins or generic page layouts leak into the product list.
        foreach ($files as $f) {
            $this->assertStringNotContainsString('single-skins/post/', $f);
            $this->assertNotContains($f, ['clean.blade.php', 'index.blade.php', 'blog.blade.php', 'shop.blade.php']);
        }
    }

    #[Test]
    public function the_post_picker_lists_exactly_the_post_skins(): void
    {
        $files = $this->layoutFilesFor('post');

        $this->assertContains('post.blade.php', $files, 'Classic (the default blade) must be a selectable post skin.');
        $this->assertContains('single-skins/post/magazine.blade.php', $files);
        $this->assertContains('single-skins/post/minimal.blade.php', $files);

        foreach ($files as $f) {
            $this->assertStringNotContainsString('single-skins/product/', $f);
        }
    }

    #[Test]
    public function the_page_picker_never_shows_a_single_item_skin(): void
    {
        $files = $this->layoutFilesFor(null);

        $this->assertNotEmpty($files, 'The page/default picker must still list the generic layouts.');
        $this->assertContains('clean.blade.php', $files);
        foreach ($files as $f) {
            $this->assertStringNotContainsString('single-skins/', $f,
                'A single-item skin must never appear in the page/default layout picker.');
            // product.blade.php / post.blade.php are content_type-tagged, so the
            // page picker must drop them too.
            $this->assertNotContains($f, ['product.blade.php', 'post.blade.php']);
        }
    }

    #[Test]
    public function the_resolver_renders_the_chosen_skin_and_falls_back_to_the_default(): void
    {
        $base = [
            'id' => 123456,
            'content_type' => 'product',
            'subtype' => 'product',
            'active_site_template' => $this->template,
            'parent' => 0,
        ];

        $cases = [
            'single-skins/product/materialbuildsafe.blade.php' => 'materialbuildsafe.blade.php',
            'single-skins/product/minimal.blade.php' => 'minimal.blade.php',
            null => 'product.blade.php',      // NULL -> content_type default
            'inherit' => 'product.blade.php', // inherit -> content_type default
        ];

        foreach ($cases as $layoutFile => $expectedBasename) {
            $page = $base;
            $page['layout_file'] = $layoutFile;
            $render = (string) app()->template_manager->get_layout($page);
            $this->assertNotSame('', $render, 'Resolver returned no render file for layout_file=' . var_export($layoutFile, true));
            $this->assertSame($expectedBasename, basename($render),
                'layout_file=' . var_export($layoutFile, true) . ' must render ' . $expectedBasename);
        }
    }

    #[Test]
    public function every_skin_file_is_a_registered_self_contained_layout(): void
    {
        $skins = [
            'single-skins/product/materialbuildsafe.blade.php' => 'product',
            'single-skins/product/minimal.blade.php' => 'product',
            'single-skins/post/magazine.blade.php' => 'post',
            'single-skins/post/minimal.blade.php' => 'post',
            // The defaults double as the "Classic" skin and must be registered too.
            'product.blade.php' => 'product',
            'post.blade.php' => 'post',
        ];

        foreach ($skins as $rel => $type) {
            $path = $this->viewsDir . str_replace('/', DS, $rel);
            $this->assertFileExists($path, "Skin {$rel} must exist");
            $src = (string) file_get_contents($path);

            $this->assertMatchesRegularExpression('/type:\s*layout/i', $src, "{$rel} must declare type: layout");
            $this->assertMatchesRegularExpression('/content_type:\s*' . $type . '\b/i', $src,
                "{$rel} must declare content_type: {$type}");
            $this->assertStringContainsString("@extends('templates.big2::layouts.master')", $src,
                "{$rel} must extend the master layout so it renders with site chrome");
            $this->assertStringContainsString("@section('content')", $src, "{$rel} must open the content section");
            $this->assertStringContainsString('@endsection', $src, "{$rel} must close the content section");
        }
    }
}
