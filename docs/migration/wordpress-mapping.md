# WordPress Migration — target mapping decisions

> **Scope of this document:** Phase-1 deliverable #3 from the Easy
> WordPress Migration plan in `TODO.md`. Locks the one-way mapping
> from WordPress concepts to Microweber persistence shapes so every
> importer (RSS, sitemap, WP REST, WXR) agrees on the destination
> row for each source concept. Where a decision has competing
> options, the chosen option is called out and the rejected ones are
> named so later implementers can re-open with evidence rather than
> intuition.
>
> Companions: `docs/migration/wordpress-scope.md` (what's in vs. out
> of scope) and `docs/migration/wordpress-audit.md` (reuse map of
> existing Microweber seams).

---

## 1. Posts — `post_type=post` → `content_type=post`

**Decision:** WP posts land as Microweber `content` rows with
`content_type=post`, parented to the canonical `Blog` page
(auto-created by `DatabaseSaveContent::_getParentPageId('blog')` when
it doesn't exist).

Row shape (uses the seams documented in the audit — never raw
`DB::insert`):

```text
DatabaseSaveContent::save('content', [
    'content_type'  => 'post',
    'subtype'       => 'post',
    'parent'        => <Blog page id>,    // auto-resolved
    'title'         => $wp->post_title,
    'content_body'  => <rewritten HTML>,  // see §2
    'description'   => $wp->post_excerpt,
    'url'           => $wp->post_name,    // preserve slug
    'created_at'    => $wp->post_date_gmt,
    'updated_at'    => $wp->post_modified_gmt,
    'is_active'     => $wp->post_status === 'publish' ? 1 : 0,
    'created_by'    => <resolved user id>,   // §4
    'category_ids'  => <resolved category ids>,  // §3
    'content_meta'  => [
        'wp_source_guid' => $wp->guid,  // idempotency key
    ],
]);
```

**Why parent = Blog page and not a flat hierarchy:** Microweber
routes post URLs under their parent page (see
`content_link()` behaviour — post URLs resolve as
`<parent-slug>/<post-slug>`). Without a parent the imported posts
have no canonical public URL; routing breaks silently.

**Why `subtype=post`:** explicit, matches the shape Microweber's own
admin uses when you click "Add post" from the Blog page and keeps
post-specific filters (e.g. `Content::posts()`) working.

### Alternative considered and rejected

- Mapping WP posts to Microweber's `posts` model directly (bypassing
  `content`): rejected because `posts` is a *derived view* of
  `content WHERE content_type='post'` — the authoritative insert
  seam is the `content` table via `save_content()`, and downstream
  listeners hook the content-save event, not a post-save event.

---

## 2. Pages — `post_type=page` → `content_type=page`

**Decision:** WP pages land as Microweber `content` rows with
`content_type=page`, **not** nested under any auto-created parent
(static pages sit at site root by default).

```text
DatabaseSaveContent::save('content', [
    'content_type' => 'page',
    'subtype'      => 'static',
    'parent'       => 0,   // resolved to real parent in second pass
    'title'        => $wp->post_title,
    'content_body' => <rewritten HTML>,
    'description'  => $wp->post_excerpt,
    'url'          => $wp->post_name,
    'position'     => $wp->menu_order,
    'is_active'    => $wp->post_status === 'publish' ? 1 : 0,
    'created_by'   => <resolved user id>,
    'is_shop'      => 0,   // WP pages are never shop pages by default
    'content_meta' => [
        'wp_source_guid'      => $wp->guid,
        'wp_page_template'    => $wp->_wp_page_template,  // stored, not applied
    ],
]);
```

### Parent resolution — two-pass

WP pages can reference `post_parent` pointing at another WP page. A
single insert pass can't rely on the parent id existing yet, so the
importer runs in two passes:

1. **Pass 1** — insert every page with `parent=0`, remember a map of
   `wp_post_id → microweber_content_id`.
2. **Pass 2** — for each imported page that had a non-zero
   `post_parent`, update `content.parent` to the mapped Microweber
   id.

Cycles (a WP export pathology — very rare, usually import-corrupted)
are detected in Pass 2; offenders land under the root with a
`wp_parent_cycle=<wp-id>` meta tag.

### Alternative considered and rejected

- Mapping WP pages to `content_type=page, subtype=dynamic`: rejected
  because `subtype=dynamic` is how Microweber marks *listing* pages
  (the Blog page itself, the Shop page). A static WP "About" page is
  not a listing; forcing it into `dynamic` subtype would make it
  render a content feed where the source rendered prose.

