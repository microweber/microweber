<?php

namespace MicroweberPackages\Core\Concerns;

use MicroweberPackages\Core\Events\ModelWasCreated;
use MicroweberPackages\Core\Events\ModelWasDeleted;
use MicroweberPackages\Core\Events\ModelWasUpdated;

/**
 * Fire generic created/updated/deleted lifecycle events (carrying the model) so
 * other modules can react to any model that uses this trait. Uses model boot
 * hooks (which stack), so it never conflicts with a model's own events.
 *
 * Models with a dedicated event set (Order, Product, Content, …) keep those and
 * do not need this trait.
 */
trait FiresModelLifecycleEvents
{
    public static function bootFiresModelLifecycleEvents(): void
    {
        static::created(function ($model) {
            event(new ModelWasCreated($model));
        });
        static::updated(function ($model) {
            event(new ModelWasUpdated($model));
        });
        static::deleted(function ($model) {
            event(new ModelWasDeleted($model));
        });
    }
}
