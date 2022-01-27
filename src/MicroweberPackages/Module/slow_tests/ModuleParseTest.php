<?php

namespace MicroweberPackages\Module\slow_tests;

use Illuminate\Support\Str;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\ContentData\Traits\ContentDataTrait;
use Illuminate\Database\Eloquent\Model;


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
        <header
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

}
