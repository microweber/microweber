<?php
namespace Modules\Blog\Tests\Unit;

use Illuminate\Support\Facades\App;
use MicroweberPackages\Blog\Http\Controllers\BlogController;
use MicroweberPackages\Core\tests\TestCase;
use Modules\Page\Models\Page;
use Modules\Post\Models\Post;

class BlogFilterTest extends TestCase
{
    public function testGetPosts()
    {

        // Create dynamic page
        $newBlogPage = new Page();
        $newBlogPage->title = uniqid();
        $newBlogPage->content_type = 'page';
        $newBlogPage->subtype = 'dynamic';
        $newBlogPage->save();

        $blogPage = Page::where('id', $newBlogPage->id)->first();

        $moduleId = 'blog--mw--'. uniqid();

        save_option('content_from_id', $blogPage->id, $moduleId);

        $posts = [];

        for($i = 0; $i < 5; $i++) {

            $newPost = new Post();
            $newPost->title = uniqid();
            $newPost->parent = $blogPage->id;
            $newPost->save();

            $posts[] = $newPost;
        }

        $params = [];
        $params['id'] = $moduleId;

        $request = new \Illuminate\Http\Request();
        $request->merge($params);

        $controller = App::make(BlogController::class);
        $controller->setModuleParams($params);
        $controller->setModuleConfig([
            'module'=> 'blog'
        ]);
        $controller->registerModule();

        $html = $controller->index($request);
        $htmlString = $html->__toString();

        foreach ($posts as $post) {
            $findPostTitle = (str_contains($htmlString, $post->title) !== false);
            $this->assertTrue($findPostTitle);
        }

        $findJs = (str_contains($htmlString, 'filter.js') !== false);
        $this->assertTrue($findJs);

        $findCss = (str_contains($htmlString, 'filter.css') !== false);
        $this->assertTrue($findCss);

    }
}
