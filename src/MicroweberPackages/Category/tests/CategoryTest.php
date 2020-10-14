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

        $category = new Category();
        $category->title = 'New cat for my custom model';
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

     //   dd($newPage->categories()->get() );

     //   dd('DONE');
    }


}
