# WordPress Migration — supported surface & deferred surface

> **Scope of this document:** Phase-1 deliverable #2 from the Easy
> WordPress Migration plan in `TODO.md`. Draws the hard line between
> what a Microweber install will pull from a source WordPress site and
> what it will explicitly **refuse** to pull. Every importer built in
> Phases 3–6 must honor the lists below; anything outside the
> supported surface either no-ops with a migration-log warning or
> fails loud (§6) — it never silently writes half-correct data.
>
> This is a scoping document, not an implementation. Where a decision
> is load-bearing, the reasoning is written down so a Phase-3+
> implementer can judge edge cases that don't fit the table.

Companion documents: `docs/migration/wordpress-audit.md` (reuse map of
existing Microweber seams) and the still-to-write ADR
`docs/adr/wordpress-migration.md` (auth / rate-limit / idempotency).

---

## 1. Target WordPress versions

| Axis                     | Supported                          | Refused                                            |
|--------------------------|------------------------------------|----------------------------------------------------|
| Install type             | self-hosted `wordpress.org` ≥ 5.0  | `wordpress.com` hosted (separate API surface — later) |
| WP core version          | 5.0 or newer                       | 4.x and older                                      |
| PHP on source            | any — we don't execute source PHP  | n/a                                                |
| DB access                | never requested                    | direct `mysqldump` / DB credentials are out of scope |
| Multisite (`WP_NETWORK`) | **single site** per import job     | subsite-switching in one run — user runs N jobs    |

**Why 5.0 as the floor:** 5.0 is when Gutenberg shipped and the REST
API stabilised enough (`wp/v2`) that our REST-mode importer (Phase 5)
can rely on a predictable `_embed` shape and `content.rendered` being
the server's final HTML. Older versions emitted REST responses that
omit `_embed`, forcing per-field secondary fetches; we refuse rather
than ship two code paths.

**Why no `wordpress.com`:** the hosted product exposes `public-api.wordpress.com`,
which is a different auth (OAuth + app-token) and a different payload
shape. It's a viable future importer but doesn't share code with the
self-hosted path — tracked as a deferred follow-up, not Phase 5.

**Why no DB-direct (`mysqldump`):** requires shell/DB access to the
source, couples us to WP's internal schema (which changes across
versions), and leaks PII we don't need. URL + optional app-password
is strictly safer and sufficient for everything in §2.

---

## 2. In-scope surface (what we import)

Every row the WP importer writes lands via the reuse seams catalogued
in `wordpress-audit.md` §5 — never raw inserts.

### 2.1 Posts → Microweber posts

| WP field                  | Microweber target                                             | Notes                                                        |
|---------------------------|---------------------------------------------------------------|--------------------------------------------------------------|
| `post_title`              | `content.title`                                               |                                                              |
| `post_content` (rendered) | `content.content_body`                                        | HTML verbatim after asset-rewrite (§2.4)                     |
| `post_excerpt`            | `content.description`                                         |                                                              |
| `post_name` (slug)        | `content.url`                                                 | Preserve so inbound links resolve without redirect rules     |
| `post_date_gmt`           | `content.created_at`                                          | Stored as UTC                                                |
| `post_modified_gmt`       | `content.updated_at`                                          |                                                              |
| `post_status`             | `content.is_active` (`publish`→1, else 0)                     | `draft`/`pending`/`future` imported as inactive              |
| `post_type=post`          | `content.content_type=post` with parent `Blog` page           | Parent page auto-created via `DatabaseSaveContent` (§ audit) |
| `post_author`             | `content.created_by` after author mapping (§2.5)              |                                                              |
| `featured_media`          | attached via `MediaManager::save` as `media_type=picture`     |                                                              |
| `guid`                    | content meta `wp_source_guid` — idempotency key               | Re-imports update rather than duplicate                      |
| `categories` / `tags`     | §2.3                                                          |                                                              |
| `comments`                | §2.6                                                          |                                                              |

### 2.2 Pages → Microweber pages

