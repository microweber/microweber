# Form Builder

Microweber Form Builder – a field-type registry and schema-driven form-definition engine that turns plain PHP/JSON field definitions into **Filament v5** schema components.

Used by Microweber's Live Edit module-settings pages to render module option forms from a declarative array, with container layouts, conditional visibility, prefixing, validation-rule and default-state extraction, and multilanguage-aware fields.

## Features

- **Declarative schema → Filament components.** Describe a form as an array (or JSON) of field definitions and build real `Filament\Schemas\Components\Component` instances.
- **Pluggable field-type registry.** Register your own field types, or use the bundled Filament and Microweber custom resolvers.
- **Container layouts** — `section`, `grid`, `tabs`, `repeater`, `group`, `fieldset`.
- **Conditional visibility** via `visible_when` (operator-based) or legacy `show_when`.
- **Name prefixing** (e.g. `options.*`) and **current-state injection** for live visibility evaluation.
- **Validation-rule and default-state extraction** from the same schema.
- **Multilanguage** — fields flagged `translatable` get the `mwTranslatableOption()` macro when multilanguage is enabled.

## Installation

```bash
composer require microweber-packages/microweber-form-builder
```

Register the service provider (Microweber registers it automatically from its app bootstrap; in a standalone app add it manually):

```php
$app->register(MicroweberPackages\FormBuilder\Providers\FormBuilderServiceProvider::class);
```

The provider singletons the `FieldTypeRegistry` and `FormBuilder`, then boots the default
`FilamentFieldResolver` and `MwCustomFieldResolver` field types.

## Usage

### Build a form from an array

```php
use MicroweberPackages\FormBuilder\Facades\FormBuilder;

$schema = [
    ['type' => 'text',   'name' => 'title',  'label' => 'Title', 'required' => true],
    ['type' => 'textarea','name' => 'body',  'label' => 'Body'],
    [
        'type'   => 'select',
        'name'   => 'layout',
        'label'  => 'Layout',
        'options'=> ['grid' => 'Grid', 'list' => 'List'],
    ],
    [
        'type'   => 'number',
        'name'   => 'columns',
        'label'  => 'Columns',
        // Only visible when layout == grid
        'visible_when' => ['field' => 'layout', 'operator' => '=', 'value' => 'grid'],
    ],
];

// Returns an array<\Filament\Schemas\Components\Component>
$components = FormBuilder::fromArray($schema)
    ->prefix('options')          // field names become options.title, options.body, ...
    ->withState($currentValues)  // used for visibility evaluation
    ->build();
```

Inside a Filament page/schema you drop the built components straight in:

```php
public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
{
    return $schema->components(
        FormBuilder::fromArray($this->schemaItems)->withState($this->options)->build()
    );
}
```

This is exactly how Live Edit renders module settings (`LiveEditModuleSettings::schemaToFormFields()`).

### Build from JSON

```php
$engine = FormBuilder::fromJson($jsonString);     // throws InvalidArgumentException on bad JSON
$engine = FormBuilder::fromJsonFile('/path/to/form.json');
$components = $engine->build();
```

### Extract rules and defaults from the same schema

```php
$engine = FormBuilder::fromArray($schema)->prefix('options');

$rules    = $engine->validationRules(); // ['options.title' => 'required', ...]
$defaults = $engine->defaultState();    // ['options.columns' => 3, ...]
```

### Register a custom field type

A resolver is a `callable` (or a `FieldTypeInterface`) that receives the field-definition array
and returns a Filament component:

```php
use MicroweberPackages\FormBuilder\Facades\FormBuilder;
use Filament\Forms\Components\TextInput;

FormBuilder::registerFieldType('phone', function (array $field) {
    return TextInput::make($field['name'])
        ->label($field['label'] ?? null)
        ->tel();
});

FormBuilder::hasFieldType('phone');       // true
FormBuilder::getRegisteredTypes();        // ['text', 'textarea', ..., 'phone']
```

### Bundled field types

| Resolver | Types |
|----------|-------|
| `FilamentFieldResolver` | `text`, `textarea`, `number`, `select`, `toggle`, `toggle_buttons`, `checkbox`, `color`, `date`, `datetime`, `hidden`, `placeholder`, `radio` |
| `MwCustomFieldResolver` | `image`, `file_upload`, `color_picker`, `icon`, `icon_picker`, `link`, `link_picker`, `slider`, `range_slider`, `rich_editor`, `media_browser`, `title_with_slug`, `select_template` |
| `ContainerResolver` | `section`, `grid`, `tabs`, `repeater`, `group`, `fieldset` |

## Key classes

- `MicroweberPackages\FormBuilder\Facades\FormBuilder` — static entry point (`fromArray`, `fromJson`, `fromJsonFile`, `registerFieldType`, `hasFieldType`, `getRegisteredTypes`, `getRegistry`).
- `MicroweberPackages\FormBuilder\FormBuilder` — the facade root class.
- `MicroweberPackages\FormBuilder\FormBuilderEngine` — fluent builder: `prefix()`, `withState()`, `withRegistry()`, `build()`, `validationRules()`, `defaultState()`.
- `MicroweberPackages\FormBuilder\FieldTypeRegistry` — `register()`, `has()`, `resolve()`, `getRegisteredTypes()`, `unregister()`, `clear()`.
- `MicroweberPackages\FormBuilder\Contracts\FieldTypeInterface` — object resolver contract.
- `MicroweberPackages\FormBuilder\Resolvers\{FilamentFieldResolver, MwCustomFieldResolver, ContainerResolver}` — default field/container resolvers.
- `MicroweberPackages\FormBuilder\Providers\FormBuilderServiceProvider` — binds the registry + default resolvers.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Bozhidar Slaveykov
- Peter Ivanov
- Alexander Raikov

## License

MIT
