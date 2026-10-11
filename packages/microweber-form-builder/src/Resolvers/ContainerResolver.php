<?php

namespace MicroweberPackages\FormBuilder\Resolvers;

use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use MicroweberPackages\FormBuilder\FieldTypeRegistry;

class ContainerResolver
{
    /**
     * Container type keys.
     */
    public const CONTAINER_TYPES = ['section', 'grid', 'tabs', 'repeater', 'group', 'fieldset'];

    /**
     * Check if a type string is a container type.
     */
    public static function isContainer(string $type): bool
    {
        return in_array($type, self::CONTAINER_TYPES);
    }

    /**
     * Resolve a container definition into a Filament component.
     *
     * @param array $definition The container definition
     * @param callable $buildChildren Callback to recursively build child schema
     * @return \Filament\Schemas\Components\Component
     */
    public static function resolve(array $definition, callable $buildChildren)
    {
        $type = $definition['type'];

        return match ($type) {
            'section' => self::resolveSection($definition, $buildChildren),
            'grid' => self::resolveGrid($definition, $buildChildren),
            'tabs' => self::resolveTabs($definition, $buildChildren),
            'repeater' => self::resolveRepeater($definition, $buildChildren),
            'group' => self::resolveGroup($definition, $buildChildren),
            'fieldset' => self::resolveFieldset($definition, $buildChildren),
            default => throw new \InvalidArgumentException("Unknown container type: '{$type}'"),
        };
    }

    protected static function resolveSection(array $def, callable $buildChildren)
    {
        $component = Section::make($def['label'] ?? '');

        if (!empty($def['schema'])) {
            $component->schema($buildChildren($def['schema']));
        }

        if (!empty($def['columns'])) {
            $component->columns((int) $def['columns']);
        }

        if (!empty($def['collapsed'])) {
            $component->collapsed();
        }

        if (!empty($def['collapsible'])) {
            $component->collapsible();
        }

        return $component;
    }

    protected static function resolveGrid(array $def, callable $buildChildren)
    {
        $columns = $def['columns'] ?? 2;
        $component = Grid::make((int) $columns);

        if (!empty($def['schema'])) {
            $component->schema($buildChildren($def['schema']));
        }

        return $component;
    }

    protected static function resolveTabs(array $def, callable $buildChildren)
    {
        $label = $def['label'] ?? 'Tabs';
        $component = Tabs::make($label);

        $tabComponents = [];

        if (!empty($def['tabs'])) {
            foreach ($def['tabs'] as $tab) {
                $tabComponent = Tabs\Tab::make($tab['label'] ?? 'Tab');

                if (!empty($tab['schema'])) {
                    $tabComponent->schema($buildChildren($tab['schema']));
                }

                if (!empty($tab['icon'])) {
                    $tabComponent->icon($tab['icon']);
                }

                $tabComponents[] = $tabComponent;
            }
        }

        $component->schema($tabComponents);

        return $component;
    }

    protected static function resolveRepeater(array $def, callable $buildChildren)
    {
        $name = $def['name'] ?? 'items';
        $component = Repeater::make($name);

        if (!empty($def['label'])) {
            $component->label($def['label']);
        }

        if (!empty($def['schema'])) {
            $component->schema($buildChildren($def['schema']));
        }

        if (isset($def['min'])) {
            $component->minItems((int) $def['min']);
        }
        if (isset($def['max'])) {
            $component->maxItems((int) $def['max']);
        }

        if (!empty($def['collapsed'])) {
            $component->collapsed();
        }

        if (!empty($def['collapsible'])) {
            $component->collapsible();
        }

        return $component;
    }

    protected static function resolveGroup(array $def, callable $buildChildren)
    {
        $component = Group::make();

        if (!empty($def['schema'])) {
            $component->schema($buildChildren($def['schema']));
        }

        if (!empty($def['columns'])) {
            $component->columns((int) $def['columns']);
        }

        return $component;
    }

    protected static function resolveFieldset(array $def, callable $buildChildren)
    {
        $label = $def['label'] ?? '';
        $component = Fieldset::make($label);

        if (!empty($def['schema'])) {
            $component->schema($buildChildren($def['schema']));
        }

        if (!empty($def['columns'])) {
            $component->columns((int) $def['columns']);
        }

        return $component;
    }
}
