# ADR: Easy WordPress Migration

> **Status:** Proposed — 2026-04-23
> **Deciders:** WordPress Migration working group
> **Scope:** Cross-cutting policy decisions for the Easy WordPress
> Migration feature (plan in `TODO.md`, reuse map in
> `docs/migration/wordpress-audit.md`, surface scope in
> `docs/migration/wordpress-scope.md`, target mapping in
> `docs/migration/wordpress-mapping.md`).

Four policies are decided here because they cut across every Phase-3+
importer (RSS, sitemap, WP REST, WXR, media rehoster) and need to be
agreed *before* any one importer ships — otherwise two importers
could pick different auth shapes, different retry budgets, or
different idempotency keys, and the job-rerun and preview-commit
guarantees fall apart.

Each section below follows: **Context → Decision → Consequences →
Alternatives considered**.

---

## 1. Auth strategy — per import mode

### Context

The four URL-driven importers in the plan have different auth
requirements on the WordPress side:

| Mode    | Source surface                      | Public? | Can it auth? |
|---------|-------------------------------------|---------|--------------|
| RSS     | `/feed/`, `/feed/atom/`             | yes     | rarely used  |
| Sitemap | `/sitemap.xml`, `/sitemap_index.xml`| yes     | no practical path |
| REST    | `/wp-json/wp/v2/*`                  | mostly  | WP application passwords (5.6+) |
| WXR     | uploaded `.xml` file                | n/a     | n/a          |

Storing secrets insecurely — or sending an application password to
the wrong endpoint — would be a cross-cutting bug affecting every
importer.

### Decision

- **RSS mode:** anonymous GET only. No credential field exposed in
  the migration form for this mode.
