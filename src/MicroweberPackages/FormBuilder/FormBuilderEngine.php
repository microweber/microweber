<?php

namespace MicroweberPackages\FormBuilder;

use Filament\Schemas\Components\Utilities\Get;
use MicroweberPackages\FormBuilder\Resolvers\ContainerResolver;
use MicroweberPackages\FormBuilder\Resolvers\FilamentFieldResolver;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

class FormBuilderEngine
{
    protected array $fields = [];
    protected ?string $prefix = null;
    protected array $state = [];
    protected ?FieldTypeRegistry $registry = null;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * Set a prefix for all field names (e.g. 'options').
     */
    public function prefix(string $prefix): self
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Provide current state for conditional visibility evaluation.
     */
    public function withState(array $state): self
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Use a specific registry (otherwise uses the global singleton).
     */
    public function withRegistry(FieldTypeRegistry $registry): self
    {
        $this->registry = $registry;
        return $this;
    }

    /**
     * Get the field type registry.
     */
    protected function getRegistry(): FieldTypeRegistry
    {
        if ($this->registry) {
            return $this->registry;
        }

        return app(FieldTypeRegistry::class);
    }

    /**
     * Build the schema into Filament components.
     *
     * @return array<\Filament\Schemas\Components\Component>
     */
    public function build(): array
    {
        return $this->buildFields($this->fields);
    }

    /**
     * Recursively build fields from definitions.
     */
    protected function buildFields(array $fields): array
    {
        $components = [];

        foreach ($fields as $fieldDef) {
            if (!is_array($fieldDef)) {
                continue;
            }

            $type = $fieldDef['type'] ?? null;
            if (!$type) {
                continue;
            }

            // Check if it's a container type
            if (ContainerResolver::isContainer($type)) {
                $components[] = ContainerResolver::resolve($fieldDef, function (array $children) {
                    return $this->buildFields($children);
                });
                continue;
            }

            // Apply prefix to the name
            $fieldDef = $this->applyPrefix($fieldDef);

            // Apply translatable if needed
            $component = $this->resolveField($fieldDef);

            if ($component !== null) {
                // Apply visibility conditions
                $component = $this->applyVisibility($component, $fieldDef);

                $components[] = $component;
            }
        }

        return $components;
    }

    /**
     * Apply prefix to field name.
     */
    protected function applyPrefix(array $fieldDef): array
    {
        if ($this->prefix && !empty($fieldDef['name'])) {
            $name = $fieldDef['name'];
            // Don't double-prefix
            if (strpos($name, $this->prefix . '.') !== 0) {
                $fieldDef['name'] = $this->prefix . '.' . $name;
            }
        }

        return $fieldDef;
    }

    /**
     * Resolve a single field definition into a component.
     */
    protected function resolveField(array $fieldDef)
    {
        $type = $fieldDef['type'];
        $registry = $this->getRegistry();

        if (!$registry->has($type)) {
            // Skip unknown types silently in production, throw in debug
            if (config('app.debug', false)) {
                throw new \InvalidArgumentException("Unknown field type: '{$type}'");
            }
            return null;
        }

        $component = $registry->resolve($type, $fieldDef);

        // Apply translatable macro if applicable
        if (!empty($fieldDef['translatable'])) {
            $component = $this->applyTranslatable($component, $fieldDef);
        }

        return $component;
    }

    /**
     * Apply translatable macro to a field component.
     */
    protected function applyTranslatable($component, array $fieldDef)
    {
        if (!class_exists(MultilanguageHelpers::class)
            || !MultilanguageHelpers::multilanguageIsEnabled()) {
            return $component;
        }

        // `mwTranslatableOption` is registered via Field::macro() (Laravel
        // Macroable): method_exists() does NOT detect macros (they dispatch
        // through __call), so detect with hasMacro(), keeping method_exists()
        // as a fallback for a real method.
        $componentClass = get_class($component);
        $hasMacro = (method_exists($componentClass, 'hasMacro')
                && $componentClass::hasMacro('mwTranslatableOption'))
            || method_exists($component, 'mwTranslatableOption');

        if (!$hasMacro) {
            return $component;
        }

        // A broken/version-incompatible translatable macro must never crash the
        // whole form — degrade to the plain (non-translatable) field instead.
        try {
            return $component->mwTranslatableOption();
        } catch (\Throwable $e) {
            return $component;
        }
    }