| WP field                  | Microweber target                                             | Notes                                                        |
|---------------------------|---------------------------------------------------------------|--------------------------------------------------------------|
| `post_type=page`          | `content.content_type=page` (static, not `is_shop`)           |                                                              |
| `post_parent`             | `content.parent` — resolved in a second pass after all rows   | Keeps parent/child tree; cycles logged + flattened to root   |
| `menu_order`              | `content.position`                                            |                                                              |
| `_wp_page_template`       | stored as content meta `wp_page_template`                     | Not applied — Microweber has its own layout picker (§3.5)    |
| all other fields          | same as §2.1                                                  |                                                              |

### 2.3 Taxonomies — WP categories & tags

| WP concept      | Microweber target                                  | Notes                                                  |
|-----------------|----------------------------------------------------|--------------------------------------------------------|
| `category`      | Microweber category under the `Blog` page          | Nested categories walk via `DatabaseSave::getOrInsertCategories` |
| `post_tag`      | Microweber tag on the target content               | Deduped by name, case-insensitive                      |
| `category` slug | category slug                                      | Preserved verbatim so `/category/foo/` URLs resolve    |
| custom taxonomy | §3.3 — **deferred**                                 |                                                        |

### 2.4 Media

| WP asset               | Microweber target                                   | Notes                                                     |
|------------------------|-----------------------------------------------------|-----------------------------------------------------------|
| `attachment` (image)   | `media` row via `DatabaseSave::downloadAndSaveMedia`| Stored under `userfiles/media/imported/wordpress/<job-id>/` |
| `<img src>` in body    | rehosted + URL rewritten in place                   | Off-site images are left untouched unless user opts in    |
| `<a href>` to uploads  | rehosted + URL rewritten in place                   | Non-upload external links preserved verbatim              |
| video/audio attachment | rehosted if mime is in `is_allowed_file()` whitelist| Otherwise skipped with a log warning                      |
| alt text               | `media.description`                                 |                                                           |
| caption                | `media.title`                                       |                                                           |
| EXIF / image metadata  | dropped                                             | Microweber doesn't store it; round-trip would lose data   |

### 2.5 Authors → Microweber users

| WP field          | Microweber target                     | Notes                                                          |
|-------------------|---------------------------------------|----------------------------------------------------------------|
| `user_email`      | match existing Microweber user by email; else map to admin-configurable default | Email is the only reliable merge key |
| `display_name`    | `users.first_name` / `users.last_name` (split on first space) | Only for newly-created users                             |
| `user_login`      | `users.username`                      | Only for newly-created users; suffixed with `-wp` on collision |
| `user_registered` | `users.created_at`                    |                                                                |
| password          | never imported                        | WP hashes are phpass; we don't translate. New users get a password-reset email on first publish or stay "imported-author-no-login". |

Author resolution is a **three-mode** setting on the migration job:

1. `strict` — require every WP author to exist in Microweber by email;
   missing authors block the job with a manual-map UI.
2. `create-missing` (default) — auto-create users for unmatched
   emails; role = `subscriber` equivalent.
3. `fallback-to-admin` — assign all imported content to the job's
   runner user; log original author in content meta.

### 2.6 Comments

| WP field             | Microweber target                     | Notes                                                     |
|----------------------|---------------------------------------|-----------------------------------------------------------|
| `comment_author`     | `comments.author_name`                |                                                           |
| `comment_author_email` | `comments.author_email`             |                                                           |
| `comment_content`    | `comments.comment` (HTML-safe)        |                                                           |
| `comment_date_gmt`   | `comments.created_at`                 |                                                           |
| `comment_approved`   | `comments.is_active` (`1`→active, others→pending) |                                               |
| `comment_parent`     | `comments.reply_to_comment_id` second pass | Orphaned replies flattened to top-level              |
| `rel_id`/`rel_type`  | polymorph to imported content row     | Missing parent content → comment dropped + logged         |
| spam / trash comments | **dropped, logged** — never imported |                                                           |

### 2.7 Menus & menu items

