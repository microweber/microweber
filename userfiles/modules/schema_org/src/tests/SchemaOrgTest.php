<?php
namespace MicroweberPackages\Modules\SchemaOrg\tests;

use Illuminate\Support\Str;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;

class SchemaOrgTest extends TestCase
{
    public function testModule()
    {

        load_all_functions_files_for_modules();

        // TEST PAGE

        $page = new Page();
        $page->title = 'Test';
        $page->url = Str::slug($page->title);
        $page->save();

        $graph = getSchemaOrgScriptByContentIds([$page->id]);

        $this->assertIsString($graph);
        $this->assertStringContainsString('WebPage', $graph);
        $this->assertStringContainsString($page->title, $graph);

        // TEST POST

        $post = new Post();
        $post->title = 'Post Test';
        $post->url = Str::slug($post->title);
        $post->save();

        $graph = getSchemaOrgScriptByContentIds([$post->id]);

        $this->assertIsString($graph);
        $this->assertStringContainsString('Article', $graph);
        $this->assertStringContainsString($post->title, $graph);


        // TEST PRODUCT

        $product = new \MicroweberPackages\Product\Models\Product();
        $product->title = 'Product Test';
        $product->url = Str::slug($product->title);
        $product->setCustomField(
            [
                'type'=>'price',
                'name'=>'price',
                'value'=>'300',
            ]
        );
        $product->save();

        $graph = getSchemaOrgScriptByContentIds([$product->id]);

        $this->assertIsString($graph);
        $this->assertStringContainsString('"availability":"InStock"', $graph);
        $this->assertStringContainsString('"priceCurrency":"USD"', $graph);
        $this->assertStringContainsString('"price":"300"', $graph);
        $this->assertStringContainsString('Product', $graph);
        $this->assertStringContainsString($product->title, $graph);


        // TEST PRODUCT #2

        $product = new \MicroweberPackages\Product\Models\Product();
        $product->title = 'Product Test 2';
        $product->url = Str::slug($product->title);
        $product->setCustomField(
            [
                'type'=>'price',
                'name'=>'price',
                'value'=>'341',
            ]
        );
        $product->setContentData(['qty'=>'0']);
        $product->save();

        $graph = getSchemaOrgScriptByContentIds([$product->id]);

        $this->assertIsString($graph);
        $this->assertStringContainsString('"availability":"OutOfStock"', $graph);
        $this->assertStringContainsString('"priceCurrency":"USD"', $graph);
        $this->assertStringContainsString('"price":"341"', $graph);
        $this->assertStringContainsString('Product', $graph);
        $this->assertStringContainsString($product->title, $graph);



    }

}
