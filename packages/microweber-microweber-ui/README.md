# Microweber UI

A library of reusable Blade UI components for the Microweber admin — buttons, inputs, selects, modals, pickers (file / media / icon / font / color / link), toggles, alerts and more. Registered under the `microweber-ui` view namespace and used as `<x-microweber-ui::…>` anonymous Blade components.

Part of the [Microweber](https://microweber.com) CMS.

## Installation

```bash
composer require microweber-packages/microweber-microweber-ui
```

The provider (`MicroweberPackages\MicroweberUI\Providers\MicroweberUIServiceProvider`) is registered by the Microweber core. In `boot()` it registers the component views via `View::prependNamespace('microweber-ui', …/resources/views)`, so every file in `resources/views/components/` is available as an anonymous Blade component `<x-microweber-ui::{file-name}>`.

> The views live in `src/resources/views` (not the spatie default `resources/views`), so the provider deliberately does **not** call `$package->hasViews()`.

## Usage

Reference any component by its view file name under the `microweber-ui::` namespace:

```blade
{{-- Buttons --}}
<x-microweber-ui::button class="btn btn-primary">Save</x-microweber-ui::button>
<x-microweber-ui::danger-button wire:click="delete">Delete</x-microweber-ui::danger-button>
<x-microweber-ui::button-back />

{{-- Inputs --}}
<x-microweber-ui::input wire:model="title" />
<x-microweber-ui::textarea wire:model="body" />
<x-microweber-ui::select wire:model="type">
    <option value="page">Page</option>
</x-microweber-ui::select>
<x-microweber-ui::toggle wire:model="is_active" />
<x-microweber-ui::checkbox-single wire:model="agree" />

{{-- Pickers --}}
<x-microweber-ui::icon-picker wire:model.live="settings.icon" :value="$settings['icon']" />
<x-microweber-ui::color-picker wire:model="settings.color" />
<x-microweber-ui::media-picker wire:model="settings.image" />
<x-microweber-ui::file-picker wire:model="settings.file" />
<x-microweber-ui::font-picker wire:model="settings.font" />
<x-microweber-ui::link-picker wire:model="settings.link" />

{{-- Feedback --}}
<x-microweber-ui::alert type="success">Saved!</x-microweber-ui::alert>
<x-microweber-ui::input-error for="title" />
<x-microweber-ui::action-message on="saved">Saved.</x-microweber-ui::action-message>
```

Components are plain Blade `@props([...])` files. For example `button.blade.php`:

```blade
@props(['type' => 'submit', 'class' => 'btn btn-dark '])

<div class="form-control-live-edit-label-wrapper">
    <button {{ $attributes->merge(['type' => $type, 'class' => $class]) }}>
        {{ $slot }}
    </button>
</div>
```

### Available components

`action-message`, `action-section`, `alert`, `are-you-sure`, `banner`, `button`, `button-action`, `button-animation`, `button-back`, `checkbox`, `checkbox-single`, `color-picker`, `confirmation-modal`, `confirms-password`, `danger-button`, `date`, `datetime`, `dialog-modal`, `dropdown`, `dropdown-link`, `file-picker`, `font-picker`, `form-section`, `icon-picker`, `input`, `input-error`, `input-price`, `label`, `link-button`, `link-picker`, `media-picker`, `modal`, `nav-link`, `primary-button`, `radio`, `radio-modern`, `range-slider`, `secondary-button`, `section-border`, `section-title`, `select`, `select-modern`, `simple-text-editor`, `textarea`, `time`, `toggle`, `validation-errors`.

(See `src/resources/views/components/` for the full, authoritative list.)

## Key classes

- `MicroweberPackages\MicroweberUI\Providers\MicroweberUIServiceProvider` — registers the `microweber-ui` view namespace.
- `MicroweberPackages\MicroweberUI\Components\IconPicker` / `Tabs` — class-based `Illuminate\View\Component` variants.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Bozhidar Slaveykov
- Peter Ivanov
- Nikolay Rachkov
- Alexander Raikov

## License

MIT