| WP concept           | Microweber target                     | Notes                                                     |
|----------------------|---------------------------------------|-----------------------------------------------------------|
| `nav_menu` term      | `menus` row                           | Name preserved; slug regenerated                          |
| `nav_menu_item` post | `menu_items` row                      | URL rewritten to imported-content URL when type is `post_type` / `taxonomy` |
| `custom` menu item   | imported verbatim as external link    |                                                           |
| theme-location registration | **deferred** — §3.5             | Microweber theme bindings are template-level              |

---

## 3. Deferred surface (what we refuse to import)

Refused ≠ blocked — Phases 3–6 importers skip these, **always log
what was skipped and why**, and surface the count on the final job
summary. User gets a single "N items of type X were skipped" line per
deferral bucket below.

### 3.1 Plugins

Source plugins are skipped wholesale. Rationale:

- A plugin is PHP code; Microweber has its own module system with
  different extension points.
- The plugin's data may live in `wp_options`, `wp_postmeta`, or
  custom tables; translating it requires a plugin-specific migrator
  we're not building.
- Even where the data exists (e.g. WooCommerce products), its shape
  doesn't map 1:1 onto Microweber's shop schema — a one-shot importer
  would produce incomplete catalogs.

**Escape hatch for Phase-N:** a plugin-specific migrator
(`WooCommerceMigrator`, `YoastSeoMigrator`) can compose with the
core WP importer by consuming the same raw feed/REST payload and
writing into its own Microweber-side target. Not on this phase's
path.

### 3.2 Widgets / sidebars

`widget_*` options, classic-theme sidebars, FSE template parts, and
block-pattern registrations are all skipped. Microweber modules are
a different placement model; no stable mapping exists.

### 3.3 Custom post types & custom taxonomies

Only `post` and `page` are imported. Any `register_post_type()` or
`register_taxonomy()` registration the source uses is skipped.
Rationale:

- WP CPTs are arbitrary-shaped; a generic mapper produces content
  rows that look correct in the DB but have no frontend rendering
  path in Microweber.
- Built-in "book", "movie", "event", etc. plugin CPTs belong to
  plugin-specific migrators (§3.1).

**Generic mapper is deferred**, not impossible — tracked as a
post-Phase-11 follow-up; a CLI-only `--map-cpt=<name>=<microweber-type>`
flag is the minimum viable shape.

### 3.4 Gutenberg block HTML — *fidelity, not presence*

We import `content.rendered` (the server-rendered HTML output of the
block tree), **not** `content.raw` or the `<!-- wp:block -->` comments
that drive the editor. Consequences:

- The imported post renders identically to the source when viewed on
  the frontend.
- It is **not** re-editable as Gutenberg blocks in Microweber's live
  editor — it comes in as a single HTML blob.
- Block-level features that depend on the editor's runtime (reusable
  blocks, synced patterns, query loops driven by the block) **freeze
  at import time**. Changes to the source reusable block after import
  never propagate.

This is a deliberate trade: block-comment-accurate round-tripping
would require shipping a Gutenberg block parser + a renderer pass
that re-emits Microweber's own editor structure, which is a
year-long project. Shipping the rendered HTML covers 95% of
migration intent (reading/displaying the site) immediately.

### 3.5 Theme & design

Themes, child themes, `style.css`, FSE templates, theme-mods
(`theme_mod_*` options), customizer settings, theme locations for
menus — **all skipped**. User picks a Microweber template
post-import. Rationale: WP themes are PHP templates; they don't
translate to Microweber's Blade layouts, and running the source's
PHP in our process is a non-starter.

### 3.6 Users' private data

Never imported:

- passwords (§2.5)
- session tokens (`wp_usermeta` session keys)
- two-factor secrets
- user-level options / application-passwords
- `user_activation_key`

### 3.7 Plugin-managed data of note (explicit call-out list)

Skipped, with a one-line migration-log entry per bucket so the user
knows they were seen:

- WooCommerce orders, customers, coupons, shipping zones — deferred
- ACF field groups, Pods, CPT UI — deferred (ties into §3.3)
- Yoast/RankMath SEO meta — deferred (tracked under a Phase-N "SEO
  meta importer" that reads `_yoast_wpseo_*` meta and maps to
  Microweber's `meta_title` / `meta_description` fields on
  `content`)
