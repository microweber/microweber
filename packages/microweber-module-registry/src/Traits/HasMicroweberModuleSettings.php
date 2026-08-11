<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Traits;

/**
 * Generic settings bag (optional; many modules use options instead).
 */
trait HasMicroweberModuleSettings
{
    /** @var array<string, mixed> */
    public array $settings = [];

    /**
     * @return array<string, mixed>
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * @param  array<string, mixed>  $settings
     */
    public function setSettings(array $settings = []): void
    {
        $this->settings = $settings;
    }
}
