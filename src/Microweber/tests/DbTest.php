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


    public function testSimpeCount()
    {
        $content_count = get('content', 'count=true');
        $this->assertTrue(true, $content_count > 0);
        $this->assertTrue(true, is_int($content_count));
  

    }


}