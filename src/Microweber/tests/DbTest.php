<?php

class DbTest extends TestCase
{


    public function testSimpeGet()
    {
        $content = get('content', 'limit=2');
        $count = (count($content));
        $this->assertEquals(2, $count);
        $this->assertTrue(true, !empty($content));
    }


    public function testSimpleCount()
    {
        $content_count = get('content', 'count=true');
        $this->assertTrue(true, $content_count > 0);
        $this->assertTrue(true, is_int($content_count));
    }


    public function testPageCount()
    {
        $content_count = get('content', 'count=true');
        $pages_count = get('content', 'limit=2&count_paging=1');
        $must_be = intval(ceil($content_count / 2));
        $this->assertEquals($pages_count, $must_be);
    }


    public function testOrderBy()
    {
        $content = get('content', 'limit=1&single=1&order_by=id desc');
        $content2 = get('content', 'limit=1&single=1&order_by=id asc');
        $this->assertTrue(true, isset($content['id']));
        $this->assertTrue(true, isset($content2['id']));
        $this->assertNotEquals($content['id'], $content2['id']);
        $this->assertTrue(true, ($content['id'] > $content2['id']));
    }

    public function testLimitAndPaging()
    {

        $pages_count = get('content', 'limit=2&count_paging=1');

        $first_page = get('content', 'limit=2');
        $second_page = get('content', 'limit=2&current_page=2');

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
        $content = get('content', 'limit=10');

        $some_ids = array();
        foreach ($content as $item) {
            $some_ids[] = $item['id'];
        }
        $half = round(count($some_ids) / 2);
        shuffle($some_ids);
        $some_ids = array_slice($some_ids, $half);

        $content_ids = get('content', 'ids=' . implode(',', $some_ids));
        foreach ($content as $item) {
            $this->assertTrue(true, in_array($item['id'], $some_ids));
        }

        $content_ids = get('content', 'exclude_ids=' . implode(',', $some_ids));
        foreach ($content as $item) {
            $this->assertTrue(true, !in_array($item['id'], $some_ids));
        }


    }

    public function testMinMaxAvg()
    {
        $content = get('content', 'content_type=page&min=id');
        $content_max = get('content', 'content_type=page&max=id');
        $content_avg = get('content', 'content_type=page&avg=id');

        $this->assertTrue(true, is_int($content));
        $this->assertTrue(true, is_int($content_max));
        $this->assertTrue(true, is_int($content_avg));
        $this->assertTrue(true, ($content < $content_max));
        $this->assertTrue(true, ($content_avg < $content_max));
        $this->assertTrue(true, ($content < $content_avg));
    }
}