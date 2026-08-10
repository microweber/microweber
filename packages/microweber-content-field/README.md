# microweber-packages/content-field

Standalone content-field persistence layer for Laravel. Manages `content_fields` and `content_fields_drafts` tables — the storage backend for live-edit editable regions and multilanguage field values.

## Installation

```bash
composer require microweber-packages/content-field
```

The package auto-discovers its service provider. If you disable auto-discovery, add the provider to `config/app.php`:

```php
MicroweberPackages\ContentField\ContentFieldServiceProvider::class,
```

Run migrations:

```bash
php artisan migrate
```

## Usage

```php
// Resolve from the container
$cfm = ContentField::;

// Save a field
$cfm->saveField([
    'rel_type' => 'content',
    'rel_id'   => 42,
    'field'    => 'content_body',
    'value'    => '<p>Hello world</p>',
]);

// Read a field value
$value = $cfm->getFieldData('content_body', 'content', 42);

// Delete fields for a relation
$cfm->deleteByRelation('content', 42);
```

## Testing

```bash
composer test
```

## Static Analysis

```bash
composer analyse
```
