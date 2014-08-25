<?php

namespace FunctionsTest;


class MenusTest extends \PHPUnit_Framework_TestCase
{
    private $cleanup_content = array();

    public function testMenuAdd()
    {

        $my_menu_name = 'my test menu';
        $params = array(
            'title' => $my_menu_name,
            'make_on_not_found' => true
        );
        $my_menu = get_menu($params);

        $params = array(
            'title' => 'My menu page',
            'content_type' => 'page',
            'is_active' => 'y');
        //saving
        $parent_page = save_content($params);

        $pages = get_pages($params);

        $add = add_content_to_menu($parent_page, $my_menu['id']);


        $params = array();
        $params['menu_id'] = $my_menu['id'];
        $params['ul_class'] = 'nav-small';
        $params['maxdepth'] = 1;
        $menu = menu_tree($params);
        $find_string = stripos($menu, 'nav-small');

        //PHPUnit
        $this->assertEquals(true, is_array($my_menu));
        $this->assertEquals(true, is_array($pages));
        $this->assertEquals(true, $find_string > 0);
        $this->cleanup_content[] = $parent_page;
    }

    public function testMenu()
    {
        $my_menu_name = 'my test menu';
        $params = array(
            'title' => $my_menu_name,
            'make_on_not_found' => true
        );
        $my_menu = get_menu($params);
        $pages = get_pages();
        foreach($pages as $page){
        $add = add_content_to_menu($page['id'], $my_menu['id']);
        }


        $params = array();
        $params['menu_id'] = $my_menu['id'];


        $params['ul_class'] = 'nav-small';
        $params['maxdepth'] = 1;
        $menu = menu_tree($params);
        $find_string = stripos($menu, 'nav-small');

        //PHPUnit
        $this->assertEquals(true, $find_string > 0);
    }


    public function testMenuClasses()
    {
        $my_menu_name = 'my test menu';
        $params = array(
            'title' => $my_menu_name,
            'make_on_not_found' => true
        );
        $my_menu = get_menu($params);
        $pages = get_pages();
        foreach($pages as $page){
            $add = add_content_to_menu($page['id'], $my_menu['id']);
        }


        $params = array();
        $params['menu_id'] = $my_menu['id'];
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

        $my_menu_name = 'my test menu';
        $params = array(
            'title' => $my_menu_name,
            'make_on_not_found' => true
        );
        $my_menu = get_menu($params);
        $pages = get_pages();
        foreach($pages as $page){
            $add = add_content_to_menu($page['id'], $my_menu['id']);
        }


        $params = array();
        $params['menu_id'] = $my_menu['id'];

        $params['ul_tag'] = 'div';
        $params['li_tag'] = 'span';
        $menu = menu_tree($params);

        //PHPUnit
        $find_string = stripos($menu, 'div');
        $this->assertEquals(true, $find_string > 0);

        $find_string = stripos($menu, 'span');
        $this->assertEquals(true, $find_string > 0);
    }

    public function testRemoveSampleContent()
    {
        foreach ($this->cleanup_content as $page) {
            $delete_page = delete_content($page['id']);
            $deleted_page = get_content_by_id($page['id']);

            $this->assertEquals(false, $deleted_page);
            $this->assertEquals(true, is_array($delete_page));
        }
    }
}