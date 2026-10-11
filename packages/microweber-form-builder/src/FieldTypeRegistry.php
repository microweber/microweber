<?php

namespace MicroweberPackages\FormBuilder;

use MicroweberPackages\FormBuilder\Contracts\FieldTypeInterface;

class FieldTypeRegistry
{
    /**
     * @var array<string, callable|FieldTypeInterface>
     */
    protected array $types = [];

    /**
     * Register a field type resolver.
     *
     * @param string $type The type key (e.g. 'text', 'image', 'color_picker')
     * @param callable|FieldTypeInterface $resolver
     */
    public function register(string $type, callable|FieldTypeInterface $resolver): void
    {
        $this->types[$type] = $resolver;
    }

    /**
     * Check if a field type is registered.
     */
    public function has(string $type): bool
    {
        return isset($this->types[$type]);
    }

    /**
     * Resolve a field definition into a Filament component.
     *
     * @param string $type
     * @param array $field
     * @return \Filament\Schemas\Components\Component
     *
     * @throws \InvalidArgumentException
     */
    public function resolve(string $type, array $field)
    {
        if (!$this->has($type)) {
            throw new \InvalidArgumentException("Unknown field type: '{$type}'. Register it via FormBuilder::registerFieldType().");
        }

        $resolver = $this->types[$type];

        if ($resolver instanceof FieldTypeInterface) {
            return $resolver->resolve($field);
        }

        return call_user_func($resolver, $field);
    }

    /**
     * Get all registered type keys.
     *
     * @return string[]
     */
    public function getRegisteredTypes(): array
    {
        return array_keys($this->types);
    }

    /**
     * Remove a registered type.
     */
    public function unregister(string $type): void
    {
        unset($this->types[$type]);
    }

    /**
     * Clear all registered types.
     */
    public function clear(): void
    {
        $this->types = [];
    }
}
