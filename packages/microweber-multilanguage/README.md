# Microweber Multilanguage

Per-locale content translation for Microweber. Stores translations of model
attributes and options in dedicated tables, manages the set of supported site
languages, resolves the current locale (cookie / URL / geolocation), and plugs
into the Filament admin via a language switcher.

## Features

- **Translation store** — `multilanguage_translations` + `multilanguage_supported_locales` tables (migrations included).
- **`HasMultilanguageTrait`** — makes an Eloquent model's attributes translatable per locale.
- **`TranslateManager`** (`translate_manager`) — runs registered translate providers to localize rows/options on the fly.
- **`MultilanguageApi`** — add / activate / sort / delete / change supported languages.
- **`MultilanguageHelpers`** — enable/disable multilanguage, list supported languages, translatable module options.
- **`MultilanguageFilamentPlugin`** — language switcher wired into the admin panel.
- **Rich global helpers** — locale detection, supported-language queries, geolocation, alt-language links.

## Installation

```bash
composer require microweber-packages/microweber-multilanguage
```

Register `MicroweberPackages\Multilanguage\MultilanguageServiceProvider` (part of
the CMS provider stack). It binds `translate_manager` and `multilanguage_repository`,
loads the migrations and the public/API routes, and registers the `multilanguage`
view namespace. The admin panel adds `new MultilanguageFilamentPlugin()`.

## Usage

### Making a model translatable

```php
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Multilanguage\Models\Traits\HasMultilanguageTrait;

class Post extends Model
{
    use HasMultilanguageTrait;

    // attributes listed here are stored per-locale in multilanguage_translations
    public $translatable = ['title', 'content'];
}

$post->setLocale('bg_BG');            // read/write in a specific locale
$post->translations();                // HasMany of stored translations
$post->withoutMultilanguageRetrieving(); // read raw (default-locale) values
```

### Supported languages & current locale

```php
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

MultilanguageHelpers::multilanguageIsEnabled();     // bool
MultilanguageHelpers::setMultilanguageEnabled(true);
MultilanguageHelpers::getSupportedLanguages(true);  // only active

// Global helpers
get_supported_languages(true);                 // active site languages (with abr/icon/display)
add_supported_language('de_DE', 'German');     // register + activate a language
change_language_by_locale('bg_BG');            // switch current locale (queues the lang cookie)
get_default_language();                         // ['locale' => ..., 'language' => ...]
is_lang_supported('bg_BG');
is_lang_correct('bg_BG');
```

### The TranslateManager

```php
$manager = app('translate_manager');

$manager->addTranslateProvider(\MicroweberPackages\Option\TranslateTables\TranslateOption::class);
$manager->setCurrentLocale('bg_BG');
$manager->run();                 // localize the registered tables for the current request
$manager->getCurrentLocale();
$manager->getDefaultLocale();
$manager->reset();
```

Other packages opt in by registering a translate provider in their `boot()`:

```php
if (app()->bound('translate_manager')) {
    app()->translate_manager->addTranslateProvider(TranslateOption::class);
}
```

### Managing languages programmatically

```php
$api = new \MicroweberPackages\Multilanguage\MultilanguageApi();

$api->addLanguage(['locale' => 'fr_FR', 'language' => 'French']);
$api->activateLanguage(['id' => 3]);
$api->sortLanguage([...]);
$api->changeLanguage([...]);
$api->deleteLanguage(['id' => 3]);
```

### Alternate-language links (hreflang / switcher)

```php
multilanguage_get_all_links();                 // every page in every language
multilanguage_get_all_content_links($id);
multilanguage_get_all_post_links($postId);
multilanguage_get_all_product_links($productId);
multilanguage_get_all_category_links($categoryId);
```

## Key classes

- `MicroweberPackages\Multilanguage\MultilanguageServiceProvider` — bindings, routes, migrations.
- `MicroweberPackages\Multilanguage\TranslateManager` — on-the-fly localization (`translate_manager`).
- `MicroweberPackages\Multilanguage\MultilanguageApi` — language CRUD API.
- `MicroweberPackages\Multilanguage\MultilanguageHelpers` — enable-state + supported-language helpers.
- `MicroweberPackages\Multilanguage\MultilanguageFilamentPlugin` — admin language switcher.
- `MicroweberPackages\Multilanguage\Models\Traits\HasMultilanguageTrait` — translatable model trait.
- `MicroweberPackages\Multilanguage\Models\MultilanguageSupportedLocales` / `MultilanguageTranslations` — tables.
- Global helpers in `src/helpers/multilanguage_functions.php`.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
- Alexander Raikov
- Nikolay Rachkov

## License

MIT
