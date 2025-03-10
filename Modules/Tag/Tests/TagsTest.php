<?php

namespace Modules\Tag\Tests;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\Core\tests\TestCase;
use Modules\Content\Models\Content;

class TagsTest extends TestCase
{

    use RefreshDatabase;

    public function testModel()
    {

        //legacy save
        mw()->database_manager->extended_save_set_permission(true);
        $has_permission = mw()->database_manager->extended_save_has_permission();
        $this->assertTrue($has_permission);


        $new_page = array();
        $new_page['title'] = 'Test the tags';
        $new_page['content_type'] = 'page';
        $new_page['url'] = 'test-the-tags';
        $new_page['auto_discover_id'] = true;

        $saved_id = save_content($new_page);
        $saved_id = intval($saved_id);


        //  $c = get_content_by_id($saved_id);
        DB::enableQueryLog();
        DB::flushQueryLog();


        $article = Content::whereId($saved_id)->first();
        DB::disableQueryLog();

        $this->assertEquals(1, count(DB::getQueryLog()));

        $new_page = array();
        $new_page['title'] = 'Test the tags';
        $new_page['content_type'] = 'page';
        $new_page['url'] = 'test-the-tags';
        $new_page['auto_discover_id'] = true;

        $saved_id = save_content($new_page);

        $saved_id = intval($saved_id);
        $c = get_content_by_id($saved_id);

        $article = Content::whereId($saved_id)->first();


        $article->retag('Gardening');
        $article->save();

        $tags = $article->tagNames();

        $this->assertSame(['Gardening'], $tags);


        $article = Content::whereId($saved_id)->first();
        $article->tag('Cooking');
        $article->tag('Pepsi');
        $article->tag('Cola');
        $article->tag('Pizza');
        $article->save();


    }

    public function testPosts()
    {
        mw()->database_manager->extended_save_set_permission(true);
        $has_permission = mw()->database_manager->extended_save_has_permission();
        $this->assertTrue($has_permission);
        $new_page = array();
        $new_page['title'] = 'Beer';
        $new_page['content_type'] = 'page';
        $new_page['tag_names'] = 'beer';
        $new_page['auto_discover_id'] = true;

        $saved_id = save_content($new_page);

        $content = get_content('tag_names=beer&single=true');
        $check = false;
        if ($content['id'] == $saved_id) {
            $check = true;
        }
        $this->assertTrue($check);
        $new_page = array();
        $new_page['title'] = 'Orange';
        $new_page['content_type'] = 'page';
        $new_page['tag_names'] = 'apple,orange';
        $new_page['auto_discover_id'] = true;

        $saved_id = save_content($new_page);

        $content = get_content('all_tags=apple&single=true');
        $check = false;
        if ($content['id'] == $saved_id) {
            $check = true;
        }
        $this->assertTrue($check);
        $new_page = array();
        $new_page['title'] = 'Orange';
        $new_page['content_type'] = 'page';
        $new_page['tag_names'] = 'apple,orange';
        $new_page['auto_discover_id'] = true;

        $saved_id = save_content($new_page);

        $content = get_content('all_tags=apple,beer,orange&single=true');
        $check = false;
        if (!$content) {
            $check = true;
        }
        $this->assertTrue($check);


        $existing = content_tags();
        $check = false;
        if (in_array('beer', $existing)) {
            $check = true;
        }
        $this->assertTrue($check);


        $new_page = array();
        $new_page['title'] = 'Drinks with tags' . rand();
        $new_page['content_type'] = 'product';

        $saved_id = save_content($new_page);


        $article = Content::whereId($saved_id)->first();
        $article->tag('Pepsi');
        $article->tag('Coffee');
        $article->tag('Water');
        $article->save();


        $article = Content::withAnyTag(['Water', 'Coffee'])->get();


        foreach ($article as $item) {
            $check = in_array("Pepsi", $item->tagNames());
            $this->assertTrue($check);
        }

        $article = Content::withAnyTag(['ColaZero'])->first();
        $this->assertNull($article);


        $article = Content::withAnyTag(['Gardening Potatoes', 'Cooking Potatoes'])->get(); // fetch articles with any tag listed
        $this->assertEmpty($article);


        $article = Content::existingTags(); // return collection of all existing tags on any articles
        $this->assertNotNull($article);


    }


    public function testTagContentModel()
    {
        $unique = 'tag-' . uniqid();

        $content = new Content();
        $content->title = 'Test the tags';
        $content->content_type = 'page';
        $content->url = 'test-the-tags';

        $content->save();
        $content->tag($unique);
        $content = Content::where('title', 'Test the tags')->first();
        $tags = $content->tagNames();


        $this->assertIsArray($tags);
        $this->assertTrue(in_array($unique, $tags));

    }

    public function testTagContentModelWithArray()
    {
        $unique = 'tag-' . uniqid();

        $content = new Content();
        $content->title = 'Test the tags';
        $content->content_type = 'page';
        $content->url = 'test-the-tags';

        $content->save();
        $content->tag([$unique, 'tag2']);
        $content = Content::where('title', 'Test the tags')->first();
        $tags = $content->tagNames();

        $this->assertIsArray($tags);
        $this->assertTrue(in_array($unique, $tags));
        $this->assertTrue(in_array('tag2', $tags));
    }
}
