<?php

namespace MicroweberPackages\LiveEdit\tests;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use MicroweberPackages\User\Models\User;

class LiveEditTopRightMenuTest extends TestCase
{


    #[Test]


    public function it_live_edit_top_right_menu(): void {

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $get = $this->get(route('api.live-edit.get-top-right-menu'));
        $items = ($get->getData());

        $topItem = $items[0];
        $lastItem = array_pop($items);
        $lastItemBefore = array_pop($items);

        $this->assertEquals($topItem->title, 'Back to Admin');
        $this->assertEquals($topItem->href, admin_url());
        $this->assertNotEmpty($topItem->icon_html);

        $this->assertEquals($lastItemBefore->title, 'See website');

        $this->assertEquals($lastItem->title, 'Log out');
        $this->assertEquals($lastItem->href, logout_url());
        $this->assertNotEmpty($lastItem->icon_html);


  //      $this->assertEquals($lastItem->title, 'Template Settings');




    }
}

