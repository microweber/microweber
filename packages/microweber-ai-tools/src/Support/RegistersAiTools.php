<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Support;

use MicroweberPackages\AiTools\Contracts\ToolRegistryInterface;

/**
 * Helper for Laravel service providers that want to register AI tools.
 *
 * Usage in a module/service provider boot/register method:
 *
 *   $this->registerAiTools([
 *       MyContentSearchTool::class,
 *       MyContentCreateTool::class,
 *   ]);
 */
trait RegistersAiTools
{
    /**
     * @param list<class-string> $toolClasses
     */
    protected function registerAiTools(array $toolClasses): void
    {
        if ($toolClasses === []) {
            return;
        }

        if (!$this->app->bound(ToolRegistryInterface::class)) {
            return;
        }

        /** @var ToolRegistryInterface $registry */
        $registry = $this->app->make(ToolRegistryInterface::class);
        $registry->registerMany($toolClasses);
    }
}
