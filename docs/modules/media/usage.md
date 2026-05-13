# Media Module — Usage

Day-to-day patterns for uploading, attaching, querying, and rendering media.

## Uploading a file

The simplest path is the REST API:

```bash
curl -X POST https://yoursite.com/api/media \
    -H "Authorization: Bearer $TOKEN" \
    -F "file=@/path/to/photo.jpg" \
    -F "rel_type=content" \
    -F "rel_id=42"
```

Programmatic upload (e.g. from a queued job):

```php
use Illuminate\Support\Facades\Storage;
use Modules\Media\Models\Media;

$disk = Storage::disk('userfiles');
$path = $disk->putFileAs('uploads/' . date('Y/m'), $request->file('photo'), $generatedName);

$media = Media::create([
    'rel_type'   => 'content',
    'rel_id'     => $contentId,
    'filename'   => '/userfiles/' . $path,
    'media_type' => 'image',
    'position'   => 0,
    'created_by' => auth()->id(),
]);
```

## Attaching media to a Content row

```php
use Modules\Post\Models\Post;
use Modules\Media\Models\Media;

$post = Post::find($id);

// Attach a single image
Media::create([
    'rel_type'   => 'content',
    'rel_id'     => $post->id,
    'filename'   => '/media/default/hero.jpg',
    'media_type' => 'image',
    'position'   => 0,
]);

// Read the featured image via the Content accessor
echo $post->image;  // /media/default/hero.jpg

// Read the full gallery
$gallery = $post->media()->orderBy('position')->get();
```

`$post->image` returns the first media row's filename. `$post->media()` is the full hasMany relation.

## Removing media

```php
use Modules\Media\Models\Media;

// Detach (delete row, leave file on disk)
Media::find($mediaId)->delete();

// Detach + remove the file
$media = Media::find($mediaId);
\Storage::disk('userfiles')->delete($media->filename);
$media->delete();
```

The standard delete event fires (`Media::deleted`) — listeners can clean up CDN copies or audit log.

## Generating thumbnails

The canonical helpers:

```php
// Just the URL of a resized thumbnail (cached on first call)
echo thumbnail('/media/default/hero.jpg', 400, 300);
// → /userfiles/cache/thumbnails/abc123-400x300.jpg

// With explicit crop mode
echo thumbnail('/media/default/hero.jpg', 400, 300, true);   // crop to exact dims
echo thumbnail('/media/default/hero.jpg', 400, 300, false);  // resize, preserve aspect ratio

// Full <img> tag with srcset + sizes + alt + loading="lazy" + decoding="async"
echo responsive_thumbnail('/media/default/hero.jpg', 800, 600, [
    'alt'   => 'Hero photo',
    'class' => 'w-100 h-auto',
    'sizes' => '(max-width: 575.98px) 100vw, 50vw',
]);
```

`responsive_thumbnail()` is the helper introduced in PM TASK-012 / TICKET-CX — it generates the 1x + 2x variants automatically and applies the AI-115 eager-first-N pattern (the first 2 calls per request render `loading="eager"`, the rest `loading="lazy"`).

See `Modules/Media/Support/media_functions.php:209` for the full signature.

## Placeholder image

```php
// SVG placeholder of given dimensions and configured pixum_color
echo pixum(400, 300);
// → data: URI or short SVG file URL
```

Used as a fallback when no media is attached (`Content::mediaUrl()` calls `pixum()` as the empty default).

## Querying media

```php
use Modules\Media\Models\Media;

// All media for a content row
$attachments = Media::where('rel_type', 'content')
    ->where('rel_id', $contentId)
    ->orderBy('position')
    ->get();

// All images uploaded this month
$recent = Media::where('media_type', 'image')
    ->where('created_at', '>=', now()->startOfMonth())
    ->orderByDesc('created_at')
    ->paginate(50);

// Scope: in a folder
$folder = Media::inFolder($folderId)->get();
$rootLevel = Media::inFolder(null)->get();

// Scope: on CDN
$cdnImages = Media::onCdn()->where('media_type', 'image')->get();

// Scope: by type
$videos = Media::byType('video')->get();
```

## Folders

```php
use Modules\Media\Models\MediaFolder;

// Create
$folder = MediaFolder::create([
    'name'      => 'Product Photos 2026',
    'parent_id' => $parentFolderId ?? null,
]);

// Read tree (children of root)
$rootFolders = MediaFolder::whereNull('parent_id')->orderBy('name')->get();

// Move media into folder
Media::find($mediaId)->update(['media_folder_id' => $folder->id]);
```

## Accessors

`Media` model exposes:

```php
$media->url;        // full URL (CDN-prefixed if applicable)
$media->file_type;  // 'image' / 'video' / 'audio' / 'document'
$media->isOnCdn();  // bool
```

## Integration with Content models

`Modules\Media\Traits\MediaTrait` is applied to `Modules\Content\Models\Content`. It provides:

- `media()` — `hasMany` relation
- `thumbnail($width, $height, $crop)` — convenience method returning the first media row's thumbnail URL
- `mediaUrl()` — first media row's filename (or `pixum()` if none)

Usage:

```php
$post = \Modules\Post\Models\Post::find($id);
echo $post->thumbnail(800, 600);  // resized URL of first attached image
echo $post->mediaUrl();           // raw filename of first attached image
```

## Filament admin

The Filament Media admin lives at `Modules\Media\Filament\Resources\MediaResource`. The richer picker UI is in the **MediaLibrary** module (a separate dedicated picker modal used by every form that needs a file).

## Cache + CDN

- Thumbnails are cached in `public/userfiles/cache/thumbnails/`. Clear with `php artisan cache:clear` (the per-thumbnail cache also clears on the source `Media::deleted` event).
- CDN URLs are prefixed live via the `getUrlAttribute` accessor — no DB migration needed when toggling CDN on/off.
