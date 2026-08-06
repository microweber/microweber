<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;

/**
 * Event bindings for notifications (reserved for future listeners).
 */
class NotificationEventServiceProvider extends EventServiceProvider
{
    /**
     * @var array<class-string, list<class-string>>
     */
    protected $listen = [];
}
