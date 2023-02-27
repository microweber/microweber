<?php

namespace MicroweberPackages\Core\DatabaseManager\tests;

use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Core\tests\TestCase;

class DbTest extends TestCase
{
    private $save = array(
        'content_type' => 'page',
        'subtype' => 'static',
        'title' => 'one page',
        'parent' => '0',
        'is_deleted' => '0',
    );
    private $save_post = array(
        'content_type' => 'post',
        'subtype' => 'static',
        'title' => 'one post',
        'parent' => '0',
        'is_deleted' => '0',
    );

    private $content;
    private $content5;

    public function setUp(): void
    {
        parent::setUp();
        $this->content = db_save('content', $this->save);
        $this->content5 = db_save('content', $this->save_post);
    }

    public function testSaveIsShop()
    {

        $id = db_save('content', array(
            "content_type" => "page",
            "subtype" => "dynamic",
            "url" => "home",
            "title" => "Home",
            "is_home" => 1,
            "is_pinged" => 0,
            "is_shop" => 1
        ));

        $saved = db_get('content',['id'=>$id]);

        $this->assertEquals($saved[0]['is_shop'], '1');
    }

    public function testSimpleSave()
    {
        $save = $this->save;
        $save_post = $this->save_post;
        $content = $this->content;
        $content5 = $this->content5;

        //$content = db_save('content', $save);
        $content2 = db_save('content', $save);
        $content3 = db_save('content', $save);
        $content4 = db_save('content', $save);
        //$content5 = db_save('content', $save_post);
        $content6 = db_save('content', $save_post);


        $this->assertTrue($content != 0);
        $this->assertTrue($content2 != 0);
        $this->assertTrue($content != $content2);
        $this->assertTrue($content2 != $content3);
        $this->assertTrue($content3 != $content4);
        $this->assertTrue($content4 != $content5);
        $this->assertTrue($content5 != $content6);
    }

    public function testSimpleGet()
    {
        $content = db_get('content', 'limit=2');
        $this->assertTrue(!empty($content));
        $this->assertEquals(2, count($content));
    }

    public function testSimpleCount()
    {
        $content_count = db_get('content', 'count=true');
        $this->assertTrue($content_count > 0);
        $this->assertTrue(is_int($content_count));
    }

    public function testPageCount()
    {
        $content_count = db_get('content', 'count=true');
        $pages_count = db_get('content', 'limit=2&count_paging=1');

        $must_be = intval(ceil($content_count / 2));
        $this->assertEquals($pages_count, $must_be);
    }

    public function testOrderBy()
    {
        $content = db_get('content', 'limit=1&single=1&order_by=id desc');
        $content2 = db_get('content', 'limit=1&single=1&order_by=id asc');

        $this->assertTrue(isset($content['id']));
        $this->assertTrue(isset($content2['id']));
        $this->assertNotEquals($content['id'], $content2['id']);
        $this->assertTrue(($content['id'] > $content2['id']));
    }

    public function testLimitAndPaging()
    {
        $add_page = db_save('content', $this->save);
        $add_page = db_save('content', $this->save);
        $add_page = db_save('content', $this->save);
        $add_page = db_save('content', $this->save);

        $pages_count = db_get('content', 'limit=2&count_paging=1');

        $first_page = db_get('content', 'limit=2');
        $second_page = db_get('content', 'limit=2&current_page=2');

        $first_page_items = count($first_page);
        $second_page_items = count($second_page);

        $ids_on_first_page = array();
        foreach ($first_page as $item) {
            $this->assertTrue(isset($item['id']));
            $ids_on_first_page[] = $item['id'];
        }

        foreach ($second_page as $item) {
            $this->assertTrue(isset($item['id']));
            $this->assertFalse(in_array($item['id'], $ids_on_first_page));
        }


        $this->assertEquals($first_page_items, $second_page_items, 'First page item count: ' . $first_page_items . ', second page item count: ' . $second_page_items);
        $this->assertTrue(intval($pages_count) > 1);
        // @todo: fix  the count_paging param to return integer    $this->assertTrue(is_int($pages_count));
    }

    public function testIncludeExcludeIds()
    {
        $content = db_get('content', 'limit=10');
        $this->assertTrue(is_array($content));
        $some_ids = array();
        foreach ($content as $item) {
            $some_ids[] = $item['id'];
        }
        $half = round(count($some_ids) / 2);
        shuffle($some_ids);
        $some_ids = array_slice($some_ids, $half);

        $includeString = 'ids=' . implode(',', $some_ids);

        $content_ids = db_get('content', $includeString);
        foreach ($content_ids as $item) {
            $this->assertTrue(in_array($item['id'], $some_ids));
        }
        $this->assertTrue(is_array($content_ids));

        $excludeString = 'exclude_ids=' . implode(',', $some_ids);
        $content_ids = db_get('content', $excludeString);
        foreach ($content_ids as $item) {
            $this->assertTrue(!in_array($item['id'], $some_ids));
        }
        $this->assertTrue(is_array($content_ids));
    }

    public function testMinMaxAvg()
    {
        $content = db_get('content', 'content_type=page&min=id');
        $content_max = db_get('content', 'content_type=page&max=id');
        $content_avg = db_get('content', 'content_type=page&avg=id');

        $this->assertTrue(is_numeric($content));
        $content = intval($content);
        $this->assertTrue(is_numeric($content_max));
        $content_max = intval($content_max);
        $this->assertTrue(is_numeric($content_avg));
        $content_avg = intval($content_avg);
        $this->assertTrue(($content < $content_max), "Content: " . $content . ", Content_max: " . $content_max);
        $this->assertTrue(($content_avg < $content_max), "Content_avg: " . $content_avg . ", Content_max: " . $content_max);
        $this->assertTrue(($content <= $content_avg), "Content: " . $content . ", Content_avg: " . $content_avg);
    }

    public function testShorthandFilters()
    {
        $content = db_get('content', 'limit=1&content_type=[eq]page');
        foreach ($content as $item) {
            $this->assertTrue(($item['content_type'] == 'page'));
        }
        $content = db_get('content', 'limit=1&content_type=[neq]page');

        foreach ($content as $item) {
            $this->assertTrue(($item['content_type'] != 'page'));
        }
        $content = db_get('content', 'limit=1&content_type=[like]post');
        foreach ($content as $item) {
            $this->assertTrue(($item['content_type'] == 'post'));
        }
        $content = db_get('content', 'limit=1&content_type=[not_like]post');
        foreach ($content as $item) {
            $this->assertTrue(($item['content_type'] != 'post'));
        }
    }

    public function testSelectOnlyFields()
    {
        $content = db_get('content', 'limit=2&fields=id,position&order_by=id desc');

        foreach ($content as $item) {
            $this->assertTrue(count($item) == 2);
            $this->assertTrue(isset($item['id']));
            $this->assertFalse(isset($item['position']));
        }
    }
}
