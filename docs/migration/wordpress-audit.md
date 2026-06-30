# WordPress Migration — audit of existing import plumbing

> **Scope of this audit:** Phase-1 deliverable from the Easy WordPress
> Migration plan in `TODO.md`. The goal is to *not* invent a parallel
> insert stack for WordPress content and instead map every importer
> surface we need onto code that already ships and is already covered
> by tests. This document lists what exists, what it does, and which
> seam the WordPress migrator should plug into. It does **not**
> implement any of the importers — those are Phase 3–6 tasks.

The WP plan originally hinted at `MicroweberPackages\Import\*` and
`ContentImport` as reuse targets. Neither exists under those names —
the equivalent code lives under `Modules/Restore`, `Modules/Backup`,
`Modules/Content`, `Modules/Media`, `Modules/Sitemap` and
`Modules/RssFeed`. The table below is the authoritative remap.

---

## 1. The content-write pipeline (the part we MUST reuse)

### `save_content($data)` — `Modules/Content/Support/helpers.php:271`

Top-level free function. Every insert/update of a `content` row (pages,
posts, products) in the rest of the codebase goes through this — it's
the single seam that fires `content.manager.before.save` /
`content.manager.after.save` events, resolves the `add_content_to_menu`
side-effect, and clears the model cache.

**Implementation:** `ContentManager::save_content()` at
`Modules/Content/Services/ContentManager.php:1050`.

**Why the WP migrator must use this:** any direct `DB::insert` or
`Content::create()` bypasses every downstream consumer that listens for
`content.manager.after.save` — sitemap invalidation, search reindex,
multilanguage fallbacks, and whatever a module installs a listener
for. A migrator that writes rows directly is silently-broken the
moment a module adds a post-save hook.

### `DatabaseSave::save($table, $data)` — `Modules/Restore/DatabaseSave.php:249`

Thin wrapper around `db_save($table, $data)` with
`skip_cache=true, allow_html=true, allow_scripts=true` set. Used by
the Restore pipeline for every row that isn't a `content` row
(categories, media, custom fields, etc.). The WP migrator should use
the *same* wrapper (not raw `db_save`) so any future hardening to the
wrapper — e.g. a "source=wordpress" tag column — lands in one place.

### `DatabaseSaveContent::save($table, $tableData)` — `Modules/Restore/DatabaseSaveContent.php:17`

The Restore pipeline's `content`-specific wrapper — sets
`skip_cache=true, allow_html=true, allow_scripts=true, extended_save=true`
and resolves a parent page id (`Blog` / `Shop`) when the input doesn't
have one. **This is the exact behaviour the WP importer needs** when
dropping WP posts → Microweber posts / WP pages → Microweber pages.
The migrator should call this, not build its own page-resolution
logic.

### `DatabaseSave::savePost($postData)` / `saveProduct()` — `Modules/Restore/DatabaseSave.php:19, 80`

Higher-level shortcut the Restore module uses for structured post /
product payloads (title, content_body, categories, first_level_categories,
pictures, content_data, variants). The WP importer's `MigrationItemDTO`
should be shaped to be a drop-in for this method's input — then a
WP post import is one call per item with zero custom insert code.

### `DatabaseSave::getOrInsertCategories($categories, $parentPageId)` — `Modules/Restore/DatabaseSave.php:211`

Nested-category resolver: takes a list of category names + a parent
page id, walks up the tree creating rows that don't exist, returns
the resulting leaf category ids. Exactly the semantics WP
categories/tags need — no WP-specific taxonomy code required.

### `DatabaseSave::downloadAndSaveMedia($imageUrl, $contentId)` — `Modules/Restore/DatabaseSave.php:171`

Takes a remote image URL, downloads via `app()->http->url()->download()`,
validates with `\MicroweberPackages\Utils\System\Files::is_allowed_file()`,
moves into `media_uploads_path()` with a content-hashed filename
(`md5($imageUrl)`), and attaches to the target content via
`app()->media_manager->save([...])`. Supports arrays of URLs with a
recursive call.

**Reuse verdict:** this is the media-rehosting primitive for Phase 7
of the plan. The WP importer's `MediaRehoster` shouldn't reimplement
download/move/attach — it should be a thin layer that walks imported
HTML, collects `<img src>` + `<a href>` URLs, and delegates each to
this method.

