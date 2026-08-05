<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Queue\Events\ProcessQueueEvent;
use MicroweberPackages\Queue\Listeners\ProcessQueueListener;

/**
 * Registers queue package event listeners without requiring laravel/framework's EventServiceProvider.
 */
class QueueEventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(ProcessQueueEvent::class, [ProcessQueueListener::class, 'handle']);
    }
}
