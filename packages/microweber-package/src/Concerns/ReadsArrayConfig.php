<?php

namespace MicroweberPackages\Package\Concerns;

/**
 * Type-coercing readers over an in-memory `$this->config` array.
 *
 * Used by manager services that load their settings into a `protected array
 * $config` property and need safe string/int/bool access with a default. Keeps
 * the (previously copy-pasted, byte-identical) coercion logic in one place.
 *
 * @property array $config
 */
trait ReadsArrayConfig
{
    protected function configString(string $key, string $default = ''): string
    {
        $value = $this->config[$key] ?? $default;
        if (is_string($value)) {
            return $value;
        }
        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        return $default;
    }

    protected function configInt(string $key, int $default = 0): int
    {
        $value = $this->config[$key] ?? $default;
        if (is_int($value)) {
            return $value;
        }
        if (is_numeric($value)) {
            return (int) $value;
        }

        return $default;
    }

    protected function configBool(string $key, bool $default = false): bool
    {
        $value = $this->config[$key] ?? $default;
        if (is_bool($value)) {
            return $value;
        }
        if (is_int($value) || is_string($value)) {
            return (bool) $value;
        }

        return $default;
    }
}
