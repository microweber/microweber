<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Traits;

use MicroweberPackages\ModuleRegistry\Support\CmsHelpers;

/**
 * Module options (persisted CMS options keyed by module instance id).
 *
 * Soft-depends on CMS helpers `get_module_options` / `save_module_option`.
 *
 * @phpstan-require-extends \MicroweberPackages\ModuleRegistry\Abstract\BaseModule
 */
trait HasMicroweberModuleOptions
{
    /**
     * @return list<array<string, mixed>>|array<string, mixed>
     *
     * @internal
     */
    public function getOptionsFull(): array
    {
        $moduleId = $this->resolveModuleInstanceId();
        $moduleType = static::$module !== '' ? static::$module : null;

        return CmsHelpers::getModuleOptions($moduleId, $moduleType);
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        $savedOptions = $this->getOptionsFull();
        $options = [];

        foreach ($savedOptions as $option) {
            if (! is_array($option)) {
                continue;
            }
            if (isset($option['option_key']) && is_string($option['option_key'])) {
                $options[$option['option_key']] = $option['option_value'] ?? null;
            }
        }

        return $options;
    }

    public function saveOption(string $key, mixed $value = null): mixed
    {
        $moduleId = $this->resolveModuleInstanceId();
        $moduleType = static::$module !== '' ? static::$module : null;

        return CmsHelpers::saveModuleOption($key, $value, $moduleId !== '' ? $moduleId : null, $moduleType);
    }

    public function getOption(string $key, mixed $default = null): mixed
    {
        $options = $this->getOptions();

        if ($options !== [] && array_key_exists($key, $options) && $options[$key]) {
            return $options[$key];
        }

        return $default;
    }

    /**
     * @return list<string>
     */
    public static function getTranslatableOptionKeys(): array
    {
        return static::$translatableOptions;
    }

    private function resolveModuleInstanceId(): string
    {
        if (! isset($this->params['id'])) {
            return '';
        }

        $id = $this->params['id'];
        if (is_string($id)) {
            return $id;
        }
        if (is_int($id) || is_float($id)) {
            return (string) $id;
        }

        return '';
    }
}
