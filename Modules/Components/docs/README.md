# `Components` module

> **Slug:** `components`
> **Tier:** 4
>
> *Auto-generated from filesystem survey on 2026-04-25 with
> column / route / method extraction. Domain section is
> the only hand-edit needed; the rest of this file is
> regenerable from source.*

## Domain

*Hand-edit this section: describe what the module does
operationally, who consumes it, and which sibling modules
it interacts with.*

## Data model

This module owns no migrations of its own.

## Tests

Run: `php vendor/bin/phpunit Modules/Components/Tests`

### `Tests/Unit/Components/AlertTest.php`

  - `it_renders_alert_component_with_dismissible_property`
  - `it_renders_a_card_with_dark_theme`

### `Tests/Unit/Components/ButtonTest.php`

  - `it_renders_a_button_with_type`
  - `it_renders_a_button_as_outline`
  - `it_renders_a_block_button`
  - `it_renders_a_button_with_additional_attributes`

### `Tests/Unit/Components/CardTest.php`

  - `it_renders_a_card_with_dark_theme`
  - `it_renders_a_card_with_custom_classes`

### `Tests/Unit/Components/CheckboxTest.php`

  - `it_renders_a_checkbox_with_label`
  - `it_renders_a_checkbox_as_disabled`

### `Tests/Unit/Components/ColTest.php`

  - `it_renders_a_col_with_different_sizes`
  - `it_renders_a_col_with_full_width`

### `Tests/Unit/Components/ContainerTest.php`

  - `it_renders_a_fluid_container`

### `Tests/Unit/Components/HeroTest.php`

  - `it_renders_a_hero_with_image`
  - `it_renders_a_hero_with_content`

### `Tests/Unit/Components/InputTest.php`

  - `it_renders_an_input_with_label`
  - `it_renders_an_input_as_required`
  - `it_renders_an_input_with_help_text`

### `Tests/Unit/Components/NavItemTest.php`

  - `it_renders_a_nav_item_with_active_state`
  - `it_renders_a_nav_item_with_custom_classes`

### `Tests/Unit/Components/NavbarTest.php`

  - `it_renders_a_navbar_with_brand`
  - `it_renders_a_navbar_with_expand`
  - `it_renders_a_navbar_with_fixed_position`

### `Tests/Unit/Components/RadioTest.php`

  - `it_renders_a_radio_with_label`
  - `it_renders_a_radio_as_disabled`

### `Tests/Unit/Components/RowTest.php`

  - `it_renders_a_flex_row`
  - `it_renders_a_flex_no_wrap_row`

## Service providers

  - `Modules\Components\Providers\ComponentsServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
