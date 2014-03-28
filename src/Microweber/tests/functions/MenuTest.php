<?php

namespace FunctionsTest;


//class MenuTest extends \PHPUnit_Framework_TestCase

class NONWORKING  
{

    function __construct()
    {



        //  cache_clear('db');
        // mw('content')->db_init();
    }

    public function testMenu()
    {
        $params = array();
        $params['ul_class'] = 'nav-small';
        $params['maxdepth'] = 1;
        $menu = menu_tree($params);
        $find_string = stripos($menu, 'nav-small');

        //PHPUnit
        $this->assertEquals(true, $find_string > 0);
    }



    public function testMenuWithId()
    {
        $params = array();
        $params['menu_id'] = 1;
        $menu = menu_tree($params);
        $find_string = stripos($menu, 'menu_1');

        //PHPUnit
        $this->assertEquals(true, $find_string > 0);
    }

    public function testMenuClasses()
    {
        $params = array();
        $params['menu_id'] = 1;
        $params['ul_class'] = 'nav-holder';
        $params['li_class'] = 'nav-item';
        $menu = menu_tree($params);


        //PHPUnit
        $find_string = stripos($menu, 'nav-holder');
        $this->assertEquals(true, $find_string > 0);

        $find_string = stripos($menu, 'nav-item');
        $this->assertEquals(true, $find_string > 0);
    }


    public function testMenuTags()
    {
        $params = array();
        $params['menu_id'] = 1;
        $params['ul_tag'] = 'div';
        $params['li_tag'] = 'span';
        $menu = menu_tree($params);

        //PHPUnit
        $find_string = stripos($menu, 'div');
        $this->assertEquals(true, $find_string > 0);

        $find_string = stripos($menu, 'span');
        $this->assertEquals(true, $find_string > 0);
    }


}