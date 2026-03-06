<?php


namespace Modules\Menu\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use Modules\Content\Models\Content;
use Modules\Menu\Models\Menu;

class MenuContentModelTest extends TestCase
{


    #[Test]


    public function it_if_content_model_is_attached_to_menu(): void {

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


    #[Test]


    public function it_if_menu_ids_attrbute_is_saved_from_set_menu_ids_method(): void {

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
