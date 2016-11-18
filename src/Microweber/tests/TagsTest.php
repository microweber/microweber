<?php

namespace Microweber\tests;

use Tag;
use Content;
use DB;

class TagsTest extends TestCase
{
    /**
     * @group tags
     * Tests the tags
     */
    public function testPosts()
    {

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

return;
        $article = Content::whereId($saved_id)->first();
        $article->tag('Cooking');
        $article->tag('Pepsi');
        $article->tag('Cola');
        $article->save();


        $article = Content::withAnyTag(['Gardening', 'Cooking'])->get(); // different syntax, same result as above
        foreach ($article as $item) {
            $check = in_array("Pepsi", $item->tagNames());
            $this->assertTrue($check);
        }

return;
        $article = Content::withAnyTag(['Cola'])->first(); // fetch articles with any tag listed
        dd($article);

        $article = Content::withAnyTag('Gardening, Cooking')->get(); // fetch articles with any tag listed
        //d($article);
        $article = Content::withAnyTag(['Gardening', 'Cooking'])->get(); // different syntax, same result as above
        // d($article);
        $article = Content::withAnyTag('Gardening', 'Cooking')->get(); // different syntax, same result as above
        // d($article);

        $article = Content::withAllTags('Gardening, Cooking')->get(); // only fetch articles with all the tags
        d($article);
        //  Content::withAllTags(['Gardening', 'Cooking'])->get();
        //  Content::withAllTags('Gardening', 'Cooking')->get();


        $article = Content::existingTags(); // return collection of all existing tags on any articles

        dd($article);
    }

}
