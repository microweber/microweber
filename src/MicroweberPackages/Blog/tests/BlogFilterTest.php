<?php
namespace MicroweberPackages\Blog\tests;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use MicroweberPackages\Blog\Http\Controllers\BlogController;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Shop\Http\Controllers\ShopController;

class BlogFilterTest extends TestCase
{
    public function testGetPosts()
    {
        $newPage = new Page();
        $newPage->title = uniqid();
        $newPage->content_type = 'page';
        $newPage->subtype = 'dynamic';
        $newPage->save();

        $blogPage = Page::where('content_type', 'page')
            ->where('subtype','dynamic')
            ->first();

        $moduleId = 'blog-'. uniqid();

        save_option('content_from_id', $moduleId);

        $posts = [];

        for($i = 0; $i < 5; $i++) {

            $newPost = new Post();
            $newPost->title = uniqid();
            $newPost->parent = $blogPage->id;
            $newPost->save();

            $posts[] = $newPost;
        }

        $params = [];
        $params['id'] = $blogPage->id;

        $request = new \Illuminate\Http\Request();
        $request->merge($params);

        $controller = App::make(BlogController::class);
        $controller->setModuleParams($params);
        $controller->setModuleConfig([
            'module'=> $moduleId
        ]);
        $controller->registerModule();

        $html = $controller->index($request);
        $htmlString = $html->__toString();

        foreach ($posts as $post) {
            $findPostTitle = (strpos($htmlString, $post->title) !== false);
            $this->assertTrue($findPostTitle);
        }

        $findJs = (strpos($html, 'filter.js') !== false);
        $this->assertTrue($findJs);

        $findCss = (strpos($html, 'filter.css') !== false);
        $this->assertTrue($findCss);

    }
}
