<?php

namespace MicroweberPackages\Repository\tests;


use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Product\Models\Product;

class ContentRepositoryTest extends TestCase
{

    public function testContentRepository()
    {

        // add data

        $title = 'Duncan mclaude! - ' . rand();
        $year = rand(111, 999);
        $product = new Product();
        $product->title = $title;
        $product->save();

        $prod_id = $product->id;
        $product = Product::find($prod_id);
        $product->setContentData(['weapon' => 'pen', 'year' => $year]);
        $product->save();


        // get from repository test

        $content_repository = app()->repository_manager->driver(\MicroweberPackages\Content\Content::class);

        $cont = $content_repository->findAllBy('id', $prod_id);

        $newyear = 'New' . rand();
        $newyearval = 'Newval' . rand();


        foreach ($cont as $con) {
            $data = $content_repository->findById($con->id)->contentData;
            if ($data) {
                foreach ($data as $dat) {

                    if ($dat->field_name == 'year') {
                        $dat->field_name = $newyear;
                        $dat->field_value = $newyearval;
                        $s = $dat->save();
                        $data1 = $content_repository->getContentData($con->id);
                        foreach ($data1 as $dat2) {
                            if ($dat2['id'] == $dat->id) {
                                $new_field_name = $dat->field_name;
                            }
                        }
                    }
                }

            }
        }

        $this->assertEquals($new_field_name, $newyear);
        $this->assertEquals($s, true);

        $content_data = content_data($prod_id);
        $this->assertEquals($content_data[$newyear], $newyearval);

        $contById = $content_repository->findById($prod_id);
        $this->assertEquals($contById->id, $prod_id);


        $del = delete_content($prod_id);
        $contMustBeNull = $content_repository->findById($prod_id);

        $this->assertEquals($contMustBeNull, null);


    }


}