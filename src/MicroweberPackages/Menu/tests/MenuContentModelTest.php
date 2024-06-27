<?php


namespace MicroweberPackages\Menu\tests;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Menu\Models\Menu;

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


}
