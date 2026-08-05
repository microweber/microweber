<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use MicroweberPackages\Queue\Filament\Resources\FailedJobResource;
use MicroweberPackages\Queue\Filament\Resources\JobResource;

class QueuePlugin implements Plugin
{
    public function getId(): string
    {
        return 'microweber-queue';
    }

    public static function make(): static
    {
        /** @var static $instance */
        $instance = new static(); // @phpstan-ignore new.static

        return $instance;
    }

    public function register(Panel $panel): void
    {
        if (! (bool) config('microweber-queue.filament.enabled', true)) {
            return;
        }

        $panel->resources([
            JobResource::class,
            FailedJobResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