    /**
     * Apply visibility conditions to a component.
     */
    protected function applyVisibility($component, array $fieldDef)
    {
        $visibleWhen = $fieldDef['visible_when'] ?? null;

        if (!$visibleWhen) {
            // Legacy support: 'show_when' as used in old schemaToFormFields
            $showWhen = $fieldDef['show_when'] ?? null;
            if ($showWhen && is_array($showWhen)) {
                $state = $this->state;
                $prefix = $this->prefix;
                $component->visible(function () use ($showWhen, $state) {
                    foreach ($showWhen as $condition) {
                        if (isset($state[$condition]) && $state[$condition]) {
                            return true;
                        }
                    }
                    return false;
                });
            }
            return $component;
        }

        $prefix = $this->prefix;

        if (isset($visibleWhen['field'])) {
            $conditionField = $visibleWhen['field'];
            if ($prefix && strpos($conditionField, $prefix . '.') !== 0) {
                $conditionField = $prefix . '.' . $conditionField;
            }

            $operator = $visibleWhen['operator'] ?? '=';
            $value = $visibleWhen['value'] ?? null;

            $component->visible(function (Get $get) use ($conditionField, $operator, $value) {
                $fieldValue = $get($conditionField);
                return self::evaluateCondition($fieldValue, $operator, $value);
            });
        }

        return $component;
    }

    /**
     * Evaluate a visibility condition.
     */
    public static function evaluateCondition($fieldValue, string $operator, $conditionValue): bool
    {
        return match ($operator) {
            '=', '==', 'equals' => $fieldValue == $conditionValue,
            '!=', '!==', 'not_equals' => $fieldValue != $conditionValue,
            'in' => is_array($conditionValue) && in_array($fieldValue, $conditionValue),
            'not_in' => is_array($conditionValue) && !in_array($fieldValue, $conditionValue),
            '>' => $fieldValue > $conditionValue,
            '<' => $fieldValue < $conditionValue,
            '>=' => $fieldValue >= $conditionValue,
            '<=' => $fieldValue <= $conditionValue,
            'contains' => is_string($fieldValue) && str_contains($fieldValue, $conditionValue),
            'not_empty', 'filled' => !empty($fieldValue),
            'empty', 'blank' => empty($fieldValue),
            default => true,
        };
    }

    /**
     * Extract validation rules from the schema.
     *
     * @return array<string, string> e.g. ['name' => 'required|string', ...]
     */
    public function validationRules(): array
    {
        return $this->extractValidationRules($this->fields);
    }

    /**
     * Recursively extract validation rules.
     */
    protected function extractValidationRules(array $fields): array
    {
        $rules = [];

        foreach ($fields as $fieldDef) {
            if (!is_array($fieldDef)) {
                continue;
            }

            $type = $fieldDef['type'] ?? null;
            if (!$type) {
                continue;
            }

            // Recurse into containers
            if (ContainerResolver::isContainer($type)) {
                $schema = $fieldDef['schema'] ?? [];
                if (!empty($schema)) {
                    $rules = array_merge($rules, $this->extractValidationRules($schema));
                }
                if ($type === 'tabs' && !empty($fieldDef['tabs'])) {
                    foreach ($fieldDef['tabs'] as $tab) {
                        if (!empty($tab['schema'])) {
                            $rules = array_merge($rules, $this->extractValidationRules($tab['schema']));
                        }
                    }
                }
                continue;
            }

            $fieldDef = $this->applyPrefix($fieldDef);
            $name = $fieldDef['name'] ?? null;
            if (!$name) {
                continue;
            }

            $fieldRules = [];

            if (!empty($fieldDef['required'])) {
                $fieldRules[] = 'required';
            }

            if (!empty($fieldDef['validation'])) {
                $extraRules = $fieldDef['validation'];
                if (is_string($extraRules)) {
                    $extraRules = explode('|', $extraRules);
                }
                $fieldRules = array_merge($fieldRules, $extraRules);
            }

            if (!empty($fieldRules)) {
                $rules[$name] = implode('|', $fieldRules);
            }
        }

        return $rules;
    }

    /**
     * Extract default state values from the schema.
     *
     * @return array<string, mixed> e.g. ['name' => '', ...]
     */
    public function defaultState(): array
    {
        return $this->extractDefaultState($this->fields);
    }

    /**
     * Recursively extract default state.
     */
    protected function extractDefaultState(array $fields): array
    {
        $defaults = [];

        foreach ($fields as $fieldDef) {
            if (!is_array($fieldDef)) {
                continue;
            }

            $type = $fieldDef['type'] ?? null;
            if (!$type) {
                continue;
            }

            // Recurse into containers
            if (ContainerResolver::isContainer($type)) {
                $schema = $fieldDef['schema'] ?? [];
                if (!empty($schema)) {
                    $defaults = array_merge($defaults, $this->extractDefaultState($schema));
                }
                if ($type === 'tabs' && !empty($fieldDef['tabs'])) {
                    foreach ($fieldDef['tabs'] as $tab) {
                        if (!empty($tab['schema'])) {
                            $defaults = array_merge($defaults, $this->extractDefaultState($tab['schema']));
                        }
                    }
                }
                continue;
            }

            $fieldDef = $this->applyPrefix($fieldDef);
            $name = $fieldDef['name'] ?? null;
            if (!$name) {
                continue;
            }

            if (array_key_exists('default', $fieldDef)) {
                $defaults[$name] = $fieldDef['default'];
            }
        }

        return $defaults;
    }
}
