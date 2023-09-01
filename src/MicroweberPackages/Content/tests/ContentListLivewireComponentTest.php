<?php

namespace MicroweberPackages\Content\tests;

use Livewire\Livewire;
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

    public function testContentBasicComponent()
    {
        $this->actingAsAdmin();

        $myPage = new Page();
        $myPage->title = 'My page';
        $myPage->content = 'My page content';
        $myPage->save();

        $myProduct = new Product();
        $myProduct->title = 'My product';
        $myProduct->content = 'My product content';
        $myProduct->save();

        $myPost = new Post();
        $myPost->title = 'My post';
        $myPost->content = 'My post content';
        $myPost->save();

        $getAllContents = Content::get();

        $contentListTest = Livewire::test(ContentList::class);
        $contentListTest->set('paginate', 200);
        $contentListTest->call('getRenderData');
        $response = json_decode($contentListTest->lastResponse->content(),TRUE);
        $responseMethod = reset($response['effects']['returns']);
        $contentListResponseData = $responseMethod['data']['contents']['data'];

        $findMyPage = false;
        $findMyPost = false;
        $findMyProduct = false;
        foreach ($contentListResponseData as $contentData) {
            if ($contentData['id'] == $myPage->id) {
                $findMyPage = true;
            }
            if ($contentData['id'] == $myPost->id) {
                $findMyPost = true;
            }
            if ($contentData['id'] == $myProduct->id) {
                $findMyProduct = true;
            }
        }

        $this->assertTrue($findMyPage);
        $this->assertTrue($findMyPost);
        $this->assertTrue($findMyProduct);

        $this->assertEquals($getAllContents->count(), $responseMethod['data']['contents']['total']);

    }

    public function testContentSearchBasicComponent()
    {
        $this->actingAsAdmin();

        $myPost = new Post();
        $myPost->title = 'My post content - For search';
        $myPost->content = 'My post content';
        $myPost->save();

        $myProduct = new Product();
        $myProduct->title = 'My product - For search';
        $myProduct->content = 'My product content - For search';
        $myProduct->save();

        $getAllPosts = Post::where('title', 'My post content - For search')->get();

        $contentListTest = Livewire::test(ContentList::class);
        $contentListTest->set('filters', [
            'keyword' => 'My post content - For search'
        ]);
        $contentListTest->call('getRenderData');
        $response = json_decode($contentListTest->lastResponse->content(),TRUE);
        $responseMethod = reset($response['effects']['returns']);
        $contentListResponseData = $responseMethod['data']['contents']['data'];

        $findMyProduct = false;
        $findMyPost = false;
        foreach ($contentListResponseData as $contentData) {
            if ($contentData['id'] == $myProduct->id) {
                $findMyProduct = true;
            }
            if ($contentData['id'] == $myPost->id) {
                $findMyPost = true;
            }
        }

        $this->assertTrue($findMyPost);
        $this->assertFalse($findMyProduct);
        $this->assertEquals($getAllPosts->count(), $responseMethod['data']['contents']['total']);

    }

    public function testPostSearchWithTagsBasicComponent()
    {
        $this->actingAsAdmin();

        for ($i = 1; $i <= 10; $i++) {
            $myPost = new Post();
            $myPost->title = 'My post content - For search with tags' . $i;
            $myPost->content = 'My post content';
            $myPost->save();
        }

        $contentListTest = Livewire::test(ContentList::class);
        $contentListTest->set('filters', [
            'tags' => 'non-existing-tag'
        ]);
        $contentListTest->call('getRenderData');
        $response = json_decode($contentListTest->lastResponse->content(),TRUE);
        $responseMethod = reset($response['effects']['returns']);
        $contentListResponseData = $responseMethod['data']['contents']['data'];

        $this->assertEmpty($contentListResponseData);
        $this->assertEquals(0, $responseMethod['data']['contents']['total']);

    }

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

    public function testProductsSearchBasicComponent()
    {
        $this->actingAsAdmin();

        $myProduct = new Product();
        $myProduct->title = 'My product';
        $myProduct->content = 'My product content';
        $myProduct->save();

        $myProduct = new Product();
        $myProduct->title = 'My product - For search';
        $myProduct->content = 'My product content - For search';
        $myProduct->save();

        $getAllProducts = Product::where('title', 'My product - For search')->get();

        $contentListTest = Livewire::test(ProductsList::class);
        $contentListTest->set('filters', [
            'keyword' => 'My product content - For search'
        ]);
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
