# Media Module — Examples

## Recipe 1: Upload + attach to a post in one shot

```php
use Modules\Media\Models\Media;
use Modules\Post\Models\Post;
use Illuminate\Support\Facades\Storage;

$post = Post::create([
    'title' => 'New Article',
    'is_active' => 1,
]);

$file = $request->file('hero_image');
$path = $file->storePubliclyAs(
    'uploads/' . date('Y/m'),
    \Str::uuid() . '.' . $file->getClientOriginalExtension(),
    'userfiles'
);

Media::create([
    'rel_type'   => 'content',
    'rel_id'     => $post->id,
    'filename'   => '/userfiles/' . $path,
    'media_type' => 'image',
    'position'   => 0,
    'created_by' => auth()->id(),
]);
```

## Recipe 2: Multi-image gallery upload

```php
foreach ($request->file('photos', []) as $i => $file) {
    $path = $file->storePubliclyAs(
        'gallery/' . $post->id,
        \Str::uuid() . '.' . $file->getClientOriginalExtension(),
        'userfiles'
    );
    Media::create([
        'rel_type'   => 'content',
        'rel_id'     => $post->id,
        'filename'   => '/userfiles/' . $path,
        'media_type' => 'image',
        'position'   => $i,
        'created_by' => auth()->id(),
    ]);
}
```

## Recipe 3: Render a gallery in Blade

```html
@php($gallery = $post->media()->where('media_type', 'image')->orderBy('position')->get())

<div class="gallery">
    @foreach($gallery as $img)
        <a href="{{ $img->url }}" data-fancybox="post-{{ $post->id }}">
            {!! responsive_thumbnail($img->filename, 400, 300, [
                'alt' => $img->title ?: 'Photo ' . ($loop->iteration),
                'class' => 'gallery-thumb',
            ]) !!}
        </a>
    @endforeach
</div>
```

## Recipe 4: Custom thumbnail size with crop

```php
// Square 200x200 crop
$square = thumbnail($filename, 200, 200, true);

// 800-wide, height auto
$wide = thumbnail($filename, 800);

// In a controller, return JSON for an API consumer
return response()->json([
    'thumbs' => [
        '200' => thumbnail($filename, 200, 200, true),
        '400' => thumbnail($filename, 400, 400, true),
        '800' => thumbnail($filename, 800, 800, true),
    ],
]);
```

## Recipe 5: Bulk migrate filenames to a new CDN URL

```php
use Modules\Media\Models\Media;

// Rewrite filename prefix on all rows
Media::where('filename', 'like', '/userfiles/%')
    ->chunkById(100, function ($rows) {
        foreach ($rows as $m) {
            $m->filename = preg_replace(
                '|^/userfiles/|',
                '/media/',
                $m->filename
            );
            $m->save();
        }
    });
```

Run inside a backed-up dev environment first — `update` queries on `media` can be huge in production.

## Recipe 6: Move all media in a folder to S3

```php
use Illuminate\Support\Facades\Storage;
use Modules\Media\Models\Media;

$local = Storage::disk('userfiles_local');
$s3 = Storage::disk('userfiles_s3');

Media::inFolder($folderId)->chunkById(50, function ($rows) use ($local, $s3) {
    foreach ($rows as $m) {
        $relative = ltrim(parse_url($m->filename, PHP_URL_PATH), '/');
        if (! $local->exists($relative)) continue;
        $s3->put($relative, $local->get($relative), 'public');
        // Optionally: $local->delete($relative);
    }
});
```

## Recipe 7: Detect orphan media (no attached content)

```php
use Modules\Media\Models\Media;
use Modules\Content\Models\Content;

$orphans = Media::where('rel_type', 'content')
    ->whereNotIn('rel_id', function ($q) {
        $q->select('id')->from('content')->where('is_deleted', 0);
    })
    ->get(['id', 'filename', 'rel_id']);

echo "Found {$orphans->count()} orphan media rows.\n";
foreach ($orphans as $m) {
    echo "  - {$m->filename} (was attached to deleted content #{$m->rel_id})\n";
}
```

## Recipe 8: Clear the thumbnail cache for a single file

```php
use Modules\Media\Models\MediaThumbnail;
use Illuminate\Support\Facades\Storage;

$mediaId = 87;
$thumbs = MediaThumbnail::where('media_id', $mediaId)->get();
foreach ($thumbs as $thumb) {
    Storage::disk('userfiles')->delete(ltrim($thumb->filename, '/'));
}
MediaThumbnail::where('media_id', $mediaId)->delete();
```

## Recipe 9: Featured-image fallback

```php
$post = \Modules\Post\Models\Post::find($id);
$featured = $post->mediaUrl();  // returns pixum() placeholder if no media attached
echo responsive_thumbnail($featured, 1200, 600, ['alt' => $post->title]);
```

`mediaUrl()` is the safe accessor — never returns null. If you need to know whether a real image is attached:

```php
if ($post->media()->exists()) {
    echo responsive_thumbnail($post->mediaUrl(), 1200, 600);
} else {
    // Show a different layout when there's no hero image
}
```

## Recipe 10: REST API list + filter

```bash
TOKEN=$(curl -s -X POST https://yoursite.com/api/login \
    -H "Content-Type: application/json" \
    -d '{"email":"admin@yoursite.com","password":"…"}' | jq -r .token)

# All images for a post
curl -H "Authorization: Bearer $TOKEN" \
    "https://yoursite.com/api/media?rel_type=content&rel_id=42&media_type=image" | jq .

# All videos uploaded this month
curl -H "Authorization: Bearer $TOKEN" \
    "https://yoursite.com/api/media?media_type=video&order_by=created_at&order=desc&limit=50" | jq .
```
