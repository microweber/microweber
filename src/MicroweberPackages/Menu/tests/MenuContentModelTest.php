<?php


namespace MicroweberPackages\Menu\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Menu\Models\Menu;
use Modules\Content\Models\Content;

class MenuContentModelTest extends TestCase
{


    public function testIfContentModelIsAttachedToMenu()
    {

        $menu = new Menu();
        $menu->title = 'test menu';
        $menu->save();
        $menu_id = $menu->id;


        $content = new Content();
        $content->title = 'test content';
        $content->setMenuIds($menu_id);
        $content->save();

        $this->assertNotEmpty($content->menuItems()->get());

    }


    public function testIfMenuIdsAttrbuteIsSavedFromSetMenuIdsMethod()
    {

        $menu = new Menu();
        $menu->title = 'test menu';
        $menu->save();
        $menu_id = $menu->id;


        $content = new Content();
        $content->title = 'test content';

        $content->menuIds = [$menu_id];

        $content->save();

        $this->assertNotEmpty($content->menuItems()->get());

    }


}
