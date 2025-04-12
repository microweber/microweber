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
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class ContentObserver  implements ShouldHandleEventsAfterCommit
{


    public function creating(Model $content): void
    {
        app()->content_repository->clearCache();

        event(new ContentIsCreating($content));
    }

    /**
     * Handle the Content "created" event.
     */
    public function created(Model $content): void
    {
        app()->content_repository->clearCache();

        event(new ContentWasCreated($content));
    }

    public function updating(Model $content): void
    {
        app()->content_repository->clearCache();

        event(new ContentIsUpdating($content));
    }

    /**
     * Handle the Content "updated" event.
     */
    public function updated(Model $content): void
    {
        app()->content_repository->clearCache();

        event(new ContentWasUpdated($content));
    }

    /**
     * Handle the Content "deleted" event.
     */
    public function deleted(Model $content): void
    {
        app()->content_repository->clearCache();

        event(new ContentWasDeleted($content));
    }

    /**
     * Handle the Content "restored" event.
     */
    public function restored(Model $content): void
    {
        app()->content_repository->clearCache();

        event(new ContentWasRestored($content));
    }

    /**
     * Handle the Content "forceDeleted" event.
     */
    public function forceDeleted(Model $content): void
    {
        app()->content_repository->clearCache();

        event(new ContentWasDestroyed($content));
    }
}