### `app()->media_manager->save($data)` — `Modules/Media/Repositories/MediaManager.php:386`

The media-attachment seam `downloadAndSaveMedia` ends up calling.
Accepts `rel_id`, `rel_type` (or `for` + `for-id` aliases),
`media_type`, `filename`/`src`. Handles HTML-clean + xss-clean on the
payload. Any media attach the WP migrator performs goes through here.

---

## 2. The readers we can reuse

### `Modules/Restore/Restore.php` — the restore orchestrator

`Restore::start()` pipeline:

1. `SessionStepper` — progress tracking across polled requests
   (`Modules/Backup/SessionStepper.php`). The Filament admin UX in the
   migration plan's Phase 9 can poll the same class for "processed /
   total / ETA".
2. `readContent()` → `restoreAsType($file)` → a format-specific reader
   based on the file extension (json/xml/csv/xlsx/zip).
3. `DatabaseWriter::runWriter()` or `runWriterWithBatch()` — mirrors
   exactly the preview-then-commit shape the WP plan's Phase 8 needs
   (see §3).

**Reuse verdict:** the WP migrator's job-runner shouldn't invent a new
orchestrator. It should *extend* `Restore` (or compose with it) so
`setFile($wxrPath)` + `start()` becomes the WXR import path for
Phase 6 almost for free.

### `Modules/Restore/Formats/XmlReader.php:1` — minimal XML reader

Loads a file via `simplexml_load_file()` and json-re-encodes to an
assoc array. Fine for small files; **not suitable for WXR** exports
(WordPress WXR exports from a busy site are routinely 100–500 MB).
Phase-6 of the plan correctly calls out streaming via `XMLReader`.

**Reuse verdict:** keep the class (it covers Microweber-format XML
exports) and add a sibling `WxrReader` using streaming `XMLReader`.
Don't retrofit simplexml onto large WXR files — the OOM risk is real.

### `Modules/Restore/Formats/{Json,Csv,Xlsx,Zip}Reader.php` — other formats

Not directly reusable for WP (WP doesn't emit JSON/CSV/XLSX content
dumps), but the `DefaultReader` base class
(`Modules/Restore/Formats/DefaultReader.php:1`) and the reader-
interface contract (each implements `readData()`) *is* the shape
Phase 3-6 importers should follow so they compose with
`Restore::setFile()`.

### `Modules/RssFeed/` — *emitter only, not a reader*

`RssController::index()` in `Modules/RssFeed/Http/Controllers/RssController.php:17`
publishes `/feed` in either Atom or the WordPress-WXR-flavored format
(the `wordpress.blade.php` view). **This is a producer, not a
consumer.** Phase 3 of the WP plan (RSS reader) needs new code — no
existing parser to reuse. Recommended: `laminas/laminas-feed` is
already a transitive dep of various Laravel mail packages; confirm
during Phase-3 implementation and prefer it over hand-rolling
`simplexml`.

### `Modules/Sitemap/` — *emitter only, not a reader*

`SitemapController` publishes `/sitemap.xml`, `/categories_sitemap.xml`,
`/tags_sitemap.xml`, `/posts_sitemap.xml`, `/products_sitemap.xml`.
All output paths. **Phase 4 (sitemap importer) has no existing reader
to reuse**; build one under `Modules/WordPressMigration/Readers/`.

### `app()->http` — `src/MicroweberPackages/Utils/Http/Http.php`

Unified HTTP facade used by `downloadAndSaveMedia` and everywhere
else in the codebase that talks to a remote server. Methods:
`get($params)`, `post($params)`, `download($saveTo, $postParams)`.

**Reuse verdict:** every remote fetch the WP migrator does —
`wp-json` probes, feed fetches, sitemap fetches, readability scrapes,
media downloads — must go through `app()->http`. That gives us:
- one place to adjust SSL verification (`SslVerificationTest` already
  covers the adapter);
- one place to plumb rate-limit / retry policy;
- telemetry and proxy-config parity with the rest of the stack.

---

## 3. Preview-before-commit: the shape is already there

`Modules/Restore/DatabaseWriter.php:28` uses per-entity writer traits
(`DatabaseContentWriter`, `DatabaseMediaWriter`, etc.) and exposes
both `runWriter()` and `runWriterWithBatch()`.

Phase 8 of the plan ("staging → preview → commit") can be implemented
as:

