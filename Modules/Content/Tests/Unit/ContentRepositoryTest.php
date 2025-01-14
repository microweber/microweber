<?php

namespace Modules\Content\Tests\Unit;

use MicroweberPackages\Core\tests\TestCase;
use Modules\Content\Models\Content;
use Modules\Product\Models\Product;

class ContentRepositoryTest extends TestCase
{
    public function testGetBlogsFromRepository()
    {
        Content::truncate();

        //saving

        $title = 'Blog' . rand();
        $saveContent = array(
            'title' => $title,
            'content_type' => 'page',
            'subtype' => 'dynamic',
            'is_active' => 1
        );

        $saveContent2 = array(
            'title' => $title . '2',
            'content_type' => 'page',
            'subtype' => 'dynamic',
            'is_active' => 1
        );

        $save_id = save_content($saveContent);
        $save_id2 = save_content($saveContent2);
        $saved = get_content_by_id($save_id);
        $saved2 = get_content_by_id($save_id2);

        $this->assertEquals($saved['title'], $title);
        $this->assertEquals($saved2['title'], $title . '2');

        $first_blog = app()->content_repository->getFirstBlogPage();
        $this->assertEquals($saved['title'], $first_blog['title']);

        $all = app()->content_repository->getAllBlogPages();

        $this->assertEquals(count($all), 2);

        foreach ($all as $item) {
            $this->assertEquals($item['subtype'], 'dynamic');
        }


    }

    public function testGetShopsFromRepository()
    {
        Content::truncate();

        //saving
        $title = 'Shop' . rand();
        $saveContent = array(
            'title' => $title,
            'content_type' => 'page',
            'is_shop' => 1,
            'is_active' => 1
        );

        $saveContent2 = array(
            'title' => $title . '2',
            'content_type' => 'page',
            'is_shop' => 1,
            'is_active' => 1
        );

        $save_id = save_content($saveContent);
        $save_id2 = save_content($saveContent2);
        $saved = get_content_by_id($save_id);
        $saved2 = get_content_by_id($save_id2);

        $this->assertEquals($saved['title'], $title);
        $this->assertEquals($saved2['title'], $title . '2');

        $first_blog = app()->content_repository->getFirstShopPage();
        $this->assertEquals($saved['title'], $first_blog['title']);


        $all = app()->content_repository->getAllShopPages();

        $this->assertEquals(count($all), 2);

        foreach ($all as $item) {
            $this->assertEquals($item['is_shop'], 1);
        }


    }
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

        $content_repository = app()->content_repository;

        $cont = $content_repository->findAllBy('id', $prod_id);

        $newyear = 'New' . rand();
        $newyearval = 'Newval' . rand();


        foreach ($cont as $con) {

            $data = $content_repository->findById($con->id)->contentData()->get();
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


        $query1 = $content_repository->makeModel()->select('content.*')->where('id','<>',$prod_id)->first();
        $query2 = $content_repository->makeModel()->select('content.*')->where('id','=',$prod_id)->first();
        $this->assertEquals($query2->id, $prod_id);
        $this->assertNotEquals($query1->id, $prod_id);



        $del = delete_content($prod_id);
        $contMustBeNull = $content_repository->findById($prod_id);

        $this->assertEquals($contMustBeNull, null);



        // check non existing content data
        $data =  $content_repository->getContentData(0);
        $this->assertIsArray($data);

    }
}
