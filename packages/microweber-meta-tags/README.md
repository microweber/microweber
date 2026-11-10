# Meta Tags

Head/footer meta-tag rendering for Microweber — the single place that assembles everything that goes into `<head>` and before `</body>` on the frontend, the legacy admin and the Filament admin: title, favicon, CSRF, SEO tags, template CSS/JS, Livewire assets, custom user CSS/JS, per-page CSS/JS, motion libraries and more.

Part of the [Microweber](https://microweber.com) CMS.

## How it works

Built on top of [butschster/meta-tags](https://github.com/butschster/LaravelMetaTags). `MetaTagsServiceProvider` extends butschster's application provider and registers a set of **packages** (`frontend`, `admin`, `filament`) composed of **tags** — each tag is an `Entities\*` class under `src/Entities/` that knows how to render one chunk of head/footer markup (e.g. `TitleHeadTags`, `FaviconHeadTag`, `CsrfTokenHeadTags`, `LiveEditCssHeadTags`, `MotionLibsHeadTags`, `LivewireHeadTags`).

Rendering is exposed through two renderers (bound as singletons) and their facades:

- `FrontendMetaTagsRenderer` → `FrontendMetaTags` facade — includes the `frontend` package.
- `AdminMetaTagsRenderer` → `AdminMetaTags` facade — includes the `admin` package.

## Installation

```bash
composer require microweber-packages/microweber-meta-tags
```

The provider is registered by the Microweber core. The global helper functions in `src/helpers/meta_tags_functions.php` are loaded via Composer's `files` autoload.

## Usage

### Global helpers (how templates call it)

Template masters render the head and footer with two helpers:

```blade
<head>
    {{-- renders title, favicon, CSRF, template CSS/JS, Livewire, SEO, custom CSS … --}}
    {!! meta_tags_head() !!}
</head>
<body>
    ...
    {{-- renders footer scripts: Livewire, custom/per-page JS, icon fonts … --}}
    {!! meta_tags_footer() !!}
</body>
```

Append your own markup to the template head/footer:

```php
meta_tags_head_add('<link rel="preconnect" href="https://fonts.gstatic.com">');
meta_tags_footer_add('<script src="/js/analytics.js" defer></script>');

// Thin wrappers over the template manager
template_head('<meta name="theme-color" content="#0d6efd">');
template_foot('<script>/* ... */</script>');

// Flag the current request so jQuery is loaded eagerly
mw_require_jquery();
```

Admin head/footer helpers (deprecated frontend aliases `mw_header_scripts()` / `mw_footer_scripts()` delegate to the two above):

```php
mw_admin_header_scripts(); // AdminMetaTags::getHeadMetaTags()
mw_admin_footer_scripts(); // AdminMetaTags::getFooterMetaTags()
```

### Facades

```php
use MicroweberPackages\MetaTags\Facades\FrontendMetaTags;
use MicroweberPackages\MetaTags\Facades\AdminMetaTags;

$head   = FrontendMetaTags::getHeadMetaTags();
$footer = FrontendMetaTags::getFooterMetaTags();

$adminHead = AdminMetaTags::getHeadMetaTags();
```

### Registering a custom head/footer tag

Add a tag to one of the butschster packages from your own service provider:

```php
use Butschster\Head\Facades\PackageManager;
use Butschster\Head\Packages\Package;

PackageManager::create('my_feature', function (Package $package) {
    $package->addTag('my_head_tag', new \App\Meta\MyHeadTag());
});
```

…or hook into Microweber's custom-tag callbacks, which `CustomHeadTagsFromCallback` / `CustomFooterTagsFromCallback` collect.

## Key classes

- `MicroweberPackages\MetaTags\Facades\FrontendMetaTags` / `AdminMetaTags` — render entry points.
- `MicroweberPackages\MetaTags\FrontendMetaTagsRenderer` / `AdminMetaTagsRenderer` / `AdminFilamentMetaTagsRenderer` — the renderers (`getHeadMetaTags()` / `getFooterMetaTags()`).
- `MicroweberPackages\MetaTags\Providers\MetaTagsServiceProvider` — registers the `frontend` / `admin` / `filament` packages and all tags.
- `MicroweberPackages\MetaTags\Entities\*` — the individual head/footer tag renderers (title, favicon, CSRF, Live Edit CSS/JS, motion libs, Livewire, icon fonts, …).

## Global helpers

```php
meta_tags_head(): string;              // full <head> markup for the frontend
meta_tags_footer(): string;            // footer markup for the frontend
meta_tags_head_add($src): string;      // append to template head
meta_tags_footer_add($src): string;    // append to template footer
template_head($src); template_foot($src);
mw_require_jquery(): void;
mw_admin_header_scripts(): string; mw_admin_footer_scripts(): string;
```

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Alexander Raikov

## License

MIT
