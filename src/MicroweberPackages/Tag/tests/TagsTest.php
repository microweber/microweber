<?php

namespace MicroweberPackages\Tag\tests;

use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Core\tests\TestCase;
use Tag;
use DB;

class TagsTest extends TestCase
{
    /**
     * @group tags
     * Tests the tags
     */
    public function testModel(){
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
        $c = get_content_by_id($saved_id);

        DB::enableQueryLog();

        /*

        The  "find" method maybe have a bug where the whole table is selected,
        it executes 2 queries instead of 1 and it returns the whole table as result

        $article = Content::find($saved_id)->first(); - makes 2 queries?

        $article = Content::whereId($saved_id)->first(); - OK

        the log shows

        `
        0 => array:3 [
            "query" => "select * from "content" where "content"."id" = ? limit 1"
            "bindings" => array:1 [
              0 => 44
            ]
            "time" => 0.0
          ]
          1 => array:3 [
            "query" => "select * from "content" limit 1"
            "bindings" => []
            "time" => 0.0
          ]
        ]
        `

        the bug is that
        instead of returning the selected id, we get the whole table

        we will use "whereId" instead of "find"


        */


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
        if (in_array('Beer',$existing)) {
            $check = true;
        }
        $this->assertTrue($check);


        $new_page = array();
        $new_page['title'] = 'Drinks with tags'.rand();
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
//
//
//        $article = Content::withAnyTag(['Cola'])->first();
//        dd($article);
//
//        $article = Content::withAnyTag('Gardening, Cooking')->get(); // fetch articles with any tag listed
//        //d($article);
//        $article = Content::withAnyTag(['Gardening', 'Cooking'])->get(); // different syntax, same result as above
//        // d($article);
//        $article = Content::withAnyTag('Gardening', 'Cooking')->get(); // different syntax, same result as above
//        // d($article);
//
//        $article = Content::withAllTags('Gardening, Cooking')->get(); // only fetch articles with all the tags
//        d($article);
//        //  Content::withAllTags(['Gardening', 'Cooking'])->get();
//        //  Content::withAllTags('Gardening', 'Cooking')->get();
//
//
//        $article = Content::existingTags(); // return collection of all existing tags on any articles


    }





}
