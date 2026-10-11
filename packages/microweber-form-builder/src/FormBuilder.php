<?php

namespace MicroweberPackages\FormBuilder;

use MicroweberPackages\FormBuilder\Contracts\FieldTypeInterface;

class FormBuilder
{
    /**
     * Create a FormBuilderEngine from an array of field definitions.
     *
     * @param array $fields
     * @return FormBuilderEngine
     */
    public static function fromArray(array $fields): FormBuilderEngine
    {
        return new FormBuilderEngine($fields);
    }

    /**
     * Create a FormBuilderEngine from a JSON string.
     *
     * @param string $json
     * @return FormBuilderEngine
     *
     * @throws \InvalidArgumentException
     */
    public static function fromJson(string $json): FormBuilderEngine
    {
        $fields = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON: ' . json_last_error_msg());
        }

        if (!is_array($fields)) {
            throw new \InvalidArgumentException('JSON must decode to an array of field definitions.');
        }

        return new FormBuilderEngine($fields);
    }

    /**
     * Create a FormBuilderEngine from a JSON file path.
     *
     * @param string $path
     * @return FormBuilderEngine
     *
     * @throws \InvalidArgumentException
     */
    public static function fromJsonFile(string $path): FormBuilderEngine
    {
        if (!is_file($path)) {
            throw new \InvalidArgumentException("JSON file not found: {$path}");
        }

        $json = file_get_contents($path);

        return self::fromJson($json);
    }

    /**
     * Register a custom field type in the global registry.
     *
     * @param string $type
     * @param callable|FieldTypeInterface $resolver
     */
    public static function registerFieldType(string $type, callable|FieldTypeInterface $resolver): void
    {
        app(FieldTypeRegistry::class)->register($type, $resolver);
    }

    /**
     * Check if a field type is registered.
     */
    public static function hasFieldType(string $type): bool
    {
        return app(FieldTypeRegistry::class)->has($type);
    }

    /**
     * Get all registered field type keys.
     *
     * @return string[]
     */
    public static function getRegisteredTypes(): array
    {
        return app(FieldTypeRegistry::class)->getRegisteredTypes();
    }

    /**
     * Get the field type registry instance.
     */
    public static function getRegistry(): FieldTypeRegistry
    {
        return app(FieldTypeRegistry::class);
    }
}
