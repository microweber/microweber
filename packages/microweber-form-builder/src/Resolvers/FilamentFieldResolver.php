<?php

namespace MicroweberPackages\FormBuilder\Resolvers;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use MicroweberPackages\FormBuilder\FieldTypeRegistry;

class FilamentFieldResolver
{
    /**
     * Register all default Filament field types into the registry.
     */
    public static function register(FieldTypeRegistry $registry): void
    {
        $registry->register('text', function (array $field) {
            $component = TextInput::make($field['name'])
                ->label($field['label'] ?? self::titlelize($field['name']));

            self::applyCommon($component, $field);

            return $component;
        });

        $registry->register('textarea', function (array $field) {
            $component = Textarea::make($field['name'])
                ->label($field['label'] ?? self::titlelize($field['name']));

            if (isset($field['options']['rows'])) {
                $component->rows((int) $field['options']['rows']);
            }

            self::applyCommon($component, $field);

            return $component;
        });

        $registry->register('number', function (array $field) {
            $component = TextInput::make($field['name'])
                ->label($field['label'] ?? self::titlelize($field['name']))
                ->numeric();

            $options = $field['options'] ?? [];
            if (isset($options['min'])) {
                $component->minValue($options['min']);
            }
            if (isset($options['max'])) {
                $component->maxValue($options['max']);
            }
            if (isset($options['step'])) {
                $component->step($options['step']);
            }

            self::applyCommon($component, $field);

            return $component;
        });

        $registry->register('select', function (array $field) {
            $component = Select::make($field['name'])
                ->label($field['label'] ?? self::titlelize($field['name']))
                ->options($field['options'] ?? []);

            if (!empty($field['extra']['searchable'])) {
                $component->searchable();
            }
            if (!empty($field['extra']['multiple'])) {
                $component->multiple();
            }

            self::applyCommon($component, $field);

            return $component;
        });

        $registry->register('toggle', function (array $field) {
            $component = Toggle::make($field['name'])
                ->label($field['label'] ?? self::titlelize($field['name']));

            self::applyCommon($component, $field);

            return $component;
        });

        $registry->register('toggle_buttons', function (array $field) {
            $component = ToggleButtons::make($field['name'])
                ->label($field['label'] ?? self::titlelize($field['name']))
                ->options($field['options'] ?? []);

            if (!empty($field['extra']['inline'])) {
                $component->inline();
            }
            if (!empty($field['extra']['icons'])) {
                $component->icons($field['extra']['icons']);
            }

            self::applyCommon($component, $field);

            return $component;
        });

        $registry->register('checkbox', function (array $field) {
            $component = Checkbox::make($field['name'])
                ->label($field['label'] ?? self::titlelize($field['name']));

            self::applyCommon($component, $field);

            return $component;
        });

        $registry->register('color', function (array $field) {
            // Use MwColorPicker if available to match the old schemaToFormFields behavior
            if (class_exists(\MicroweberPackages\Filament\Forms\Components\MwColorPicker::class)) {
                $component = \MicroweberPackages\Filament\Forms\Components\MwColorPicker::make($field['name'])
                    ->label($field['label'] ?? self::titlelize($field['name']))
                    ->hex();
            } else {
                $component = ColorPicker::make($field['name'])
                    ->label($field['label'] ?? self::titlelize($field['name']));
            }

            self::applyCommon($component, $field);

            return $component;
        });

        $registry->register('date', function (array $field) {
            $component = DatePicker::make($field['name'])
                ->label($field['label'] ?? self::titlelize($field['name']));

            self::applyCommon($component, $field);

            return $component;
        });

        $registry->register('datetime', function (array $field) {
            $component = DateTimePicker::make($field['name'])
                ->label($field['label'] ?? self::titlelize($field['name']));

            self::applyCommon($component, $field);

            return $component;
        });

        $registry->register('hidden', function (array $field) {
            $component = Hidden::make($field['name']);

            if (array_key_exists('default', $field) && $field['default'] !== null) {
                $component->default($field['default']);
            }

            return $component;
        });

        $registry->register('placeholder', function (array $field) {
            $component = Placeholder::make($field['name'])
                ->label($field['label'] ?? self::titlelize($field['name']));

            if (isset($field['extra']['content'])) {
                $component->content($field['extra']['content']);
            }

            return $component;
        });

        $registry->register('radio', function (array $field) {
            $component = Radio::make($field['name'])
                ->label($field['label'] ?? self::titlelize($field['name']))
                ->options($field['options'] ?? []);

            self::applyCommon($component, $field);

            return $component;
        });
    }

    /**
     * Apply common field properties.
     */
    public static function applyCommon($component, array $field): void
    {
        if (!empty($field['placeholder'])) {
            if (method_exists($component, 'placeholder')) {
                $component->placeholder($field['placeholder']);
            }
        }

        if (!empty($field['help'])) {
            if (method_exists($component, 'helperText')) {
                $component->helperText($field['help']);
            }
        }

        if (array_key_exists('default', $field) && $field['default'] !== null) {
            $component->default($field['default']);
        }

        if (!empty($field['required'])) {
            $component->required();
        }

        if (!empty($field['live'])) {
            $component->live();
        }

        if (!empty($field['columns'])) {
            $component->columnSpan($field['columns']);
        }

        if (!empty($field['column_span']) && $field['column_span'] === 'full') {
            $component->columnSpanFull();
        }

        if (!empty($field['validation'])) {
            $rules = $field['validation'];
            if (is_string($rules)) {
                $rules = explode('|', $rules);
            }
            $component->rules($rules);
        }
    }

    /**
     * Convert a field name to a human-readable title.
     */
    public static function titlelize(string $name): string
    {
        // Remove prefix like "options."
        $name = preg_replace('/^[a-zA-Z]+\./', '', $name);

        if (function_exists('titlelize')) {
            return titlelize($name);
        }

        $slug = preg_replace('/[-_]/', ' ', $name);
        return ucwords($slug);
    }
}
