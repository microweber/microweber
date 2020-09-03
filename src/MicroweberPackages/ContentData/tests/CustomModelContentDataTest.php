<?php

namespace MicroweberPackages\ContentData\tests;

use MicroweberPackages\ContentData\Traits\ContentDataTrait;
use MicroweberPackages\Core\tests\TestCase;

use Illuminate\Database\Eloquent\Model;


class TestModel extends Model
{
    use ContentDataTrait;

    protected $table = 'content';

}


class CustomModelContentDataTest extends TestCase
{
    public function testContentDataToCustomModel()
    {

        $product = new TestModel();
        $product->title = 'Test car bmw';
        $product->save();

        $prod_id = $product->id;
        //  dd($prod_id);

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


        //   $product = Product::find($prod_id);

        $contentData = $product->getContentData(['model', 'year']);

        $this->assertFalse(isset($contentData['year']));
        $this->assertEquals($contentData['model'], 'bmw');


        $product = TestModel::whereContentData(['model' => 'bmw'])->first();
        $this->assertEquals($product['title'], 'Test car bmw');


    }
}
