# Livewire

Microweber's Livewire integration layer. This is **glue** — it adapts [Livewire](https://livewire.laravel.com) v4 to how Microweber serves assets and handles requests, ships a Microweber-tuned `config/livewire.php`, and wires in the Livewire modal stack.

Part of the [Microweber](https://microweber.com) CMS.

## What it does

The package has no public service/facade of its own. Its `LivewireServiceProvider` configures Livewire for Microweber:

- **Script route override** — `Livewire::setScriptRoute(...)` points `livewire.min.js` at the correct path depending on `MW_SERVED_FROM_BASE_PATH`.
- **Request handler override** — swaps Livewire's `HandleRequests` mechanism for `MwLivewireHandleRequests`, which rewrites the update URI to go through Microweber's `site_url()`.
- **Merged config** — merges `src/config/livewire.php` into the `livewire` config key (Microweber defaults: `legacy_model_binding => true`, `inject_assets => false`, `app_url => site_url()`, etc.).
- **Legacy model binding** — provides `MwEloquentModelSynth`, an `EloquentModelSynth` that resolves morph-map aliases back to model classes.
- **Modal stack** — registers `MicroweberPackages\LivewireModal\LivewireModalServiceProvider` (see the `microweber-livewire-modal` package).
- **`livewire` view namespace** + a helper route to render a component inside the admin iframe layout.

## Installation

```bash
composer require microweber-packages/microweber-livewire
```

The provider is registered by the Microweber core (`MicroweberPackages\Livewire\LivewireServiceProvider`). It loads `src/routes/web.php` and registers the `livewire` view namespace.

## Usage

### Render a component inside the admin iframe

A route is registered to render any Livewire component inside the `admin::layouts.iframe` layout — used where a full Livewire page needs to live in an iframe:

```
GET /admin-livewire-components/render-component?componentName=my-component&foo=bar
```

(Route name: `admin.livewire.components.render-component`, `admin` middleware.) Every extra query parameter is passed to the component as a mount attribute.

```blade
{{-- resources/views/livewire/render-component.blade.php --}}
@extends('admin::layouts.iframe')
@section('content')
    <div>
        @livewire($componentName, $componentAttributes, $livewireId)
    </div>
@endsection
```

### Configuration

The package overrides the `livewire` config. To customise Livewire for the whole app, edit the published `config/livewire.php` as usual — Microweber-specific defaults set here include:

| Key | Microweber default |
|-----|--------------------|
| `legacy_model_binding` | `true` |
| `inject_assets` | `false` (assets are injected via the meta-tags package) |
| `inject_morph_markers` | `true` |
| `app_url` | `site_url()` |
| `asset_url` | `site_url('vendor/livewire/livewire.min.js')` (or `public/...` when base-path served) |
| `pagination_theme` | `tailwind` |

## Key classes

- `MicroweberPackages\Livewire\LivewireServiceProvider` — the integration provider.
- `MicroweberPackages\Livewire\Mechanisms\HandleRequests\MwLivewireHandleRequests` — overrides the Livewire update URI.
- `MicroweberPackages\Livewire\Features\SupportLegacyModels\MwEloquentModelSynth` — morph-map aware model hydration.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Bozhidar Slaveykov
- Peter Ivanov
- Nikolay Rachkov

## License

MIT
