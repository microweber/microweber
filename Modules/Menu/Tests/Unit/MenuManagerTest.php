<?php


namespace Modules\Menu\Tests\Unit;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Core\tests\TestCase;
use Modules\Content\Models\Content;
use Modules\Menu\Models\Menu;
use Modules\Menu\Models\MenuItem;

class MenuManagerTest extends TestCase
{
    protected $menu_title;
    protected $page_title;
    protected $menu_id;
    protected $page_id;

    public function setUp(): void
    {
        parent::setUp();

        // Generate unique titles for menu and page
        $this->menu_title = 'menu test ' . uniqid();
        $this->page_title = 'Page to add to menu ' . uniqid();

        // Create a menu
        $this->menu_id = app()->menu_manager->menu_create('title=' . $this->menu_title);
        $this->assertGreaterThan(0, $this->menu_id);

        // Create a page
        $params = [
            'title' => $this->page_title,
            'content_type' => 'page',
            'is_active' => 1,
            'url' => 'test-page-' . uniqid(),
        ];
        $this->page_id = save_content($params);
        $this->assertGreaterThan(0, $this->page_id);


        $menu = get_menus('single=1&title=' . $this->menu_title);

        $this->assertEquals($menu['title'], $this->menu_title);
        $this->assertEquals($menu['item_type'], 'menu');

        $this->assertGreaterThan(0, $this->page_id);


        $params = array(
            'menu_id' => $menu['id'],
            'content_id' => $this->page_id,
        );

        //add page to menu
        $menu_item_add_page = app()->menu_manager->menu_item_save($params);
        $this->assertGreaterThan(0, $menu_item_add_page);


    }


    public function testMenuManager()
    {

        $menu = get_menus('single=1&title=' . $this->menu_title);
        $page_title = $this->page_title;
        $save_page_id = $this->page_id;


        $params = array(
            'menu_id' => $menu['id'],
        );

        $testIsInMenu = is_in_menu($menu['id'], $save_page_id);
        $this->assertTrue($testIsInMenu);

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


    public function testMenuManagerSubpage()
    {
        // Create a subpage
        $params = [
            'title' => $this->page_title . ' subpage',
            'content_type' => 'page',
            'is_active' => 1,
            'parent' => $this->page_id,
        ];
        $subpage_id = save_content($params);

        $menu = get_menus('single=1&title=' . $this->menu_title);

        //add sub page to menu
        $params = array(
            'menu_id' => $menu['id'],
            'content_id' => $subpage_id,
        );
        //add page to menu
        $menu_item_add_page = app()->menu_manager->menu_item_save($params);
        $this->assertGreaterThan(0, $menu_item_add_page);

        $testIsInMenu = is_in_menu($menu['id'], $subpage_id);
        $this->assertTrue($testIsInMenu);


        //move to submenu of parent page
        $menuItemOfParentPage = app()->menu_manager->get_menu_items('single=1&content_id=' . $this->page_id);
        $menuItemOfSubpage = app()->menu_manager->get_menu_items('single=1&content_id=' . $subpage_id);

        $params = array(
            'id' => $menuItemOfSubpage['id'],
            'parent_id' => $menuItemOfParentPage['id'],
            'url' => site_url() . 'this-is-the-url/custom-url'
        );
        $menu_item_add_page = app()->menu_manager->menu_item_save($params);


        $findSavedMenu = DB::table('menus')->where('id', $menu_item_add_page)->first();
        $this->assertEquals($findSavedMenu->url, '{SITE_URL}this-is-the-url/custom-url');


        $testIsInMenu = is_in_menu($menu['id'], $subpage_id);
        $this->assertTrue($testIsInMenu);

        $testIsInMenuOtherPage = is_in_menu($menu['id'], 0);
        $testIsInMenuOtherPage2 = is_in_menu(0, 0);
        $this->assertFalse($testIsInMenuOtherPage);
        $this->assertFalse($testIsInMenuOtherPage2);


    }


    public function testMenuManagerAddContentToMenuTwice()
    {
        MenuItem::where('parent_id', $this->menu_id)->delete();
        $newCleanPageId = save_content([
            'subtype' => 'static',
            'content_type' => 'page',

            'title' => 'liveedittestMenuManagerAddContentToMenuTwice',
            'url' => 'liveedittestMenuManagerAddContentToMenuTwice',

            'is_active' => 1,
            'add_content_to_menu' => [
                $this->menu_id
            ]
        ]);
        $testIsInMenu = is_in_menu($this->menu_id, $newCleanPageId);
        $this->assertTrue($testIsInMenu);


        $newCleanPageId = save_content([
            'id' => $newCleanPageId,

            'add_content_to_menu' => [
                $this->menu_id
            ]
        ]);

        $testIsInMenu = is_in_menu($this->menu_id, $newCleanPageId);
        $this->assertTrue($testIsInMenu);

        $menuItemOfPage = app()->menu_manager->get_menu_items('content_id=' . $newCleanPageId . '&parent_id=' . $this->menu_id);
        $this->assertIsArray($menuItemOfPage);
        $this->assertEquals(count($menuItemOfPage), 1);

    }


    public function testMenuContentAddedFromRelation()
    {

        $newCleanPageId = save_content([
            'subtype' => 'static',
            'title' => 'testMenuContentAddedFromRelation',

        ]);
        $find = Content::where('id', $newCleanPageId)->first();
        $menu = get_menus('single=1&title=' . $this->menu_title);
        $find->menuItems()->create([
            'title' => 'test',
            'parent_id' => $menu['id'],
            'is_active' => 1,
            'position' => 1
        ]);
        $this->assertNotEmpty($find->menuItems()->get());
        $testIsInMenu = is_in_menu($menu['id'], $newCleanPageId);
        $this->assertTrue($testIsInMenu);
    }

    public function testMenuIdsArrtbuteOnModel()
    {
        $newCleanPageId = save_content([
            'subtype' => 'static',
            'title' => 'testMenuIdsArrtbuteOnModel',

        ]);
        $menu = get_menus('single=1&title=' . $this->menu_title);

        $find = Content::where('id', $newCleanPageId)->first();

        $find->setMenuIds([$menu['id']]);

        $find->save();

        $testIsInMenu = is_in_menu($menu['id'], $newCleanPageId);

        $this->assertTrue($testIsInMenu);
    }

}
