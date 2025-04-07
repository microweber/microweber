<?php

namespace Modules\Content\Observers;

use Illuminate\Database\Eloquent\Model;
use Modules\Content\Events\ContentIsCreating;
use Modules\Content\Events\ContentIsUpdating;
use Modules\Content\Events\ContentWasCreated;
use Modules\Content\Events\ContentWasDeleted;
use Modules\Content\Events\ContentWasDestroyed;
use Modules\Content\Events\ContentWasRestored;
use Modules\Content\Events\ContentWasUpdated;

class ContentObserver
{


    public function creating(Model $content): void
    {
        event(new ContentIsCreating($content));
    }

    /**
     * Handle the Content "created" event.
     */
    public function created(Model $content): void
    {
        event(new ContentWasCreated($content));
    }

    public function updating(Model $content): void
    {
        event(new ContentIsUpdating($content));
    }

    /**
     * Handle the Content "updated" event.
     */
    public function updated(Model $content): void
    {
        event(new ContentWasUpdated($content));
    }

    /**
     * Handle the Content "deleted" event.
     */
    public function deleted(Model $content): void
    {
        event(new ContentWasDeleted($content));
    }

    /**
     * Handle the Content "restored" event.
     */
    public function restored(Model $content): void
    {
        event(new ContentWasRestored($content));
    }

    /**
     * Handle the Content "forceDeleted" event.
     */
    public function forceDeleted(Model $content): void
    {
        event(new ContentWasDestroyed($content));
    }
}
