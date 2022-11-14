<?php

namespace MicroweberPackages\Module\slow_tests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\ContentData\Traits\ContentDataTrait;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\User\Models\User;


class ModuleParseTest extends TestCase
{


    public function testIfModulesIdsAreAssignedOnParser()
    {
        $layout = '<!DOCTYPE html>
        <html>
        <head>
        <title>Title of the document</title>
        </head>

        <body>
        <module type="btn" template="default" />
        <module type="btn" template="default" />
        <module type="btn" template="default" />
        <module type="btn" template="default" />
        </body>

        </html>';


        $app = app();
        $layout =  $app->parser->process($layout);


        $pq = \phpQuery::newDocument($layout);
        $els = $pq['.module'];
        foreach ($els as $key=>$elem) {
            $id = $elem->getAttribute('id');
            $parent_id = $elem->getAttribute('parent-module-id');

            if($key == 0){
                $this->assertEquals('module-btn', $id);
            } else {
                $this->assertEquals('module-btn--'.$key, $id);
            }

        }
    }

    public function testIfModulesIdsAreAssignedOnParser2()
    {


        $layout = '<!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/html">
        <head>
        <title>Title of the document</title>
        </head>
        <body>
        <header>
                <module type="menu" template="default" />
                <module type="shop/cart" template="default" />
        </header>

        <main>
        <module type="posts" template="default" />
        </main>

        <footer>
                <module type="contact_form" template="default" />
        </footer>
        </body>

        </html>';

        $app = app();

        $layout =  $app->parser->process($layout);
        $pq = \phpQuery::newDocument($layout);
        $ids_check = [
            'module-menu',
            'module-shop-cart',
            'module-posts',
            'module-contact-form',

        ];

        foreach ($ids_check as $id_val) {
            $els = $pq['#'.$id_val];
            $this->assertEquals(!empty($els), true);
            foreach ($els as $key=>$elem) {
                $id = $elem->getAttribute('id');
                $this->assertEquals($id_val, $id);
            }
        }
    }

    public function testIfModulesIdsAreAssignedOnParser3()
    {

        $layout = <<<HTML
        <!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/html">
        <head>
        <title>Title of the document</title>
        </head>
        <script>
        var a = '<module type="menu" template="default" />';
        </script>
        <body>
        <textarea id="should-not-parse-modules"><module type="btn" template="default"></module></textarea>
        <textarea id="should-not-parse-script"><script>var b = '';</script></textarea>
        <input id="should-not-parse-input" type="text" name="test" value="<module type=ants template=default />" />
        </body>

        </html>
HTML;




        $app = app();

        $layout =  $app->parser->process($layout);



        $this->assertEquals(str_contains($layout,'<module type="menu" template="default" />'), true);


        $pq = \phpQuery::newDocument($layout);
        $val=   $pq->find('#should-not-parse-modules')->val();
        $this->assertEquals($val, '<module type="btn" template="default"></module>');

        $val=   $pq->find('#should-not-parse-script')->val();
        $this->assertEquals($val, '<script>var b = \'\';</script>');


        $val=   $pq->find('#should-not-parse-input')->val();
        $this->assertEquals($val, '<module type=ants template=default />');


    }

    public function testModuleSettingsGroupAdmin()
    {

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $layout = <<<HTML
     <module id="settings_admin_mw-main-module-backend-settings-admin" class="card-body pt-3" option_group="shop/settings" is_system="1" style="position: relative;" data-type="settings/system_settings" />
HTML;
        $layout = app()->parser->process($layout);


        $pq = \phpQuery::newDocument($layout);

        $icons_classes = [
            'mdi-cart-outline',
            'mdi-scissors-cutting',
            'mdi-truck-outline',
            'mdi-label-percent-outline',
            'mdi-cash-usd-outline',
            'mdi-account-cash-outline',
            'mdi-cash-register',
            'mdi-email-edit-outline',
            'mdi-cog-outline',
        ];
        foreach ($icons_classes as $icon_class) {
            $val = $pq->find('i.' . $icon_class)->parent()->attr('class');
            $this->assertEquals($val,'icon-holder');

        }


    }


    public function testParsingModuleTags()
    {


        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $layout = <<<HTML
<div class="module module-highlight-code" data-mw-title="highlight_code" data-type="highlight_code" id="highlight-code-20221114094239" parent-module="highlight_code" parent-module-id="highlight-code-20221114094239"></div>

HTML;
        $layout = app()->parser->make_tags($layout);
        $layout = trim($layout);
        $expected = '<module class="module module-highlight-code" data-mw-title="highlight_code" data-type="highlight_code" id="highlight-code-20221114094239" parent-module="highlight_code" parent-module-id="highlight-code-20221114094239"></module>';
        $this->assertEquals($layout, $expected);


    }


}
