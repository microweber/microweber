<?php

namespace MicroweberPackages\Database\tests;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use Modules\Category\Models\Category;
use Modules\Content\Models\Content;
use Modules\CustomFields\Models\CustomFieldValue;

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
        Content::truncate();
        Category::truncate();
        $this->content = db_save('content', $this->save);
        $this->content5 = db_save('content', $this->save_post);
    }

    #[Test]

    public function it_db_save_json_field_custom_field_value(): void {

        $customFieldValueId = db_save('custom_fields_values', [
            'rel_type' => morph_name(\Modules\Content\Models\Content::class),
            'rel_id' => 1,
            'custom_field_id' => 1,
            'value' => json_encode(['test' => 'test'])
        ]);

        $findCustomFieldValue = CustomFieldValue::where('id', $customFieldValueId)->first();

        $this->assertEquals($findCustomFieldValue->value, json_encode(['test' => 'test']));
    }

    #[Test]

    public function it_db_save_json_field_option(): void {
        $option = [
            'option_group' => 'test',
            'option_key' => 'test',
            'option_value' => json_encode(['test' => 'test'])
        ];

        $optionId = db_save('options', $option);
        $findOption = \MicroweberPackages\Option\Models\Option::where('id', $optionId)->first();
        $this->assertEquals($findOption->option_value, json_encode(['test' => 'test']));
    }

    #[Test]

    public function it_save_is_shop(): void {

        $id = db_save('content', array(
            "content_type" => "page",
            "subtype" => "dynamic",
            "url" => "home",
            "title" => "Home",
            "is_home" => 1,
            "is_pinged" => 0,
            "is_shop" => 1
        ));

        $saved = db_get('content', ['id' => $id]);

        $this->assertEquals($saved[0]['is_shop'], '1');
    }

    #[Test]

    public function it_simple_save(): void {
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

    #[Test]

    public function it_simple_get(): void {
        $content = db_get('content', 'limit=2');
        $this->assertTrue(!empty($content));
        $this->assertEquals(2, count($content));
    }

    #[Test]

    public function it_simple_count(): void {
        $content_count = db_get('content', 'count=true');
        $this->assertTrue($content_count > 0);
        $this->assertTrue(is_int($content_count));
    }

    #[Test]

    public function it_page_count(): void {
        $content_count = db_get('content', 'count=true');
        $pages_count = db_get('content', 'limit=2&count_paging=1');

        $must_be = intval(ceil($content_count / 2));
        $this->assertEquals($pages_count, $must_be);
    }

    #[Test]

    public function it_order_by(): void {
        $content = db_get('content', 'limit=1&single=1&order_by=id desc');
        $content2 = db_get('content', 'limit=1&single=1&order_by=id asc');

        $this->assertTrue(isset($content['id']));
        $this->assertTrue(isset($content2['id']));
        $this->assertNotEquals($content['id'], $content2['id']);
        $this->assertTrue(($content['id'] > $content2['id']));
    }

    #[Test]

    public function it_limit_and_paging(): void {
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

    #[Test]

    public function it_include_exclude_ids(): void {
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

    #[Test]

    public function it_min_max_avg(): void {
        $content = db_get('content', 'content_type=page&min=id');
        $content_max = db_get('content', 'content_type=page&max=id');
        $content_avg = db_get('content', 'content_type=page&avg=id');

        $this->assertTrue(is_numeric($content));
        $content = intval($content);
        $this->assertTrue(is_numeric($content_max));
        $content_max = intval($content_max);
        $this->assertTrue(is_numeric($content_avg));
        $content_avg = intval($content_avg);
        $this->assertTrue(($content <= $content_max), "Content: " . $content . ", Content_max: " . $content_max);
        $this->assertTrue(($content_avg <= $content_max), "Content_avg: " . $content_avg . ", Content_max: " . $content_max);
        $this->assertTrue(($content <= $content_avg), "Content: " . $content . ", Content_avg: " . $content_avg);
    }

    #[Test]

    public function it_shorthand_filters(): void {
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

    #[Test]

    public function it_select_only_fields(): void {
        $content = db_get('content', 'limit=2&fields=id,position&order_by=id desc');

        foreach ($content as $item) {
            $this->assertTrue(count($item) == 2);
            $this->assertTrue(isset($item['id']));
            $this->assertTrue(isset($item['position']));
        }
    }

    #[Test]

    public function it_get_fields(): void {
        $table = 'content';
        $tableFields = app()->database_manager->get_fields($table, false, true);

        $this->assertIsArray($tableFields);

        $this->assertIsArray($tableFields[0]);
        $this->assertArrayHasKey('name', $tableFields[0]);
        $this->assertEquals('id', $tableFields[0]['name']);
    }
}
