# Media Module — Troubleshooting

## Upload fails with "The file failed to upload" or 413 Payload Too Large

PHP-side limits (in `php.ini`):

- `upload_max_filesize = 50M`
- `post_max_size = 50M`
- `max_execution_time = 300`

After editing, restart PHP-FPM / Apache. The `media.max_upload_mb` option ALSO caps; even if PHP allows 50M, Microweber will reject above the configured option.

If Nginx is in front, also set:

```nginx
client_max_body_size 50M;
```

## Thumbnail generation throws "Class Imagick not found"

The image-processing layer requires either GD or Imagick. Check:

```bash
php -m | grep -iE 'gd|imagick'
```

Install:

```bash
# Ubuntu
sudo apt install php8.2-gd php8.2-imagick
sudo systemctl restart php8.2-fpm

# Alpine
apk add php82-gd php82-pecl-imagick
```

WebP support requires GD compiled with `--with-webp` or Imagick built against ImageMagick ≥ 6.8.

## Thumbnails cached but stale after image replaced on disk

`thumbnail()` doesn't re-check the source file's mtime — it returns the cached thumbnail URL as long as the cache file exists.

Workaround:

```php
use Modules\Media\Models\MediaThumbnail;
use Illuminate\Support\Facades\Storage;

$thumbs = MediaThumbnail::where('media_id', $mediaId)->get();
foreach ($thumbs as $t) {
    Storage::disk('userfiles')->delete(ltrim($t->filename, '/'));
}
MediaThumbnail::where('media_id', $mediaId)->delete();
```

The next `thumbnail()` call will regenerate.

## CDN-prefixed URL returns 404

1. **CDN doesn't have the file yet** — most CDNs pull on first request, but if the source URL is unreachable, the CDN never caches. Verify the file exists at the origin: `curl -I https://origin.yoursite.com/userfiles/path/to/file.jpg`.
2. **Cache key includes query string** — some CDNs treat `?v=2` as a different file. Strip query strings or invalidate the CDN cache.
3. **Wrong CDN URL in `.env`** — `MEDIA_CDN_URL` should NOT end with a trailing slash; the accessor doesn't normalize. Set `MEDIA_CDN_URL=https://cdn.yoursite.com`.

## Media attached but `$post->image` returns empty / placeholder

Checklist:

1. **Media row's `rel_type` correct?** Should be `'content'`, not `'Modules\Post\Models\Post'` or any class string. Inspect: `\DB::table('media')->where('rel_id', $post->id)->pluck('rel_type')`.
2. **Media row's `rel_id` matches the post id?** Easy to mismatch when copying rows.
3. **`media_type = 'image'`?** The `image` accessor filters to images.
4. **`position` set?** The accessor returns the LOWEST `position` row — non-image rows with `position = 0` might win the lookup if the filter isn't applied.

## `responsive_thumbnail()` renders the wrong loading attribute

The helper uses a request-scoped static counter for the eager-first-N pattern. If you render a list of products and want the first 4 (not 2) to be eager:

```php
echo responsive_thumbnail($filename, 800, 600, [
    'alt' => $title,
    'eager_first_n' => 4,
]);
```

If a SINGLE image should be eager regardless of position:

```php
echo responsive_thumbnail($filename, 800, 600, [
    'alt' => $title,
    'loading' => 'eager',  // explicit override
]);
```

## File uploaded but doesn't appear in admin

1. **`media_folder_id` filter applied in the Filament resource?** Most installs default to "All folders"; tenants may scope to the current folder only.
2. **`is_deleted` column?** Some installs have soft-delete on `media`; the admin only shows non-deleted rows.
3. **Cache:** `php artisan cache:clear && php artisan filament:cache-components`.

## Disk space filling up due to thumbnail cache

The thumbnail cache directory (`public/userfiles/cache/thumbnails/`) grows over time. Manual prune:

```bash
# Delete thumbnails older than 90 days
find public/userfiles/cache/thumbnails/ -type f -mtime +90 -delete
```

And clear the matching `media_thumbnails` rows:

```php
\Modules\Media\Models\MediaThumbnail::where('created_at', '<', now()->subDays(90))->delete();
```

A scheduled job in `app/Console/Kernel.php` is a good fit.

## SVG uploads display as broken images

Some installs ship `allowed_extensions` without `svg`. Update the option:

```sql
UPDATE options
SET option_value = 'jpg,jpeg,png,gif,webp,svg,pdf,mp4,mp3'
WHERE option_key = 'allowed_extensions' AND option_group = 'media';
```

Also note: serving SVG with `Content-Type: image/svg+xml` requires correct MIME handling at the web-server level. SVGs can contain `<script>` — sanitize at upload time if untrusted users can upload.

## Video uploads play but no thumbnail generated

The thumbnail engine targets images. For video posters:

1. Generate a still externally (ffmpeg, Cloudinary, etc.) and upload it as a separate `media_type=image` row
2. OR use the embedded player's `poster` attribute pointing at a manually-uploaded still

Bulk video-poster generation:

```bash
ffmpeg -i input.mp4 -ss 00:00:01.000 -vframes 1 poster.jpg
```

## "Disk userfiles not configured" error after a deploy

`config/filesystems.php` must include the `userfiles` disk. Verify the deploy includes the latest config and run:

```bash
php artisan config:clear
php artisan config:cache
```

## Where to file bugs

- Media module: `Modules/Media/`. Tests live in `Modules/Media/Tests/`.
- Image-processing bugs (GD/Imagick) usually trace to PHP extension config, not Media module code — check `php -m` first.
- CDN/S3 bugs usually trace to filesystem config — check `config/filesystems.php` and the matching `.env` values.
