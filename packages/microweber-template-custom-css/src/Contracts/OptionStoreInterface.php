<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Contracts;

/**
 * Abstraction for reading/writing CSS content stored as options.
 *
 * Microweber CMS implements this via get_option/save_option.
 * Standalone apps can use the array/file-backed ArrayOptionStore.
 */
interface OptionStoreInterface
{
    public function get(string $key, string $group): mixed;

    /**
     * @param  array{option_key?: string, option_group?: string, option_value?: mixed}|string  $keyOrArray
     */
    public function save(array|string $keyOrArray, mixed $value = null, ?string $group = null): bool;
}
