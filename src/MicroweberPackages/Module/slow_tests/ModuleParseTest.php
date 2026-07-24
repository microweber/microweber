<?php

namespace MicroweberPackages\Module\slow_tests;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use MicroweberPackages\User\Models\User;


class ModuleParseTest extends TestCase
{


    #[Test]


    public function it_if_modules_ids_are_assigned_on_parser(): void {
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

    #[Test]

    public function it_if_modules_ids_are_assigned_on_parser2(): void {


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

    #[Test]

    public function it_if_modules_ids_are_assigned_on_parser3(): void {

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



    #[Test]



    public function it_parsing_module_tags(): void {


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

    #[Test]
    public function it_layout_processor_is_the_default_parser(): void
    {
        // The LayoutProcessor pipeline is the default; legacy is opt-in via
        // use_legacy_parser (config/env/admin option), which defaults OFF.
        $this->assertFalse((bool) config('microweber.use_legacy_parser'),
            'use_legacy_parser defaults to false → LayoutProcessor is the default');
    }

    #[Test]
    public function it_use_legacy_parser_falls_back_to_legacy(): void
    {
        // Opt into legacy → the legacy phpQuery flow.
        config(['microweber.use_legacy_parser' => true]);
        try {
            $layout = '<div class="edit" rel="content" field="content">'
                . '<module type="btn" template="default"/>'
                . '<module type="btn" template="default"/>'
                . '</div>';

            $parser = new \MicroweberPackages\App\Utils\ParserProcessor();
            $out = $parser->process($layout);

            // Legacy flow assigns module-btn / module-btn--1 and leaves no raw tags.
            $this->assertStringContainsString('module-btn', $out);
            $this->assertStringNotContainsString('<module', $out);
        } finally {
            config(['microweber.use_legacy_parser' => false]);
        }
    }

    #[Test]
    public function it_layout_processor_flag_on_renders_via_new_pipeline(): void
    {
        config(['microweber.use_legacy_parser' => false]);
        try {
            // Use a non-existent rel/field combo so stored edit-field content
            // does not overwrite the modules in the test layout.
            $layout = '<div class="edit" rel="global" field="test_pipeline_' . uniqid() . '">'
                . '<module type="btn" template="default"/>'
                . '<module type="btn" template="default"/>'
                . '</div>'
                . '<!-- <module type="btn"/> -->'
                . '<pre><module type="btn"/></pre>';

            $parser = new \MicroweberPackages\App\Utils\ParserProcessor();
            $out = $parser->process($layout);

            // Modules tokenized + id-allocated by the new pipeline.
            $this->assertStringContainsString('id="module-btn"', $out);
            $this->assertStringContainsString('class="module module-btn"', $out);
            // No bogus -0 suffix when there is no content scope.
            $this->assertStringNotContainsString('id="module-btn-0"', $out);
            // Comment + pre modules stay verbatim (protected, not rendered).
            $this->assertStringContainsString('<!-- <module type="btn"/> -->', $out);
            $this->assertStringContainsString('<pre><module type="btn"/></pre>', $out);
        } finally {
            config(['microweber.use_legacy_parser' => false]);
        }
    }

    #[Test]
    public function it_layout_processor_flag_on_keeps_slash_module_type(): void
    {
        config(['microweber.use_legacy_parser' => false]);
        try {
            $layout = '<div class="edit" rel="global" field="test_slash_' . uniqid() . '">'
                . '<module type=shop/products template=default />'
                . '</div>';

            $parser = new \MicroweberPackages\App\Utils\ParserProcessor();
            $out = $parser->process($layout);

            // Slash type survives: id is dashed, data-type keeps the slash.
            $this->assertStringContainsString('id="module-shop-products"', $out);
            $this->assertStringContainsString('data-type="shop/products"', $out);
            $this->assertStringNotContainsString('<module', $out);
        } finally {
            config(['microweber.use_legacy_parser' => false]);
        }
    }


}
