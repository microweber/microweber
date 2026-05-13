# MediaLibrary Module — Troubleshooting

## Page is empty / "No files in this folder"

1. **Wrong folder selected?** Check the breadcrumb at the top of the canvas. Click "Root" to see all top-level files.
2. **Active search filter?** Clear the search input (×) — files outside the search hits won't show.
3. **`media_folder_id` mismatch?** Files uploaded via the REST API without a `media_folder_id` show up under the root. Files inserted via direct SQL with a stale `media_folder_id` may point at a deleted folder.
4. **DB has rows but UI doesn't show them?** Filament caches Livewire component state — `php artisan filament:cache-components && php artisan view:clear`.

## Upload fails silently

1. **413 Payload Too Large** — check server-side limits (Nginx `client_max_body_size`, PHP `upload_max_filesize`, PHP `post_max_size`).
2. **Disk full** — `df -h public/userfiles` — uploads silently fail when the disk is full.
3. **Permissions** — `chown -R www-data:www-data public/userfiles && chmod -R u+w public/userfiles`.
4. **MIME mismatch** — the Media module rejects files whose MIME doesn't match `allowed_extensions`. Inspect: `php artisan tinker` → `get_option('allowed_extensions', 'media')`.

## Drag-and-drop reorder isn't saving

1. **Network failure** — open browser devtools, watch the request to the Livewire endpoint. A 419 (CSRF) usually means the session expired; refresh.
2. **Concurrent edits** — if two operators drag the same folder at the same time, last-write-wins; reload to see actual state.

## Detail panel doesn't open when clicking a file

1. **Livewire JS not loaded?** Check the page source for `<script src="...livewire.js">`. Filament includes Livewire automatically — if missing, your panel registration is broken.
2. **Console error** — open devtools console. A common error is "Alpine.js component already initialized" — usually fixed by `php artisan filament:cache-components`.
3. **Custom subclass overriding `selectMedia()` incorrectly** — subclasses MUST call `parent::selectMedia($id)` or implement the full panel-open contract.

## Unsplash tab shows "API key not configured"

Set `UNSPLASH_ACCESS_KEY` in `.env` and run:

```bash
php artisan config:clear
```

The Unsplash service reads via Laravel's `env()` on first call; cached config breaks the lookup until cleared.

## Unsplash search returns 0 results despite typing valid terms

1. **Rate limit hit** — Unsplash free tier allows 50 requests/hour. Check response headers for `X-Ratelimit-Remaining: 0`.
2. **Wrong API key** — try the same key against `https://api.unsplash.com/photos/random?client_id=YOUR_KEY` from curl. 401 means the key is wrong; 403 means the app isn't approved for production access yet (default for new apps).
3. **Network egress blocked** — check the server can reach `api.unsplash.com` (some hosting providers block outbound HTTP by default).

## Unsplash download succeeds but attribution missing

The download flow stores `unsplash_photographer` + `unsplash_url` in `content_data` on the new media row. If your template doesn't render them, the attribution won't appear publicly even though it's in the DB.

```php
$media = \Modules\Media\Models\Media::find($mediaId);
$photographer = $media->getContentDataByFieldName('unsplash_photographer');
$url = $media->getContentDataByFieldName('unsplash_url');

if (! $photographer) {
    // Attribution missing — check Unsplash response logging on download
}
```

If `unsplash_photographer` is empty even after download, the Unsplash API response shape may have changed; check `Modules/MediaLibrary/Support/Unsplash.php::download()` for the JSON parsing.

## "Move to folder" fails on large bulk selections

Filament limits bulk-action payloads. For >100 files, run via tinker or a queued job:

```php
\Modules\Media\Models\Media::whereIn('id', $mediaIds)
    ->update(['media_folder_id' => $targetFolderId]);
```

The browser will reflect the move on next page load.

## Picker modal opens but selecting a file does nothing

1. **Parent form not listening for `selected.media`** — verify the parent's `#[On('selected.media')]` attribute or `wire:on` binding.
2. **Picker view called without `multiselect` prop** — defaults vary by Filament version; pass it explicitly.

## Folder tree shows "0" badge for a folder that has files

The folder count comes from `getFolderCountsProperty()` which runs a `GROUP BY media_folder_id` query. If you've just inserted rows via direct SQL, the count may be cached for the current request lifecycle — refresh the page.

## CSS layout broken in picker mode

The picker mode reuses the full-page template but hides the sidebar via CSS. If your custom theme overrides `.fi-page` or `.fi-modal-content` margins, the picker grid may overflow.

Inspect with devtools. Fix by adding scoped overrides:

```css
.fi-modal-content .media-library-grid {
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
}
```

## Where to file bugs

- MediaLibrary module: `Modules/MediaLibrary/`. Tests in `Modules/MediaLibrary/Tests/`.
- Data-layer bugs (upload validation, thumbnail generation, CDN, storage adapters) belong against the **Media** module — see [`docs/modules/media/troubleshooting.md`](../media/troubleshooting.md).
- Filament-platform bugs (the page renders blank, Livewire wire chains broken) belong upstream.