- Add a `writeOnDatabase=false` branch (the plumbing is already there
  at `Restore.php:201`) that routes writes into
  `wp_migration_staging_*` tables via a sibling `StagingDatabaseWriter`
  that re-uses the same per-entity trait set.
- `Commit` button promotes staged rows by wrapping an existing
  `runWriter()` call in a transaction.

**No new orchestrator required.** Reuse `Restore`'s dry-run shape.

---

## 4. What does NOT exist and must be built

Every item on this list is covered by a Phase-3+ bullet in `TODO.md`
already — this section just makes the gaps explicit so a Phase-1
reader doesn't assume any of these can be grepped.

- **RSS/Atom reader.** `Modules/RssFeed` is an emitter. Build
  `Modules/WordPressMigration/Readers/RssFeedReader.php` against the
  `DefaultReader` contract.
- **Sitemap XML reader.** `Modules/Sitemap` is an emitter. Build
  `Modules/WordPressMigration/Readers/SitemapReader.php` with support
  for `sitemap_index.xml` nesting (Yoast / RankMath / AIOSEO flavors).
- **WP REST API client.** No existing WP REST client. Build
  `Modules/WordPressMigration/Clients/WpRestClient.php` on top of
  `app()->http`.
- **WXR streaming parser.** `XmlReader` is simplexml-only; unsafe for
  large WXR. Build `Modules/WordPressMigration/Readers/WxrReader.php`
  using `\XMLReader` with a cursor-style API over `<item>` nodes.
- **Readability / DOM-extraction pass.** No existing extractor; pull
  in `fivefilters/readability.php` during Phase 4 when the sitemap
  reader needs body extraction.
- **HTML rewriter.** No existing URL-rewriter; `Modules/Content`'s
  content-save path xss-cleans but doesn't rewrite asset URLs. Build
  `Modules/WordPressMigration/Services/HtmlMediaRewriter.php`.

---

## 5. Seam summary — one table

| WP migrator need            | Existing seam to call                                                                 | New code (Phase) |
|-----------------------------|---------------------------------------------------------------------------------------|------------------|
| Insert a WP post/page       | `DatabaseSaveContent::save('content', $row)` (§1)                                     | no — reuse       |
| Insert a structured post    | `DatabaseSave::savePost($dto)` (§1)                                                   | no — reuse       |
| Insert WP categories/tags   | `DatabaseSave::getOrInsertCategories($names, $parentPageId)` (§1)                     | no — reuse       |
| Rehost a media URL          | `DatabaseSave::downloadAndSaveMedia($url, $contentId)` (§1)                           | no — reuse       |
| Attach media to content     | `app()->media_manager->save([...])` (§1)                                               | no — reuse       |
| Fetch remote HTTP           | `app()->http->url($url)->get()` / `->download($path)` (§2)                             | no — reuse       |
| Orchestrate a job           | `Restore::setFile()` + `Restore::start()` (§2)                                        | no — reuse       |
| Track per-step progress     | `Modules\Backup\SessionStepper` (§2)                                                  | no — reuse       |
| Stage-then-commit writes    | `Restore::writeOnDatabase=false` branch + per-trait writers (§3)                      | thin extension   |
| Read an RSS/Atom feed       | —                                                                                     | Phase 3          |
| Read a sitemap.xml tree     | —                                                                                     | Phase 4          |
| Hit the WP REST API         | —                                                                                     | Phase 5          |
| Stream a large WXR export   | —                                                                                     | Phase 6          |
| Rewrite asset URLs in HTML  | —                                                                                     | Phase 7          |

---

## 6. Implementation note for Phase-3 onward

Put every new importer under `Modules/WordPressMigration/` (new
module) and keep its `Readers/` siblings shaped to the
`DefaultReader` contract from `Modules\Restore\Formats\DefaultReader`.
That way a shipped `.wxr` file can be imported with exactly the
existing Restore admin UX (upload → preview → commit) by registering
`wxr` as a restore-supported extension, and the only genuinely new
surface area is the REST/RSS/sitemap URL-driven path — which still
terminates in the same `save_content()` / `DatabaseSave::*` calls this
audit catalogs above.

This audit is the reuse contract for Phase-3 implementers: if a new
importer calls `DB::insert` or `Content::create` directly, it's
skipping something in this document and the review should push back.
