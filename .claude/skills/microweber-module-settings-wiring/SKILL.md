---
name: microweber-module-settings-wiring
description: >-
  Use this whenever a Microweber module has Filament settings (via LiveEditModuleSettings)
  that the module's render() method should consume but doesn't. The Stage-1
  "data-shipped-consumer-not-wired" defect — settings save correctly, users change them,
  nothing happens in the template. Apply this pattern when adding settings fields in
  a ModuleSettings.php or when you notice render() ignoring options from getOption()/get_option().
  Also covers correct defaults, filter_var() for boolean options, and Blade conditional
  patterns for conditional rendering (e.g. show_arrows, show_dots, enable_captcha).
---

# Microweber Module Settings Wiring

## Problem

Microweber CMS has a settings framework where:
1. `Modules/<Name>/Filament/<Name>ModuleSettings.php` defines Filament form fields
2. Settings save into the DB via the `LiveEditModuleSettings` base class
3. `Modules/<Name>/Microweber/<Name>Module.php::render()` builds view data
4. Template renders from `$viewData` 

**The defect:** Steps 1-2 ship correctly. Steps 3-4 use hardcoded values and never call
`$this->getOption()` or `get_option()`. User changes settings; nothing changes visually.

**Stage-1 defect family**: "data-shipped-consumer-not-wired". The settings UI and database
layer are correct — only the consumption layer is missing.

**8+ confirmed instances** in the microweber codebase (2026-05-22): Slider (autoplay/speed/
arrows/dots), GoogleMaps (show_marker), Embed (code_type), ContactForm (newsletter_subscription/
enable_captcha/email_redirect_after_submit), SocialLinks (6 icon styling properties).

## Root Cause

`LiveEditModuleSettings` saves options correctly via `set_option()` calls. Filament persists
them. The `render()` method simply never calls `$this->getOption('key', 'default')` to read
them back — the template hardcodes defaults.

## Solution Pattern

### Step 1: Check ModuleSettings.php for the option key names

```php
// In Modules/Slider/Filament/SliderModuleSettings.php:
Toggle::make('options.autoplay')        // → key = 'autoplay'
TextInput::make('options.autoplay_speed') // → key = 'autoplay_speed'
```

The form field name `options.KEY` → the saved option key is `KEY`.

### Step 2: Read options in render()

```php
// In Modules/Slider/Microweber/SliderModule.php:
public function render()
{
    $viewData = $this->getViewData();  // always call parent first

    // Read each setting with a sensible default:
    $viewData['sliderAutoplay']      = (bool) $this->getOption('autoplay', true);
    $viewData['sliderAutoplaySpeed'] = (int) ($this->getOption('autoplay_speed', 3000) ?: 3000);
    $viewData['sliderShowArrows']    = (bool) $this->getOption('show_arrows', true);
    $viewData['sliderShowDots']      = (bool) $this->getOption('show_dots', true);

    return view(static::$templatesNamespace . '.default', $viewData);
}
```

**For boolean options stored as "1"/"0"/true/false strings** — use `filter_var()`:
```php
$viewData['showMarker'] = filter_var(
    $this->getOption('show_marker', true),
    FILTER_VALIDATE_BOOLEAN,
    FILTER_NULL_ON_FAILURE
) ?? true;
```

**For string enum options** — cast to string with default:
```php
$viewData['code_type'] = $this->getOption('code_type', 'html') ?: 'html';
```

**For module-level options not tied to specific instance** — use `get_option()` instead:
```php
// ContactForm: options scoped to this specific form instance (module_id)
$viewData['show_newsletter_subscription'] = (bool)(
    get_option('newsletter_subscription', $this->params['id']) ?: false
);
```

### Step 3: Consume in template

```blade
{{-- Blade template: conditional based on wired setting --}}
@if($sliderAutoplay)
    autoplay: { delay: {{ $sliderAutoplaySpeed }} },
@endif

@if($sliderShowDots)
    pagination: { el: '#slider-pagination-{{ $id }}' },
@endif
```

### When to use `$this->getOption()` vs `get_option()`

| Method | When to use |
|--------|-------------|
| `$this->getOption('key', 'default')` | Options set via the module settings form (scoped to this element) |
| `get_option('key', $this->params['id'])` | Options stored against the module instance ID |
| `get_option('key', 'website')` | Site-wide settings (not module-specific) |

## Checking for Existing Settings Fields

Before wiring a render() method, always check which fields exist in ModuleSettings:

```bash
# Find all option field names for a module:
grep -E "make\('options\." Modules/Slider/Filament/SliderModuleSettings.php
```

This shows you the exact option keys the settings UI writes.

## Contract Test Pattern

After wiring, write a contract test that asserts the `$viewData` variable is set:

```php
/** @test */
public function render_reads_autoplay_from_options(): void
{
    // The getOption calls must be present in the render method source:
    $src = file_get_contents(base_path('Modules/Slider/Microweber/SliderModule.php'));

    $this->assertStringContainsString(
        "getOption('autoplay'",
        $src,
        'render() must call getOption("autoplay") to wire the settings'
    );
    $this->assertStringContainsString(
        "viewData['sliderAutoplay']",
        $src,
        'render() must set $viewData[sliderAutoplay]'
    );
}
```

## Do NOT

- Do NOT add option reads to the template directly (templates have no access to `getOption()`).
- Do NOT hardcode values in the template when a setting exists in ModuleSettings.
- Do NOT cast boolean options with `(bool) get_option(...)` without a null-coalesce —
  `get_option()` can return `null`, `"1"`, `"0"`, `"true"`, `"false"`.
  Use `filter_var()` for robust boolean coercion.
- Do NOT skip checking the existing ModuleSettings.php first — option keys must match exactly.
- **Do NOT let the form field key differ from the `get_module_option()` key** (3rd-batch Stage-1 variant, AI-1017 2026-05-22):
  `Select::make('options.data-maptype')` saves as `data-maptype` (no hyphen between "map" and "type"),
  but `render()` called `get_module_option('data-map-type', ...)` (with hyphen). The map type setting
  was silently ignored. Fix: change the form key to `options.data-map-type` AND add a backward-compat
  read of the old key: `get_module_option('data-map-type', $id) ?? get_module_option('data-maptype', $id)`.
  This applies to any hyphen, underscore, or camelCase mismatch between the form key and the reader key.

## Key-Mismatch Anti-Pattern (3+ recurrences — watch for this)

| Module | Form key | Reader key | Mismatch type |
|--------|----------|------------|---------------|
| Video (AI-967) | `options.lazy_load` | `lazyload` | underscore vs none |
| GoogleMaps (AI-1017) | `options.data-maptype` | `data-map-type` | hyphen placement |
| Video (AI-1008) | `options.width` suffix `px` | helper appends `px` | double-append |

When fixing a mismatch: (1) fix the form key to match the reader; (2) add a fallback read of the old key for backward compat; (3) add a contract test asserting both keys are handled.

## Applies To

- `Modules/<Name>/Microweber/<Name>Module.php` render() methods
- Any module with a Filament settings class extending `LiveEditModuleSettings`
- Template files consuming `$viewData` (look for hardcoded values matching settings fields)

## Reference Files

- `Modules/Slider/Microweber/SliderModule.php` — autoplay/speed/arrows/dots wiring
- `Modules/GoogleMaps/Microweber/GoogleMapsModule.php` — filter_var() boolean pattern
- `Modules/Embed/Microweber/EmbedModule.php` — string enum type dispatch
- `Modules/ContactForm/Microweber/ContactFormModule.php` — get_option() with module ID scope
