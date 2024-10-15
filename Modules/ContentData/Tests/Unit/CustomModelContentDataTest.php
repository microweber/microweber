<?php

namespace Modules\ContentData\Tests\Unit;

use MicroweberPackages\Core\tests\TestCase;


class CustomModelContentDataTest extends TestCase
{
    public function testContentDataToCustomModel()
    {

        $product = new TestModel();
        $product->title = 'Test car bmw';
        $product->save();

        $prod_id = $product->id;

        $product = TestModel::find($prod_id);

        $product->setContentData(['model' => 'bmw', 'year' => 2005]);

        $product->save();


        $product = TestModel::find($prod_id);

        $contentData = $product->getContentData(['model', 'year']);

        $this->assertEquals($contentData['model'], 'bmw');
        $this->assertEquals($contentData['year'], '2005');

        $product = TestModel::find($prod_id);
        $product->deleteContentData(['year']);
        $product->save();

        $contentData = $product->getContentData(['model', 'year']);

        $this->assertFalse(isset($contentData['year']));
        $this->assertEquals($contentData['model'], 'bmw');


        $product = TestModel::whereContentData(['model' => 'bmw'])->first();
        $this->assertEquals($product['title'], 'Test car bmw');


    }
}
