<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Traits;

/**
 * Instance params bag for a rendered module (id, attributes, etc.).
 */
trait HasMicroweberModuleParams
{
    /** @var array<string, mixed> */
    public array $params = [];

    /**
     * @return array<string, mixed>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    public function getModuleId(): mixed
    {
        return $this->params['id'] ?? null;
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function setParams(array $params = []): void
    {
        $this->params = $params;
    }
}
