# Post Module — Examples

End-to-end recipes for common blog workflows.

## Recipe 1: Create a post with categories + tags + featured image

```php
use Modules\Post\Models\Post;
use Modules\Media\Models\Media;

$post = Post::create([
    'title'        => 'Building a CMS Theme in 2026',
    'url'          => 'building-a-cms-theme-2026',
    'description'  => 'A complete walkthrough of building a Microweber template from scratch.',
    'content_body' => '<h1>Intro</h1><p>...</p>',
    'parent'       => $blogPageId,
    'is_active'    => 1,
    'posted_at'    => now(),
]);

$post->setCategories([$tutorialsCategoryId, $themesCategoryId]);
$post->tag(['microweber', 'theme', 'tutorial']);

Media::create([
    'rel_type' => 'content',
    'rel_id'   => $post->id,
    'filename' => '/media/default/theme-hero.jpg',
    'media_type' => 'image',
    'position' => 0,
]);

echo $post->link;   // /blog/building-a-cms-theme-2026
```

## Recipe 2: Schedule a post for next week

```php
$post = Post::create([
    'title'     => 'Black Friday Tips',
    'url'       => 'black-friday-tips',
    'parent'    => $blogPageId,
    'is_active' => 1,
    'posted_at' => now()->addDays(7),  // visible to admin, hidden from public until then
]);

// On the public blog index, gate on posted_at:
$visible = Post::active()
    ->where('posted_at', '<=', now())
    ->orderBy('posted_at', 'desc')
    ->paginate(10);
```

## Recipe 3: Build a category archive page

```php
$category = \DB::table('categories')->where('id', $categoryId)->first();
$posts = Post::active()
    ->whereCategoryIds($categoryId)
    ->where('posted_at', '<=', now())
    ->orderBy('posted_at', 'desc')
    ->paginate(12);

return view('blog.category', [
    'category' => $category,
    'posts'    => $posts,
]);
```

In the blade:

```html
<h1>Category: {{ $category->title }}</h1>

@foreach($posts as $post)
    <article>
        <a href="{{ $post->link }}">
            @if($post->image)
                <img src="{{ $post->image }}" alt="{{ $post->title }}" loading="lazy">
            @endif
            <h2>{{ $post->title }}</h2>
        </a>
        <p>{{ $post->description }}</p>
        <time datetime="{{ $post->posted_at }}">{{ $post->posted_at->format('M j, Y') }}</time>
    </article>
@endforeach

{{ $posts->links() }}
```

## Recipe 4: Build a tag cloud

```php
use Conner\Tagging\Model\TagGroup;
use Conner\Tagging\Model\Tagged;

$tags = \DB::table('tagging_tags')
    ->select('name', \DB::raw('count(*) as count'))
    ->whereIn('id', function ($q) {
        $q->select('tag_id')
          ->from('tagging_tagged')
          ->where('taggable_type', \Modules\Content\Models\Content::class);
    })
    ->groupBy('name')
    ->orderBy('count', 'desc')
    ->limit(50)
    ->get();
```

```html
<div class="tag-cloud">
    @foreach($tags as $tag)
        <a href="/blog/tag/{{ Str::slug($tag->name) }}" class="tag-{{ ceil($tag->count / 5) }}">
            {{ $tag->name }} ({{ $tag->count }})
        </a>
    @endforeach
</div>
```

## Recipe 5: Filter posts via the public BlogFilter

```php
use Modules\Blog\FrontendFilter\BlogFilter;

$filter = new BlogFilter(request()->all());
$posts = $filter->apply(Post::active())->paginate(10);
```

`BlogFilter` reads the standard query params (`category`, `tag`, `from`, `to`, `q`) and chains them onto the Eloquent builder.

## Recipe 6: Author archive page

```php
$author = \App\Models\User::findOrFail($authorId);
$posts = Post::active()
    ->where('created_by', $author->id)
    ->where('posted_at', '<=', now())
    ->orderBy('posted_at', 'desc')
    ->paginate(15);

return view('blog.author', compact('author', 'posts'));
```

## Recipe 7: Generate an RSS feed

```php
use Modules\Post\Models\Post;

$posts = Post::active()
    ->where('posted_at', '<=', now())
    ->orderBy('posted_at', 'desc')
    ->limit(20)
    ->get();

$rss = view('feeds.rss', compact('posts'))->render();

return response($rss, 200, ['Content-Type' => 'application/rss+xml']);
```

```html
<!-- feeds/rss.blade.php -->
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
  <channel>
    <title>{{ option('website_title', 'website') }}</title>
    <link>{{ url('/') }}</link>
    <description>{{ option('website_description', 'website') }}</description>
    @foreach($posts as $post)
      <item>
        <title>{{ $post->title }}</title>
        <link>{{ $post->link }}</link>
        <description><![CDATA[{{ $post->description ?: strip_tags(Str::limit($post->content_body, 300)) }}]]></description>
        <pubDate>{{ $post->posted_at->toRfc822String() }}</pubDate>
        <guid>{{ $post->link }}</guid>
      </item>
    @endforeach
  </channel>
</rss>
```

## Recipe 8: Bulk publish/unpublish via tinker

```php
// Publish all draft posts older than 24 hours
Post::where('is_active', 0)
    ->where('created_at', '<=', now()->subDay())
    ->update(['is_active' => 1, 'posted_at' => now()]);

// Unpublish posts in a category
Post::whereCategoryIds($oldCategoryId)->update(['is_active' => 0]);
```

## Recipe 9: Test factory with relationships

```php
namespace Modules\Post\Tests\Unit;

use Modules\Page\Models\Page;
use Modules\Post\Models\Post;
use Tests\TestCase;

class BlogListingTest extends TestCase
{
    public function test_blog_index_shows_active_posts_in_date_order(): void
    {
        $blog = Page::factory()->create(['url' => 'blog', 'subtype' => 'dynamic']);

        $newer = Post::factory()->create([
            'parent' => $blog->id,
            'posted_at' => now()->subHour(),
        ]);

        $older = Post::factory()->create([
            'parent' => $blog->id,
            'posted_at' => now()->subDays(7),
        ]);

        $response = $this->get('/blog');
        $response->assertOk();
        $response->assertSeeInOrder([$newer->title, $older->title]);
    }
}
```

## Recipe 10: REST API create from a script

```bash
TOKEN=$(curl -s -X POST https://yoursite.com/api/login \
    -H "Content-Type: application/json" \
    -d '{"email":"admin@yoursite.com","password":"…"}' | jq -r .token)

curl -X POST https://yoursite.com/api/posts \
    -H "Authorization: Bearer $TOKEN" \
    -H "Content-Type: application/json" \
    -d '{
        "title": "Auto-published from CI",
        "content_body": "<p>This post was created by a deploy pipeline.</p>",
        "parent": 8,
        "is_active": 1,
        "categories": [3],
        "tags": ["release-notes"]
    }' | jq .
```
