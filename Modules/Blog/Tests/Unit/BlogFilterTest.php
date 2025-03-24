<?php
namespace Modules\Blog\Tests\Unit;

use Illuminate\Support\Facades\App;
use MicroweberPackages\Core\tests\TestCase;
use Modules\Blog\Livewire\BlogComponent;
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

        $controller = App::make(BlogComponent::class);
        $controller->mount($moduleId);
        $htmlString = $controller->render();
        $htmlString = $htmlString->__toString();

        foreach ($posts as $post) {
            $findPostTitle = (str_contains($htmlString, $post->title) !== false);
            $this->assertTrue($findPostTitle);
        }

//        $findJs = (str_contains($htmlString, 'filter.js') !== false);
//        $this->assertTrue($findJs);
//
//        $findCss = (str_contains($htmlString, 'filter.css') !== false);
//        $this->assertTrue($findCss);

        $findJs = (str_contains($htmlString, 'wire:model.live') !== false);
        $this->assertTrue($findJs);

    }
}