- **Sitemap mode:** anonymous GET only. Same reasoning.
- **REST mode:** optional WP Application Password. When supplied, the
  importer sends HTTP Basic auth (`Authorization: Basic
  base64(user:app-pass)`) per
  [WP core REST handshake](https://developer.wordpress.org/rest-api/reference/authentication/).
  When not supplied, REST mode behaves exactly like RSS — it pulls
  only public posts/pages/media and logs a notice that
  `draft`/`private` content is inaccessible.
- **WXR mode:** no network auth; the file is uploaded over
  Microweber's own authenticated admin session.
- **Credential storage:** application-password pairs are stored in
  the `wp_migration_jobs` table **encrypted with Laravel's built-in
  `encrypted` cast** (`APP_KEY`-backed, as Laravel's other secret
  columns are in `config/auth.php` test installs). Never written to
  logs, never surfaced in the Filament index/view pages — only
  appears as a `Password` field masked with `•••` on the create
  form, re-entered on retry.
- **Credential lifetime:** the encrypted field is nulled out 24h
  after job completion (success *or* final failure) by the existing
  queue-workers cron (`Modules/Queue` already runs `php artisan
  schedule:run` — a new `wp-migration-jobs:prune-credentials`
  command plugs into that). Re-runs after the 24h window require
  the operator to re-paste the app password.
- **HTTP layer:** every outbound request goes through `mw()->http`
  (`src/MicroweberPackages/Utils/Http/Http.php`) — already covered
  by `SslVerificationTest`. The WP migrator never instantiates its
  own Guzzle client.

### Consequences

- Users on WP ≥ 5.6 get the richest REST import; users on older
  5.x (where app passwords are absent) see the same notice and run
  the anon-REST import, which still works for public content.
- App passwords are shown to the operator at most once (the create
  form). The Filament View page shows a `Credentials: expired /
  pruned` state after 24h; retrying after pruning prompts for
  re-entry.
- No custom crypto, no custom secret store.

### Alternatives considered

- **OAuth2.** Rejected: `wp-json/oauth1` is plugin-driven on
  WordPress (not core) and would force users to install an OAuth
  server plugin on the source before running our migrator — exactly
  the one thing we're trying to avoid.
- **Basic auth with user password.** Rejected: WP core disabled
  direct password basic-auth on the REST API in 5.6 in favour of
  application passwords. Sending the account password would succeed
  only against pre-5.6 installs and is the worst secret to cache.
- **Mirroring the app password in `.env`.** Rejected: makes
  multi-tenant Microweber hosts share a secret across jobs; doesn't
  fit the per-job model the Filament resource already implies.

---

## 2. Rate-limit posture

### Context

WordPress hosts are famously fragile under burst traffic. An
importer that pulls a 5000-post site as fast as it can will:

- get rate-limited by aggressive WAFs (Cloudflare, Wordfence) —
  we'd see 429s and false-positive blocks;
- trip 5xx on cheap shared hosts whose PHP-FPM pool maxes out at
  10 workers;
- burn our own outbound egress allowance on fresh installs running
  inside a container with network quotas.

The importer must be a **polite client** to every source by
default, with a single knob for operators who know their source can
handle more.

### Decision

- **Concurrency:** 1 in-flight request per source host. Never two
  concurrent `GET`s against the same WP install, regardless of
  mode. (Media rehost still serializes media downloads in the same
  single-host pool; cross-host asset rehost can run in parallel.)
- **Inter-request pacing:** minimum **300 ms** between requests to
  the same host. The job's worker `usleep`s the difference after
  each completed request.
- **Timeouts:** per-request `connect_timeout=10s`, `request_timeout=60s`.
  Timeouts beyond 60s mean the source is overloaded; retry with
  backoff rather than hold the worker.
- **Retry budget:** max 3 retries per request, with exponential
  backoff `2s, 8s, 32s` on 429/5xx. `4xx` other than 429 fails the
  single item immediately and is logged (never retried — the source
  is telling us no).
- **Pagination pacing:** when the REST importer walks `?page=N`, it
  applies the same 300 ms pacing between pages. No Promise-all pipe
  through `?page=1..N` simultaneously.
- **Respect `Retry-After`:** if the source sends a `Retry-After`
  header (429 or 503), honor it even if it's longer than the next
  backoff in the ladder. Cap at 10 minutes; beyond that, park the
  job in `awaiting-retry-window` and requeue at the `Retry-After`
  deadline.
- **Operator override:** a single `high_throughput=true` flag on the
  job config drops pacing to 50 ms, concurrency to 4, and backoff
  to `500ms, 2s, 8s`. Default is `false`; the Filament form calls
  this toggle "I own this WordPress server" with a tooltip
  explaining it will hammer the source.
- **`User-Agent`:** every outbound request carries
  `User-Agent: Microweber-WP-Migration/1.0 (+https://microweber.com)`
  so the source's admin can identify us in access logs when
  troubleshooting.
- **`robots.txt`:** the sitemap importer honors disallow rules for
  `/wp-admin/`, `/wp-json/` and any other disallowed paths;
  attempted fetches to disallowed paths are skipped with a log
  entry. RSS and REST are explicit-endpoint modes and ignore
  `robots.txt` (the operator pointed the job at the endpoint
  deliberately).

### Consequences

- A 5000-post import at default pacing is **~25 min** of wall time
  just for REST fetches (5000 / (1/0.3 s)). That's acceptable for a
  background job; the Filament UI polls progress, operator doesn't
  watch a spinner.
- The override exists because self-hosts-on-fast-infra shouldn't
  wait 25 min for something their source could serve in 2. It is
  **not** the default.

### Alternatives considered

- **No pacing, trust the source.** Rejected: the 90th-percentile WP
  host is a $5/month shared plan behind a Cloudflare free tier.
  Unpaced requests get us banned.
- **Token-bucket rate limiter per-host using Redis.** Rejected:
  overkill for a single-worker background job. The worker naturally
  serializes; a `usleep` between requests is enough.
- **Dynamic pacing based on latency.** Rejected: a moving floor is
  hard to reason about and hard to test. Static 300 ms is simple,
  predictable, and the override covers the "my source is faster"
  case.

---

## 3. Idempotency key — WP post GUID → Microweber content meta

### Context

Operators **will** re-run imports. Reasons:

- the first run was a dry-run / preview;
- the source added content since the last run;
- the migrator tripped on a pagination edge and the operator wants
  to pick up where it left off;
- the operator is testing a new job config (new author mapping,
  new conflict policy).

Every re-run needs to agree with the previous run on "have I seen
this source item before?" without walking every row in `content`.
Without a stable key, re-runs duplicate content.

### Decision

- **Primary key for content rows:** WP's `<guid>` element —
  specifically `$post->guid` as emitted by both REST
  (`response.guid.rendered`) and WXR (`<guid>` under `<item>`).
  Stored on the Microweber `content` row as meta
  `wp_source_guid=<string>`.
- **Primary key for comments:** WP's `<comment_id>` under the
  source post. Stored on the `comments` row as meta
  `wp_source_comment_id=<int>` (plus `wp_source_guid` on the parent
  content for cross-reference).
- **Primary key for media:** WP's `<post_id>` for the attachment.
  Stored on the `media` row as meta `wp_source_attachment_id=<int>`.
- **Primary key for taxonomy terms:** WP's `<term_id>`. Stored on
  the `categories` row as meta `wp_source_term_id=<int>` + a
  `wp_source_taxonomy=<string>` sibling to distinguish category vs.
  post_tag (see mapping doc §3.1).
- **Namespace:** all four meta keys are prefixed `wp_source_*` to
  reserve the namespace. Phase-N plugin-specific migrators that
  reuse this pattern must namespace their own source keys
  (`wp_source_woocommerce_*`, `wp_source_yoast_*`) — no collisions
  with this ADR's reserved four.
- **Site scoping:** the idempotency key is scoped by the source
  host extracted from the job URL at probe time — stored on the
  meta row alongside the key (`wp_source_host=example.com`).
  Migrating from two different WordPress installs into one
  Microweber can therefore produce two content rows with the same
  `guid` (WP doesn't globally unique them). The tuple
  `(wp_source_host, wp_source_guid)` is what the importer reads for
  dedupe.
- **WXR mode:** there is no source host on an offline file. The
  operator supplies a `source_host` string at upload time
  (defaults to the filename minus extension). The tuple shape is
  unchanged.
- **Indexing:** since `content_meta` is a key/value side table,
  re-run lookups must not scan it linearly. Phase-5 adds a
  composite index on `(meta_key, meta_value)` restricted to
  meta_keys in the reserved `wp_source_*` namespace. Already covered
  by the plan's Phase-5 taxonomy-first-pass bullet.

### Consequences

- Re-runs are O(n) on the source, O(1) lookup per item against the
  indexed meta columns. A 5000-post re-run stays fast.
- The operator can point a second Microweber install at the same
  source and get the same dedupe key on the second install — the
  meta pair is deterministic.
- Attacking the meta key is not a security boundary: `wp_source_guid`
  is already public on the source (WP emits it in the feed). Storing
  it unencrypted is fine.

### Alternatives considered

- **Using `wp_post_id` alone.** Rejected: post ids aren't unique
  across installs, so migrating from `site-a.com` and `site-b.com`
  into one Microweber would produce collisions on integer 1.
- **Hashing `(host || guid || post_id)`.** Rejected: a hash is
  opaque; when debugging a run the operator wants to see the source
  GUID, not a 32-char blob. The tuple meta fields are
  human-inspectable.
- **Reusing `content.source_url`** (existing Microweber field):
  rejected: the field is user-editable in the admin UI; an operator
  tweaking URLs post-import would silently break the re-run key.
  Meta fields are stable because nothing in Microweber's UI edits
  them.

---

## 4. Conflict policy — skip / overwrite / create-new revision

### Context

When a re-run sees the same source item it imported before, the
importer needs a policy to decide what happens to the Microweber
row. Three user intents are genuinely different:

1. "I'm re-running to pick up new items; leave existing ones
   alone." → **skip**
2. "I'm re-running because the source changed; sync my site." →
   **overwrite**
3. "I'm testing a new mapping; import again next to the old one so
   I can diff them." → **create-new**

A silent default — say, "always overwrite" — would destroy edits
the operator made to imported content after the first run (a real
and common case: operator imports, tweaks the homepage copy,
re-runs to pick up a new post, and loses the homepage tweak).

### Decision

- **Policy is a per-job setting**, stored on `wp_migration_jobs`.
  The Filament create form's field: radio group labelled
  `When I re-run this job, what should happen to existing imported
  content?` with three options below.
- **Default: `skip`.** The safe default. Any row with a matching
  `(wp_source_host, wp_source_guid)` is left untouched; only new
  source items produce new Microweber rows.
- **`overwrite`:** matching row is UPDATED in place. Fields
  overwritten: `title`, `content_body`, `description`,
  `updated_at`, `is_active`, `category_ids`, attached media (via
  re-rehost). Fields **preserved**: `created_at`, `created_by`,
  `id`, `url`, anything under `content_meta.*` that isn't
  `wp_source_*` (so operator-added tags survive). The
  `content.manager.before.save` / `after.save` events fire so
  downstream listeners (sitemap rebuild, search reindex) run.
- **`create-new`:** a fresh `content` row is inserted. The
  `wp_source_guid` meta is suffixed with `#rev<N>` where N is one
  past the highest existing revision for the tuple. URLs clash on
  slug → the new row's slug is appended with `-rev<N>`. The old
  row is left exactly as-is so the operator can diff.
- **User-edits detection for `overwrite`:** before updating, the
  importer compares the existing Microweber row's `updated_at`
  against its `wp_source_updated_at` meta (stored at last import).
  If they differ, the operator has edited the row since the last
  import; the importer logs a `WARNING: operator-edited row
  overwritten (wp_source_guid=<X>)` line and continues. (Not
  blocking — the operator chose `overwrite` with full knowledge.)
- **Per-item override:** the Filament preview step exposes an
  action on each staged row to flip its effective policy for this
  commit only. Bulk-select is available. This is the escape hatch
  when the job-wide policy is `skip` but the operator wants to
  refresh one stale row.
- **Policy does not apply to the first run.** If
  `wp_source_host` + `wp_source_guid` don't match anything, the
  item is a fresh insert regardless of policy.
- **Deletion is out of scope.** If the source removes a post, the
  Microweber row stays. The importer never deletes content it
  wrote. A future `sync` feature (not this plan) can revisit.

### Consequences

- Default-skip means re-running after an initial import is
  non-destructive — the operator can run daily with no risk to
  hand-edits.
- Overwrite preserves ownership (`created_by`) and URL so inbound
  links keep resolving across re-runs.
- Create-new keeps the old row available for comparison — the
  operator can manually delete the old row once the new shape is
  validated.

### Alternatives considered

- **Default: `overwrite`.** Rejected: silently loses hand-edits.
- **Default: `create-new`.** Rejected: floods the site with
  duplicate-slug drafts on every re-run.
- **Blocking re-imports after operator edits.** Rejected: makes
  "source site has new posts, pull them" a multi-step ceremony.
  Logging the overwrite and continuing is the right trade —
  operator asked for sync, gets sync, sees what was overwritten.
- **Merging: update only fields the operator hasn't touched.**
  Tempting but requires per-field edit tracking Microweber doesn't
  have. Would be its own Phase-N effort. The three-mode policy is
  the "good enough" shape until we have that infra.

---

## 5. Open questions (tracked, not decided here)

- Media-diffing on `overwrite`: do we re-download every asset or
  only ones whose URL changed? Punted to Phase 7 implementation —
  both shapes are viable and the reasoning depends on
  `MediaRehoster` cache-hit ratios we don't have data for.
- Cross-job idempotency: should a second job against the same
  source skip items the first job already imported? Current answer:
  yes by default (tuple is job-independent); operators who want two
  isolated parallel imports can tick a per-job `isolate_dedupe`
  flag. Deferred to Phase 2 job-model implementation.

---

## 6. Document status & dependents

This ADR is binding on the following downstream deliverables:

- Phase 2 — `wp_migration_jobs` table schema must carry
  `conflict_policy` (enum), `source_host` (indexed), `app_password`
  (encrypted, nullable).
- Phase 3 — RSS reader records `wp_source_host + wp_source_guid`
  tuple on every inserted row.
- Phase 4 — sitemap reader: same tuple; also honors `robots.txt`.
- Phase 5 — REST importer: app-password handshake per §1; pacing
  per §2; tuple per §3; policy branch per §4.
- Phase 6 — WXR importer: `source_host` supplied at upload; same
  tuple + policy branching.
- Phase 9 — Filament admin UX: create-form exposes the three
  policy options; preview page exposes per-item override; View
  page shows credential pruning state.

Amending this ADR requires updating the dependent phases in the
same commit.
