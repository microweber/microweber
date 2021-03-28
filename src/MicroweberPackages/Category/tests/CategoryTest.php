<?php

namespace MicroweberPackages\Media\tests;

use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Category\Traits\CategoryTrait;
use MicroweberPackages\Core\tests\TestCase;

use Illuminate\Database\Eloquent\Model;


class ContentTestModelForCategories extends Model
{
    use CategoryTrait;

    protected $table = 'content';

}

class CategoryTest extends TestCase
{
    public function testAddcategoriesToModel()
    {

        $title = 'New cat for my custom model'.uniqid();

        $category = new Category();
        $category->title = $title;
        $category->save();



        $newPage = new ContentTestModelForCategories();
        $newPage->title = 'Content with cats ';

         $newPage->category_ids = $category->id;

//       $newPage->setCategories  (['kotka', 'horo']);
//
//        $newPage->setCategory([
//              'title' => 'kotka',
//              'url' => 'kotka-slug'
//        ]);

        $newPage->save();

        $cat = $newPage->categories->first();

        $this->assertNotEmpty($cat );
        $this->assertEquals($cat->parent->title,$title );

    }


}
