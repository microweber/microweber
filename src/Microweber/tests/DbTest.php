<?php

namespace Microweber\tests;

class DbTest extends TestCase
{
    public function testSimpeSave()
    {
        $save = array(
            'content_type' => 'page',
            'subtype' => 'static',
            'title' => 'one page',
            'parent' => '0',
            'is_deleted' => '0',
        );
        $save_post = array(
            'content_type' => 'post',
            'subtype' => 'static',
            'title' => 'one post',
            'parent' => '0',
            'is_deleted' => '0',
        );

        $content = db_save('content', $save);
        $content2 = db_save('content', $save);
        $content3 = db_save('content', $save);
        $content4 = db_save('content', $save);
        $content5 = db_save('content', $save_post);
        $content6 = db_save('content', $save_post);

        $this->assertTrue(true, !$content);
        $this->assertTrue(true, !$content2);
        $this->assertTrue(true, $content != $content2);
        $this->assertTrue(true, $content2 != $content3);
        $this->assertTrue(true, $content3 != $content4);
        $this->assertTrue(true, $content4 != $content5);
        $this->assertTrue(true, $content5 != $content6);
    }

    public function testSimpeGet()
    {
        $content = db_get('content', 'limit=2');
        $count = (count($content));
        $this->assertEquals(2, $count);
        $this->assertTrue(true, !empty($content));
    }

    public function testSimpleCount()
    {
        $content_count = db_get('content', 'count=true');
        $this->assertTrue(true, $content_count > 0);
        $this->assertTrue(true, is_int($content_count));
    }

    public function testPageCount()
    {
        $content_count = db_get('content', 'count=true');
        $pages_count = db_get('content', 'limit=2&count_paging=1');

        $must_be = intval(floor($content_count / 2));
        $this->assertEquals($pages_count, $must_be);
    }

    public function testOrderBy()
    {
        $content = db_get('content', 'limit=1&single=1&order_by=id desc');
        $content2 = db_get('content', 'limit=1&single=1&order_by=id asc');

        $this->assertTrue(true, isset($content['id']));
        $this->assertTrue(true, isset($content2['id']));
        $this->assertNotEquals($content['id'], $content2['id']);
        $this->assertTrue(true, ($content['id'] > $content2['id']));
    }

    public function testLimitAndPaging()
    {
        $pages_count = db_get('content', 'limit=2&count_paging=1');

        $first_page = db_get('content', 'limit=2');
        $second_page = db_get('content', 'limit=2&current_page=2');

        $first_page_items = count($first_page);
        $second_page_items = count($second_page);

        $ids_on_first_page = array();
        foreach ($first_page as $item) {
            $this->assertTrue(true, isset($item['id']));
            $ids_on_first_page[] = $item['id'];
        }

        foreach ($second_page as $item) {
            $this->assertTrue(true, isset($item['id']));
            $this->assertTrue(true, in_array($item['id'], $ids_on_first_page));
        }

        $this->assertEquals($first_page_items, $second_page_items);
        $this->assertTrue(true, ($pages_count > 1));
        $this->assertTrue(true, is_int($pages_count));
    }

    public function testIncludeExcludeIds()
    {
        $content = db_get('content', 'limit=10');
        $this->assertTrue(true, is_array($content));
        $some_ids = array();
        foreach ($content as $item) {
            $some_ids[] = $item['id'];
        }
        $half = round(count($some_ids) / 2);
        shuffle($some_ids);
        $some_ids = array_slice($some_ids, $half);

        $content_ids = db_get('content', 'ids='.implode(',', $some_ids));
        foreach ($content as $item) {
            $this->assertTrue(true, in_array($item['id'], $some_ids));
        }
        $this->assertTrue(true, is_array($content_ids));

        $content_ids = db_get('content', 'exclude_ids='.implode(',', $some_ids));
        foreach ($content as $item) {
            $this->assertTrue(true, !in_array($item['id'], $some_ids));
        }
        $this->assertTrue(true, is_array($content_ids));
    }

    public function testMinMaxAvg()
    {
        $content = db_get('content', 'content_type=page&min=id');
        $content_max = db_get('content', 'content_type=page&max=id');
        $content_avg = db_get('content', 'content_type=page&avg=id');

        $this->assertTrue(true, is_int($content));
        $this->assertTrue(true, is_int($content_max));
        $this->assertTrue(true, is_int($content_avg));
        $this->assertTrue(true, ($content < $content_max));
        $this->assertTrue(true, ($content_avg < $content_max));
        $this->assertTrue(true, ($content < $content_avg));
    }

    public function testShorthandFilters()
    {
        $content = db_get('content', 'limit=1&content_type=[eq]page');
        foreach ($content as $item) {
            $this->assertTrue(true, ($item['content_type'] == 'page'));
        }
        $content = db_get('content', 'limit=1&content_type=[neq]page');

        foreach ($content as $item) {
            $this->assertTrue(true, ($item['content_type'] != 'page'));
        }
        $content = db_get('content', 'limit=1&content_type=[like]post');
        foreach ($content as $item) {
            $this->assertTrue(true, ($item['content_type'] == 'post'));
        }
        $content = db_get('content', 'limit=1&content_type=[not_like]post');
        foreach ($content as $item) {
            $this->assertTrue(true, ($item['content_type'] != 'post'));
        }
    }

    public function testSelectOnlyfields()
    {
        $content = db_get('content', 'limit=2&fields=id,position&order_by=id desc');
        foreach ($content as $item) {
            $this->assertTrue(true, count($item) == 2);
            $this->assertTrue(true, isset($item['id']));
            $this->assertTrue(true, isset($item['position']));
        }
    }
}
