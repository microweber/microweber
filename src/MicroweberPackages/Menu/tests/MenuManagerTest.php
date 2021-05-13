<?php


namespace MicroweberPackages\Menu\tests;

use MicroweberPackages\Core\tests\TestCase;

class MenuManagerTest extends TestCase
{
    public function testMenuManager()
    {

        $menu_title = 'menu test ' . uniqid();
        $page_title = 'Page to add to menu ' . uniqid();

        $create_menu = app()->menu_manager->menu_create('title=' . $menu_title);
        $this->assertGreaterThan(0, $create_menu);


        $menu = get_menus('single=1&title=' . $menu_title);

        $this->assertEquals($menu['title'], $menu_title);
        $this->assertEquals($menu['item_type'], 'menu');


        $params = array(
            'title' => $page_title,
            'content_type' => 'page',
            'is_active' => 1,);


        $save_page_id = save_content($params);
        $this->assertGreaterThan(0, $save_page_id);


        $params = array(
            'menu_id' => $menu['id'],
            'content_id' => $save_page_id,
         );

        //add page to menu
        $menu_item_add_page = app()->menu_manager->menu_item_save($params);
        $this->assertGreaterThan(0, $menu_item_add_page);


        $params = array(
            'menu_id' => $menu['id'],
        );

        $menu_tree = menu_tree($params);
        $this->assertTrue(str_contains($menu_tree, $page_title));

        $params = array(
            'menu_id' => $menu['id'],
            'return_data' => true,
        );
        $menu_tree_array = menu_tree($params);
        $this->assertTrue(is_array($menu_tree_array));
        $this->assertEquals($menu_tree_array[0]['title'], $page_title);
        $this->assertEquals($menu_tree_array[0]['content_id'], $save_page_id);
        $this->assertEquals($menu_tree_array[0]['item_type'], 'menu_item');


    }


}