- Jetpack stats, Analytics codes — dropped
- Contact Form 7 / Gravity Forms form definitions & entries —
  dropped (Microweber has its own ContactForm module; shapes don't
  match)
- Multilingual plugins (WPML, Polylang): **refused** for Phase 5;
  importing translated content from these requires language-aware
  CPT handling. Deferred. Mono-language sites work out of the box.

### 3.8 Server-side concerns

Never imported / never touched on the source:

- `.htaccess` / nginx rewrite rules
- `wp-config.php`
- cron jobs (`wp-cron` and real cron)
- file uploads outside the `wp-content/uploads/` tree
- anything in `wp-content/plugins/` or `wp-content/themes/`

---

## 4. What happens at the edges

| Situation                                             | Behaviour                                                            |
|-------------------------------------------------------|----------------------------------------------------------------------|
| Source is WP < 5.0                                    | Probe refuses the job up front; user sees "Microweber requires source WP ≥ 5.0" |
| Source is multisite and user passes the network URL   | Probe refuses; user must pass an individual subsite URL              |
| Source exposes REST API but blocks `_embed`           | Probe falls back to feed/sitemap mode automatically                  |
| A post has a CPT we don't import                      | Item skipped; one line in the migration log: `skipped cpt=my_type`   |
| A post's author email doesn't match an MW user        | Behaviour per the three-mode setting in §2.5                         |
| Two WP posts share a slug (can happen across CPTs)    | Second insert appends `-2`, `-3`; original slug kept on first import |
| WP post has `post_status=trash`                       | Skipped; log line emitted                                            |
| Source HTML references an asset we can't fetch (404/500) | URL left verbatim in body; one warning per unique URL             |
| Source HTML has an inline `<script>`                  | Preserved (Restore pipeline uses `allow_scripts=true`); noted in the migration log so admin can sanitise post-import |
| Imported content conflicts with an existing `content` row by slug | Behaviour per ADR job-config: `skip` / `overwrite` / `create-new` (the ADR writes this decision up; implementers must not guess) |

---

## 5. Idempotency key

Every imported `content` row carries a `wp_source_guid` meta field
with the WP `guid`. Re-running the same source against the same
Microweber install:

- updates by `wp_source_guid` when `conflict_policy=overwrite`
- skips by `wp_source_guid` when `conflict_policy=skip` (default)
- creates new rows when `conflict_policy=create-new` (for testing /
  side-by-side compare)

Comments carry `wp_source_comment_id`. Media carries
`wp_source_attachment_id`. Categories/tags merge by name (taxonomy +
name tuple).

The ADR is the authoritative place for conflict policy and the
rate-limit posture; this document only guarantees the key shape so
Phase-5 implementers can plumb the meta writes without waiting on
the ADR.

---

## 6. Failure modes surfaced to the operator

The migration job's final summary must show counts in these buckets.
Anything the importer silently dropped is a bug.

- `imported_posts`, `imported_pages`, `imported_media`,
  `imported_categories`, `imported_tags`, `imported_authors`,
  `imported_comments`, `imported_menus`
- `skipped_cpt` (aggregate + per-cpt breakdown)
- `skipped_taxonomy` (aggregate + per-taxonomy breakdown)
- `skipped_status_trash`, `skipped_status_spam`
- `skipped_media_unreachable` (with one sample URL per bucket)
- `skipped_author_unmapped` (strict mode only)
- `warnings_inline_script`, `warnings_off_site_asset`

If a bucket is non-zero, the UI surfaces it above the fold on the
job-complete screen so the operator can decide whether to re-run
with a different mode or accept the gap.

---

## 7. Change control

Changing what's in §2 or moving a bullet from §3 into §2 is a
breaking change for the importer's output contract — existing
migration jobs' summaries become inaccurate if the buckets in §6
shift underfoot. Updates land in the same commit that ships the
implementation, never before.
