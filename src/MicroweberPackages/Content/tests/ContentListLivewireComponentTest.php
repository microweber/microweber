<?php

namespace MicroweberPackages\Content\tests;

use Livewire\Livewire;
use MicroweberPackages\App\Http\Controllers\FrontendController;
use MicroweberPackages\Content\Http\Livewire\Admin\ContentList;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Page\Http\Livewire\Admin\PagesList;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Http\Livewire\Admin\PostsList;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Product\Http\Livewire\Admin\ProductsList;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\User\tests\UserTestHelperTrait;

class ContentListLivewireComponentTest extends TestCase
{
    use UserTestHelperTrait;

    public function testPageBasicComponent()
    {
        $this->actingAsAdmin();

        $myPage = new Page();
        $myPage->title = 'My page';
        $myPage->content = 'My page content';
        $myPage->save();

        $getAllPages = Page::get();

        $contentListTest = Livewire::test(PagesList::class);
        $contentListTest->call('getRenderData');
        $response = json_decode($contentListTest->lastResponse->content(),TRUE);
        $responseMethod = reset($response['effects']['returns']);
        $contentListResponseData = $responseMethod['data']['contents']['data'];

        $findMyPage = false;
        foreach ($contentListResponseData as $pageData) {
            if ($pageData['id'] == $myPage->id) {
                $findMyPage = true;
            }
        }

        $this->assertTrue($findMyPage);
        $this->assertEquals($getAllPages->count(), $responseMethod['data']['contents']['total']);

    }

    public function testPostsBasicComponent()
    {
        $this->actingAsAdmin();

        $myPost = new Post();
        $myPost->title = 'My post';
        $myPost->content = 'My post content';
        $myPost->save();

        $getAllPosts = Post::get();

        $contentListTest = Livewire::test(PostsList::class);
        $contentListTest->call('getRenderData');
        $response = json_decode($contentListTest->lastResponse->content(),TRUE);
        $responseMethod = reset($response['effects']['returns']);
        $contentListResponseData = $responseMethod['data']['contents']['data'];

        $findMyPost = false;
        foreach ($contentListResponseData as $pageData) {
            if ($pageData['id'] == $myPost->id) {
                $findMyPost = true;
            }
        }

        $this->assertTrue($findMyPost);
        $this->assertEquals($getAllPosts->count(), $responseMethod['data']['contents']['total']);

    }

    public function testProductsBasicComponent()
    {
        $this->actingAsAdmin();

        $myProduct = new Product();
        $myProduct->title = 'My product';
        $myProduct->content = 'My product content';
        $myProduct->save();

        $getAllProducts = Product::get();

        $contentListTest = Livewire::test(ProductsList::class);
        $contentListTest->call('getRenderData');
        $response = json_decode($contentListTest->lastResponse->content(),TRUE);
        $responseMethod = reset($response['effects']['returns']);
        $contentListResponseData = $responseMethod['data']['contents']['data'];

        $findMyProduct = false;
        foreach ($contentListResponseData as $pageData) {
            if ($pageData['id'] == $myProduct->id) {
                $findMyProduct = true;
            }
        }

        $this->assertTrue($findMyProduct);
        $this->assertEquals($getAllProducts->count(), $responseMethod['data']['contents']['total']);


    }
}
