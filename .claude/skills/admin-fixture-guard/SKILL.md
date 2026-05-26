---
name: admin-fixture-guard
description: >-
  Three-layer defence for filtering test/fixture/Faker records from admin
  listings. Layer 1: AdminFixtureGuard vocab (UI pickers). Layer 2:
  Table::modifyQueryUsing SQL filter (pagination + query). Layer 3: Dusk
  test setUp/tearDown DB cleanup.
category: admin
tags: [filament, testing, fixtures, faker, data-quality]
version_focus: Filament v5 + Laravel 11
related: [filament-developer]
level: intermediate
---

# AdminFixtureGuard — Three-Layer Fixture Defence

## Problem

Admin resource listings (Pages, Posts, Products, Orders, etc.) show
test fixture records created by Dusk tests, database seeders, or Faker
data generators. These leak into production-styled admin UIs as visible
rows with names like "DuskTest Page 1734567890", "Test post", or
Faker-generated Lorem ipsum titles.

## The Three Layers

### Layer 1 — AdminFixtureGuard vocab (UI-level filter)

`src/MicroweberPackages/Filament/Support/AdminFixtureGuard.php`

158-word vocabulary + pattern matchers for Faker/test content:

```php
use MicroweberPackages\Filament\Support\AdminFixtureGuard;

// Filter a collection by title
$filtered = AdminFixtureGuard::filterByTitle($items, 'title');

// Check a single item
if (AdminFixtureGuard::shouldRenderItem($item)) { ... }

// Check raw text
if (AdminFixtureGuard::looksLikeFakerLorem($text)) { ... }
```

**Use for:** picker dropdowns, form select options, dashboard widgets,
any surface that receives an already-fetched collection.

**Limitation:** does NOT affect SQL query counts or pagination — a
listing with 47 total rows where 12 are fixtures still shows
"1-10 of 47" even when the 12 fixture rows are visually filtered.

### Layer 2 — `Table::modifyQueryUsing()` (SQL-level filter)

Applied in the Filament Resource's `table()` method:

```php
use Illuminate\Database\Eloquent\Builder;

public static function table(Table $table): Table
{
    return $table
        ->modifyQueryUsing(fn (Builder $query) => $query
            ->where('title', 'NOT LIKE', 'DuskTest%')
            ->where('title', 'NOT LIKE', 'Test post%')
        )
        // ... columns, actions, etc.
    ;
}
```

**Use for:** Filament resource table listings where pagination counts
and data integrity matter. Fixture rows are excluded from the SQL query
entirely — they never reach Filament's table renderer.

**When to apply:** any resource whose underlying table may contain
Dusk test fixtures (Content, Pages, Posts are the primary targets;
Orders, Categories, and Products may also be affected depending on
test coverage).

### Layer 3 — Dusk test cleanup (test-side hygiene)

```php
class AdminContentWorkflowTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Clean up any leftover fixtures from prior failed runs
        DB::table('content')
            ->where('title', 'LIKE', 'DuskTest%')
            ->delete();
    }

    protected function tearDown(): void
    {
        // Clean up fixtures created by this test
        DB::table('content')
            ->where('title', 'LIKE', 'DuskTest%')
            ->delete();
        parent::tearDown();
    }
}
```

**Use for:** every Dusk test that creates content/records in the DB.
Belt-and-suspenders: `setUp()` catches leftovers from prior failed runs;
`tearDown()` catches normal completion. Both are needed because test
failures can skip `tearDown()`.

## Decision Matrix

| Surface                        | Layer 1 (Guard) | Layer 2 (SQL) | Layer 3 (Test) |
|-------------------------------|:-:|:-:|:-:|
| Filament table listing         | optional | REQUIRED | REQUIRED |
| Picker/dropdown (Select, etc.) | REQUIRED | n/a | REQUIRED |
| Dashboard widget count         | optional | REQUIRED | REQUIRED |
| API endpoint                   | n/a | REQUIRED | REQUIRED |
| Public RSS/sitemap             | n/a | REQUIRED | REQUIRED |

## Common Fixture Patterns

Patterns to filter (keep this list in sync with `AdminFixtureGuard.php`):

- `DuskTest%` — Dusk browser test fixtures (e.g. "DuskTest Page 1734567890")
- `Test post%` — Generic test content
- Faker Lorem ipsum vocabulary (158-word list in AdminFixtureGuard)
- Numeric-only titles, single-character titles

## Cross-Surface Scope

AdminFixtureGuard is NOT admin-bounded. Faker/fixture data leaks into:
- Admin listings (primary surface)
- Public RSS feeds
- Sitemap entries
- Content templates
- API responses

When adding fixture filtering to a new surface, audit BOTH admin
selectors AND public content emitters. 8+ recurrences across distinct
tickets: AI-776, AI-781, AI-844, AI-1105, AI-1107, AI-1112, AI-1115,
AI-1129.

## Do NOT

- Rely on Layer 1 alone for table listings — pagination counts still
  reflect fixture rows.
- Apply fixture detection on production request paths (AI-860 un-ship) —
  fixtures are a TEST-DB-HYGIENE problem; clean at DB layer, not at
  request-time-filter layer.
- Skip Layer 3 test cleanup — failed Dusk tests leave fixture rows in
  the DB that persist across subsequent test runs and dev sessions.

## History

- AI-784 (2026-05-17) — AdminFixtureGuard umbrella created
- AI-1105/1107/1112/1115 (2026-05-26) — extended to FAQs, Ratings, API tokens, Files, Orders
- AI-1129 (2026-05-26) — three-layer defence formalised with SQL-level `modifyQueryUsing`
- AI-860 un-ship (2026-05-18) — public-side fixture detection removed per human dispatch
