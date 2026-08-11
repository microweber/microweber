<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Traits;

/**
 * Live Edit action handlers bag for modules.
 */
trait HasMicroweberModuleLiveEditHandleAction
{
    /** @var array<string, mixed> */
    public array $liveEditActions = [];

    /**
     * @return array<string, mixed>
     */
    public function getliveEditActions(): array
    {
        return $this->liveEditActions;
    }

    /**
     * @param  array<string, mixed>  $liveEditActions
     */
    public function setliveEditActions(array $liveEditActions = []): void
    {
        $this->liveEditActions = $liveEditActions;
    }
}
