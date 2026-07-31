<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Support;

use MicroweberPackages\TemplateCustomCss\Contracts\OptionStoreInterface;

/**
 * Bridges to Microweber CMS get_option / save_option helpers.
 */
class CmsOptionStore implements OptionStoreInterface
{
    public function get(string $key, string $group): mixed
    {
        if (function_exists('get_option')) {
            return get_option($key, $group);
        }

        return false;
    }

    public function save(array|string $keyOrArray, mixed $value = null, ?string $group = null): bool
    {
        if (!function_exists('save_option')) {
            return false;
        }

        if (is_array($keyOrArray)) {
            $result = save_option($keyOrArray);

            return $result !== false;
        }

        $result = save_option($keyOrArray, $value, $group);

        return $result !== false;
    }
}