---

## 3. Taxonomies — WP `category` AND WP `post_tag` → Microweber `categories`

**Decision:** both WP categories and WP tags collapse into
Microweber's `categories` table. Categories preserve their WP
hierarchy (nested). Tags become **flat categories** (depth-0) under
the Blog page.

This is the decision named in the task line itself ("WP category/tag
→ Microweber categories") and contradicts an earlier draft of the
scope doc that had tags → `Modules/Tag` — the earlier draft was
aligned in the same commit that ships this document.

### Why one bucket for both

1. Microweber's `Tag` model (`Modules/Tag/Models/Tag.php`) extends
   `Conner\Tagging\Model\Tag` and ships with a `customers()`
   belongsToMany relation — it's wired for **customer/CRM
   segmentation**, not post classification. Mapping WP post-tags into
   it would coexist with CRM tags and muddy the admin UX.
2. Microweber's public taxonomy browsing is category-driven
   (`/category/<slug>/` routes under a parent page). Re-routing to a
   tag index would be net-new work the importer shouldn't require.
3. Users browsing the migrated site expect WP `/tag/foo/` URLs to
   redirect to a browsable archive; the simplest shape that
   satisfies that is a Microweber category of the same name.

### Category row shape

```text
Category::firstOrCreate(
    [
        'title'   => $wp->name,
        'parent_id' => <parent category id or null>,
        'rel_id'  => <Blog page id>,
    ],
    [
        'slug'       => $wp->slug,
        'data_type'  => 'category',
        'rel_type'   => Modules\Content\Models\Content::class,
        'is_active'  => 1,
        'content_meta' => [
            'wp_source_term_id'   => $wp->term_id,
            'wp_source_taxonomy'  => 'category',
        ],
    ],
);
```

Nested WP categories use `DatabaseSave::getOrInsertCategories` which
walks the chain parent-first — documented in the audit §1.

### Tag row shape

```text
Category::firstOrCreate(
    [
        'title'     => $wp->name,
        'parent_id' => null,   // flat
        'rel_id'    => <Blog page id>,
    ],
    [
        'slug'       => $wp->slug,   // prefixed with "tag-" on collision (§3.1)
        'data_type'  => 'category',
        'rel_type'   => Modules\Content\Models\Content::class,
        'is_active'  => 1,
        'content_meta' => [
            'wp_source_term_id'   => $wp->term_id,
            'wp_source_taxonomy'  => 'post_tag',
        ],
    ],
);
```

### 3.1 Collision handling: WP category with the same name as a WP tag

WP permits `category.news` and `tag.news` to coexist. Microweber's
`categories` table allows same-title siblings but the URL slugs must
differ at the same `parent_id` level. The importer's rule:

1. Import categories first (with their hierarchy).
2. When importing a tag, check for an existing depth-0 row under
   the Blog page with the same slug.
3. On collision, prefix the tag's slug with `tag-` (e.g.
   `tag-news`). Title stays `News` so the user-facing label is
   unchanged.
4. Record the original source taxonomy in `content_meta.wp_source_taxonomy`
   so the distinction survives for future reporting.

Rationale for slug-prefix over silent-merge: merging would lose the
source/taxonomy distinction and break the WP `/tag/news/` → MW URL
expectation for users who linked to the tag archive.

### 3.2 `post_format` and `nav_menu` terms

- `post_format` (WP's built-in format taxonomy: aside, gallery,
  link, etc.) — **dropped**, they're presentational-only and
  Microweber has no equivalent; Phase-N may revisit via a content
  meta `wp_post_format`.
- `nav_menu` — not a content-taxonomy in the user sense; handled
  separately by the menu importer (scope §2.7).

---

## 4. Authors — WP `user` → Microweber `user` with **match-by-email, skip-if-missing, manual-map UI**

**Decision:** default author-resolution mode is **match-by-email with
skip-if-missing**. Authors who don't resolve bubble up in a
dedicated manual-map UI in the Filament migration resource before
the user can commit the job.

This is the task's stated policy; the scope doc's three-mode setting
(`strict` / `create-missing` / `fallback-to-admin`) remains but
`match-by-email + manual-map` is the **default**; the other two
modes are opt-in via job config for bulk/headless flows.

### Resolution algorithm

