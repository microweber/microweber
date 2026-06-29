# Microweber Translation

A standalone Laravel package for managing translations with database storage, import/export, and locale helpers.

## Installation

```bash
composer require microweber-packages/translation
```

## Setup

Publish and run migrations:

```bash
php artisan migrate
```

Optionally publish config and language files:

```bash
php artisan vendor:publish --tag=microweber-translation-config
php artisan vendor:publish --tag=microweber-translation-lang
```

## Usage

### Basic Translation

The package replaces Laravel's default translator with one that loads translations from both files and database.

```php
// Standard Laravel translation functions work as before
echo __('Hello');
echo trans('messages.welcome');
```

### Import Translations

```php
use MicroweberPackages\Translation\TranslationImport;

$import = new TranslationImport();
$import->replaceTexts(true); // Overwrite existing

$translations = [
    [
        'translation_namespace' => '*',
        'translation_group' => '*',
        'translation_key' => 'Hello',
        'translation_text' => 'Hola',
        'translation_locale' => 'es_ES',
    ],
];

$result = $import->import($translations);
```

### Install a Language Pack

```php
use MicroweberPackages\Translation\TranslationPackageInstallHelper;

// Get available languages
$languages = TranslationPackageInstallHelper::getAvailableTranslations();

// Install a language
TranslationPackageInstallHelper::installLanguage('bg_BG');
```

### Language Helper

```php
use MicroweberPackages\Translation\LanguageHelper;

$name = LanguageHelper::getDisplayLanguage('bg_BG'); // "Bulgarian"
$flag = LanguageHelper::getLanguageFlag('bg_BG');     // "bg"
$isRtl = LanguageHelper::isRTL('ar_SA');              // true
```

## API Routes

The package provides optional API routes (prefix: `/api/translations`):

- `GET /` - List translations (paginated)
- `POST /save` - Save translations
- `GET /export` - Export translations as JSON
- `POST /import` - Import translations from JSON
- `GET /languages` - List available language packs
- `POST /install-language` - Install a language pack

## License

MIT