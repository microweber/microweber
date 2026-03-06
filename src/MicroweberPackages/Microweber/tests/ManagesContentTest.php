<?php

namespace MicroweberPackages\Microweber\tests;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use Modules\Content\Tests\Unit\TestHelpers;

/**
 * @deprecated
 */
class ManagesContentTest extends TestCase
{

    use TestHelpers;

    #[Test]

    public function it_content_get_by_id(): void {
        $title = 'testContentGetById' . uniqid();
        $url = 'testContentGetById' . uniqid();
        $url = str_slug($url);
        $pageId = $this->_generatePage($url, $title);

        $get = app()->microweber->contentGetById($pageId);


        $this->assertEquals($get['id'], $pageId);
        $this->assertEquals($get['title'], $title);
        $this->assertEquals($get['url'], $url);
    }


    #[Test]


    public function it_content_get(): void {
        $params = ['limit' => 5];


        foreach (range(1, 5) as $i) {
            $url = 'test-url' . uniqid();
            $title = 'Test Title' . uniqid();
            $pageId = $this->_generatePage($url, $title);
        }


        $get = app()->microweber->contentGet($params);

        $this->assertIsArray($get);
        $this->assertCount(5, $get);
    }

    #[Test]

    public function it_content_get_by_u_r_l(): void {
        $url = 'test-url' . uniqid();
        $title = 'Test Title' . uniqid();
        $pageId = $this->_generatePage($url, $title);

        $get = app()->microweber->contentGetByURL($url);

        $this->assertIsArray($get);
        $this->assertEquals($url, $get['url']);
    }

    #[Test]

    public function it_content_get_by_title(): void {
        $url = 'test-url' . uniqid();
        $title = 'Test Title' . uniqid();
        $pageId = $this->_generatePage($url, $title);
        $get = app()->microweber->contentGetByTitle($title);

        $this->assertIsArray($get);
        $this->assertEquals($title, $get['title']);
    }

    #[Test]

    public function it_content_save(): void {


        $data = ['title' => 'Test Title' . uniqid(), 'url' => 'test-url' . uniqid(), 'content_type' => 'page', 'subtype' => 'dynamic', 'is_active' => 1];
        $savedId = app()->microweber->contentSave($data);
        $save = app()->microweber->contentGetById($savedId);

        $this->assertIsArray($save);
        $this->assertEquals($data['title'], $save['title']);
        $this->assertEquals($data['url'], $save['url']);
    }

    #[Test]

    public function it_content_unpublish(): void {
        $url = 'test-url' . uniqid();
        $title = 'Test Title' . uniqid();
        $pageId = $this->_generatePage($url, $title);
        $unpublish = app()->microweber->contentUnpublish($pageId);

        $this->assertEquals($pageId,$unpublish);
    }

    #[Test]

    public function it_content_publish(): void {
        $url = 'test-url' . uniqid();
        $title = 'Test Title' . uniqid();
        $pageId = $this->_generatePage($url, $title);
        $publish = app()->microweber->contentPublish($pageId);

        $this->assertEquals($pageId,$publish);
    }
}
