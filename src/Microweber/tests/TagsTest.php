<?php

namespace Microweber\tests;

use Tag;
use Content;

class TagsTest extends TestCase
{
    /**
     * @group tags
     * Tests the tags
     */
    public function testPosts()
    {

        $id = 1;
        $tag = new Tag(array('name' => 'wamp'));
        $blogpost = Content::find(1)->tags()->save($tag);

        $tags = array('lamp', 'wamp', 'xampp', 'mamp');
        $posts = Content::whereHas('tags', function ($q) use ($tags) {
            $q->whereIn('name', $tags);
        })->get()->toArray();


        //PHPUnit
        $this->assertEquals($id, $posts[0]['id']);
    }

}
