<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Support;

use MicroweberPackages\TemplateCustomCss\Contracts\OptionStoreInterface;

/**
 * In-memory option store for standalone apps / unit tests.
 */
class ArrayOptionStore implements OptionStoreInterface
{
    /** @var array<string, array<string, mixed>> */
    protected array $options = [];

    public function get(string $key, string $group): mixed
    {
        return $this->options[$group][$key] ?? false;
    }

    public function save(array|string $keyOrArray, mixed $value = null, ?string $group = null): bool
    {
        if (is_array($keyOrArray)) {
            $keyRaw = $keyOrArray['option_key'] ?? '';
            $groupRaw = $keyOrArray['option_group'] ?? '';
            $key = is_string($keyRaw) ? $keyRaw : '';
            $groupName = is_string($groupRaw) ? $groupRaw : '';
            $val = $keyOrArray['option_value'] ?? '';
        } else {
            $key = $keyOrArray;
            $groupName = $group ?? '';
            $val = $value;
        }

        if ($key === '' || $groupName === '') {
            return false;
        }

        if (!isset($this->options[$groupName])) {
            $this->options[$groupName] = [];
        }

        $this->options[$groupName][$key] = $val;

        return true;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        return $this->options;
    }

    public function clear(): void
    {
        $this->options = [];
    }
}