```text
for each WP author in the source:
    # step 1: direct match
    mw_user = User::where('email', wp_author.user_email)->first()
    if mw_user:
        remember(wp_author.id -> mw_user.id)
        continue

    # step 2: manual-map UI (default) / fallback mode
    if mode == 'match-by-email-with-manual-map':
        enqueue_for_manual_mapping(wp_author)
    elif mode == 'create-missing':
        mw_user = User::create([
            'email'      => wp_author.user_email,
            'username'   => resolve_unique_username(wp_author.user_login),
            'first_name' => first_word(wp_author.display_name),
            'last_name'  => rest_of(wp_author.display_name),
            'is_admin'   => wp_author.roles has 'administrator' ? 1 : 0,
            'is_active'  => 1,
            'is_verified'=> 0,
            'password'   => Hash::make(random_bytes(32)),  // §5
        ])
        remember(wp_author.id -> mw_user.id)
    elif mode == 'fallback-to-admin':
        remember(wp_author.id -> job.runner_user_id)
```

The "manual-map UI" is the Filament form rendered when the job
status is `awaiting-author-map`: one row per unresolved WP author
with columns `WP email`, `WP display_name`, `WP role`, and a
`Microweber user` combo box (select existing user *or* "create new"
*or* "fallback to admin for this author only"). Committing the
form resumes the job.

### Why "skip-if-missing" is the task default, not "create-missing"

- Creating users silently is a surprise admin action — especially on
  a site with email-based login where an imported address collides
  with an outreach email already on file.
- Forcing a human confirm on author creation catches typos
  (`authot@example.com`) that would otherwise stick forever.
- The `create-missing` mode is opt-in for bulk imports where the
  operator has verified the source's author list.

### WP role → Microweber role mapping

Microweber uses a flat `users.is_admin` flag plus Spatie permissions
(no pre-seeded `editor/author/subscriber` roles). The importer
therefore uses the coarsest mapping:

| WP role         | MW `is_admin` | Notes                                                      |
|-----------------|---------------|------------------------------------------------------------|
| `administrator` | 1             | Admin capability retained                                  |
| any other       | 0             | Stored under `content_meta.wp_source_role` so finer role-replay is a future Phase-N capability, not a current importer responsibility |

### Rejected alternative: merge-by-username

- WP `user_login` is not globally unique across installs; merging
  by login produces the wrong user on collisions (e.g. `admin` in
  the source ≠ `admin` in the destination).
- WP `user_email` is install-unique and is the only safe merge key.

---

## 5. Auth material — never imported

- `user_pass` (phpass hashes) — not portable into Microweber's
  `bcrypt` via Laravel; new users get `Hash::make(random_bytes(32))`
  so the row is valid and the account receives a standard password
  reset email on first publish.
- `user_activation_key`, `session_tokens` meta — skipped, see scope
  §3.6.
- WP application passwords — skipped (Microweber issues its own via
  Passport).

---

## 6. Content ownership across pass-1 / pass-2

Pass-1 writes run with `created_by = <resolver output>`. If the
author ended up in the manual-map queue, the content row is written
with `created_by = 0` and a `wp_pending_author=<wp_user_id>` meta;
pass-2 (after manual-map completes) walks rows with that meta and
updates `created_by` in a single `UPDATE content SET created_by=? WHERE id IN (...)`
per resolved author.

This keeps preview-before-commit honest: a staged row with
`created_by=0` is visibly unowned in the preview UI, and a user
can't inadvertently commit a job whose author mapping isn't done.

---

## 7. Summary — one table

| WP concept                | Microweber target                                  | Seam to call                                                      |
|---------------------------|----------------------------------------------------|-------------------------------------------------------------------|
| `post_type=post`          | `content` + `content_type=post`                    | `DatabaseSaveContent::save('content', $row)`                      |
| `post_type=page`          | `content` + `content_type=page`                    | `DatabaseSaveContent::save('content', $row)` (+ pass-2 parent fix)|
| `category` taxonomy term  | `categories` (hierarchical) under Blog page        | `DatabaseSave::getOrInsertCategories()`                           |
| `post_tag` taxonomy term  | `categories` (flat) under Blog page                | `DatabaseSave::getOrInsertCategories()` with `parent_id=null`     |
| WP user                   | `users` (match-by-email, else manual-map queue)    | direct `User::create([...])` or admin's manual-map form submit    |
| `comment`                 | `comments` polymorphed to the imported content     | (covered in scope §2.6)                                           |
| `attachment` / inline img | `media` + URL rewrite                              | `DatabaseSave::downloadAndSaveMedia()` (covered in audit §1)      |

Phase-3+ implementers should cite this document by §-number in
their PR descriptions when a row is written — the mapping is the
reviewer's checklist. A row that doesn't line up with a § here needs
a mapping-doc update in the same commit, not a one-off exception.
