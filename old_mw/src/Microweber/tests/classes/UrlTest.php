<?php

namespace ClassTest;


class UrlTest extends \PHPUnit_Framework_TestCase
{

    public function testCurrentUrl()
    {
        $site_current_url = 'http://example.com/blog/post-title';
        mw('url')->set_current($site_current_url);
        $current_url = mw('url')->current();

        //PHPUnit
        $this->assertEquals($site_current_url, $current_url);
    }

    public function testSlug()
    {
        $title = "    My long title     ";
        $slug_must_be = "my-long-title";
        $slug = mw('url')->slug($title);

        //PHPUnit
        $this->assertEquals($slug, $slug_must_be);
    }

    public function testUrlSegments()
    {

        $site_url_var = 'http://example.com/';
        $current_url_var = 'http://example.com/testing/deep/path';

        mw('url')->set($site_url_var);

        //getting all segments
        $url_segments = mw('url')->segments($current_url_var);

        //getting single segment
        $single_segment = mw('url')->segment(2, $current_url_var);

        //PHPUnit
        $this->assertEquals(true, $url_segments[0] == 'testing');
        $this->assertEquals(true, $url_segments[1] == 'deep');
        $this->assertEquals(true, $url_segments[2] == 'path');
        $this->assertEquals(true, $single_segment == 'path');
    }

    public function testUrlGet()
    {

        $site_url_var = 'http://example.com/testing/path';

        mw()->url->set($site_url_var);
        $url = mw()->url->site();

        //PHPUnit
        $this->assertEquals($site_url_var, $url);


    }


}