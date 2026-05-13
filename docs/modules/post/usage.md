# Post Module — Usage

Day-to-day patterns for authoring, categorizing, querying, and rendering posts.

## Creating a post

```php
use Modules\Post\Models\Post;

$post = Post::create([
    'title'        => 'Getting Started with Microweber',
    'url'          => 'getting-started',
    'description'  => 'A quick intro to the CMS.',
    'content_body' => '<p>Full article body here.</p>',
    'is_active'    => 1,
    'parent'       => 8,  // parent blog page ID
]);
```

The `__construct` forces `content_type = 'post'` so the type is always correct even without explicit assignment.

## Setting a publish date

```php
$post->posted_at = now();        // publish now
$post->posted_at = now()->addDay(); // schedule for tomorrow
$post->save();
```

The blog index orders by `posted_at DESC` and (if your template gates on it) hides rows where `posted_at > now()` — that's how scheduled publishing works without a queued job.

## Categories

```php
// Attach categories (replaces existing)
$post->setCategories([3, 7]);

// Read categories
$ids = $post->categoriesIds();

// Query posts in a category
$posts = Post::active()->whereCategoryIds(5)->get();
$posts = Post::active()->whereCategoryIds([5, 8])->get();
```

Categories live in the `categories` table and attach via `categories_items`. Both Posts and Products use the same taxonomy, so a category can list posts AND products if your template renders both.

## Tags

```php
// Attach tags (additive — keeps existing)
$post->tag(['laravel', 'cms', 'tutorial']);

// Replace tags
$post->retag(['laravel', 'release-notes']);

// Read tags
$names = $post->tagNames();

// Query posts with any of the given tags
$posts = Post::active()->withAnyTag(['tutorial'])->get();

// Query posts with ALL of the given tags
$posts = Post::active()->withAllTags(['laravel', 'tutorial'])->get();
```

The Tags module provides polymorphic tag attachment via the `taggable` table.

## Featured image (and gallery)

```php
use Modules\Media\Models\Media;

// Attach a featured image to a post
Media::create([
    'rel_type' => 'content',
    'rel_id'   => $post->id,
    'filename' => '/media/default/post-hero.jpg',
    'media_type' => 'image',
    'position' => 0,
]);

// Featured image accessor (returns first media row)
echo $post->image;   // /media/default/post-hero.jpg

// Full gallery
$gallery = $post->media()->get();
```

## Querying posts

```php
use Modules\Post\Models\Post;

// All active posts, newest first
$posts = Post::active()
    ->orderBy('posted_at', 'desc')
    ->paginate(10);

// In a specific blog (parent page)
$posts = Post::active()
    ->where('parent', $blogId)
    ->orderBy('posted_at', 'desc')
    ->get();

// By keyword
$posts = Post::active()
    ->where(function ($q) use ($keyword) {
        $q->where('title', 'like', "%$keyword%")
          ->orWhere('content_body', 'like', "%$keyword%");
    })
    ->get();

// By author
$posts = Post::active()
    ->where('created_by', $userId)
    ->get();
```

Bypass the global PostScope (rare):

```php
Post::withoutGlobalScope(\Modules\Content\Scopes\PostScope::class)->get();
```

## SEO metadata

All Content SEO fields work the same way they do for pages:

```php
$post->update([
    'content_meta_title'       => 'How to Build a CMS Theme in 2026',
    'content_meta_description' => 'Walkthrough of building a Microweber template from scratch.',
    'content_meta_keywords'    => 'microweber, theme, tutorial',
    'og_title'                 => 'Build a CMS Theme in 2026',
    'og_image'                 => '/media/default/og-theme-tutorial.jpg',
    'canonical_url'            => 'https://yoursite.com/blog/build-a-theme-2026',
]);
```

## Rendering links

```html
<a href="{{ $post->link }}">{{ $post->title }}</a>
<a href="{{ $post->editLink() }}">Edit in admin</a>
<a href="{{ $post->liveEditLink() }}">Edit in Live Edit</a>
```

`$post->link` returns the canonical public URL via `content_link($post->id)`.

## Hooking into save events

```php
use Modules\Post\Models\Post;

Post::saving(function (Post $post) {
    if (! $post->url) {
        $post->url = \Str::slug($post->title);
    }
    if (! $post->posted_at) {
        $post->posted_at = now();
    }
});
```

## RSS feed integration

Microweber generates RSS via the existing `/rss` route, which reads from the Content module's RSS service. Posts with `is_active = 1` and `posted_at <= now()` are included automatically. To exclude a post from RSS, gate via custom data:

```php
$post->setContentDataByFieldName('rss_exclude', '1');
```

(Templates check this field when generating the feed.)

## Test factory

```php
use Modules\Post\Models\Post;

$post = Post::factory()->create();
Post::factory()->count(20)->create();

Post::factory()->create([
    'parent' => $blogPageId,
    'is_active' => 1,
    'posted_at' => now(),
]);
```

Factory lives at `Modules/Post/Database/Factories/PostFactory.php`.

## Filament admin

`Modules\Post\Filament\Resources\PostResource` provides:

- Index page with date sort, status filter (active/inactive), category filter, search
- Create/edit forms with rich-text editor, featured image picker, category multi-select, tag input, parent page picker, SEO panel
- Bulk publish / unpublish actions
- Soft-delete with restore

Related pages:

- `Modules\Post\Filament\Resources\PostResource\Pages\CreatePost`
- `Modules\Post\Filament\Resources\PostResource\Pages\EditPost`
