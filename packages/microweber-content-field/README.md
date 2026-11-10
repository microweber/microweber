# Microweber Content Field

Standalone content-field persistence layer for Laravel. Manages the `content_fields` and `content_fields_drafts` tables — the storage backend for Live-Edit editable regions and multilanguage field values. Each row stores one named field value for a related record (`rel_type` + `rel_id`), with draft support.

## Features

- Read / write / delete named content fields keyed by `rel_type` + `rel_id` + `field`.
- Separate drafts table (`content_fields_drafts`) with old-draft cleanup.
- Flexible querying (single value, full row, or multi-row `all` mode) via a single `getField()` entry point.
- Registered as a container singleton and exposed through the `ContentField` facade.
- Deferred service provider — only boots when the manager is resolved.

## Installation

```bash
composer require microweber-packages/microweber-content-field
```

The package auto-discovers its service provider (`spatie/laravel-package-tools`). If you disable auto-discovery, add it manually:

```php
MicroweberPackages\ContentField\ContentFieldServiceProvider::class,
```

Migrations are loaded from the package, so just run:

```bash
php artisan migrate
```

## Usage

Resolve the manager from the container, the `ContentField` facade, or inject `ContentFieldManager`.

```php
use MicroweberPackages\ContentField\Facades\ContentField;

// Save (insert or update) a field — returns the row id, or false on failure.
$id = ContentField::saveField([
    'rel_type' => 'content',
    'rel_id'   => 42,
    'field'    => 'content_body',
    'value'    => '<p>Hello world</p>',
    // 'is_draft' => true,   // write to content_fields_drafts instead
]);

// Low-level read: field name, rel_type, optional rel_id.
$html = ContentField::getFieldData('content_body', 'content', 42);
// returns the string value, or false if not found

// Flexible read via array/query-string params.
$value = ContentField::getField([
    'field'    => 'content_body',
    'rel_type' => 'content',
    'rel_id'   => 42,
]);

// 'all' mode returns every matching row (array of rows), 'full' returns the whole row.
$rows = ContentField::getField([
    'rel_type' => 'content',
    'rel_id'   => 42,
    'all'      => true,
]);

// Existence check.
if (ContentField::fieldExists('content_body', 'content', 42)) {
    // …
}

// Delete all fields for a relation.
ContentField::deleteByRelation('content', 42);

// Delete by filter (optionally from the drafts table).
ContentField::deleteField(['rel_type' => 'content', 'rel_id' => 42, 'field' => 'content_body']);
ContentField::deleteField(['rel_type' => 'content', 'rel_id' => 42], true); // drafts
```

Dependency-injection style:

```php
use MicroweberPackages\ContentField\ContentFieldManager;

public function __construct(private ContentFieldManager $fields) {}
```

### Manager API

| Method | Returns | Purpose |
| --- | --- | --- |
| `getField(array\|string $data)` | `mixed` | Flexible read (value / full row / `all` rows) |
| `getFieldData(string $field, string $relType, mixed $relId = false, bool $full = false)` | `array\|string\|false` | Low-level single read |
| `saveField(array $data)` | `int\|false` | Insert or update (needs `rel_type`, `rel_id`, `field`, `value`) |
| `fieldExists(string $field, string $relType, mixed $relId = null)` | `bool` | Existence check |
| `deleteField(array $filter, bool $drafts = false)` | `int` | Delete matching rows |
| `deleteByRelation(string $relType, mixed $relId)` | `int` | Delete all fields for a record |
| `deduplicateGlobalFields(string $field, string $relType, mixed $relId)` | `void` | Collapse duplicate global field rows |
| `cleanOldDrafts(array $data)` | `void` | Prune stale draft rows |

## Key classes

- Facade: `MicroweberPackages\ContentField\Facades\ContentField`
- Manager: `MicroweberPackages\ContentField\ContentFieldManager`
- Models: `MicroweberPackages\ContentField\ContentFieldModel`, `MicroweberPackages\ContentField\ContentFieldDraftModel`
- Provider: `MicroweberPackages\ContentField\ContentFieldServiceProvider`

## Testing

```bash
composer test
composer analyse
```

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
- Nikolay Rachkov

## License

MIT
