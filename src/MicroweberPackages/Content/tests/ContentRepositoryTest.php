<?php

namespace MicroweberPackages\Content\tests;

use MicroweberPackages\App\Http\Controllers\FrontendController;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Core\tests\TestCase;

class ContentRepositoryTest extends TestCase
{
    public function testGetBlogsFromRepository()
    {
        Content::truncate();

        //saving

        $title = 'Blog' . rand();
        $saveContent = array(
            'title' => $title,
            'content_type' => 'page',
            'subtype' => 'dynamic',
            'is_active' => 1
        );

        $saveContent2 = array(
            'title' => $title . '2',
            'content_type' => 'page',
            'subtype' => 'dynamic',
            'is_active' => 1
        );

        $save_id = save_content($saveContent);
        $save_id2 = save_content($saveContent2);
        $saved = get_content_by_id($save_id);
        $saved2 = get_content_by_id($save_id2);

        $this->assertEquals($saved['title'], $title);
        $this->assertEquals($saved2['title'], $title . '2');

        $first_blog = app()->content_repository->getFirstBlogPage();
        $this->assertEquals($saved['title'], $first_blog['title']);

        $all = app()->content_repository->getAllBlogPages();

        $this->assertEquals(count($all), 2);

        foreach ($all as $item) {
            $this->assertEquals($item['subtype'], 'dynamic');
        }


    }

    public function testGetShopsFromRepository()
    {
        Content::truncate();

        //saving
        $title = 'Shop' . rand();
        $saveContent = array(
            'title' => $title,
            'content_type' => 'page',
            'is_shop' => 1,
            'is_active' => 1
        );

        $saveContent2 = array(
            'title' => $title . '2',
            'content_type' => 'page',
            'is_shop' => 1,
            'is_active' => 1
        );

        $save_id = save_content($saveContent);
        $save_id2 = save_content($saveContent2);
        $saved = get_content_by_id($save_id);
        $saved2 = get_content_by_id($save_id2);

        $this->assertEquals($saved['title'], $title);
        $this->assertEquals($saved2['title'], $title . '2');

        $first_blog = app()->content_repository->getFirstShopPage();
        $this->assertEquals($saved['title'], $first_blog['title']);


        $all = app()->content_repository->getAllShopPages();

        $this->assertEquals(count($all), 2);

        foreach ($all as $item) {
            $this->assertEquals($item['is_shop'], 1);
        }


    }

}
