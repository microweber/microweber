<?php

namespace Modules\Content\Models\Concerns;

/**
 * task-2026-06-06-schedpublish — enforce scheduled publishing.
 *
 * The admin content form lets an author set a future `posted_at` and shows
 * "Scheduled — publishes in X", but nothing enforced it: a row with
 * is_active=1 and a future posted_at went live on the public site the instant
 * it was saved, and no scheduler ever flipped a draft on at the scheduled
 * time. This trait registers a global scope that hides content whose publish
 * date is still in the future from public (non-admin) requests. Once
 * `posted_at <= now()` the row simply re-enters every public query — no cron
 * is required.
 *
 * Admins / Live Edit (is_admin()) bypass the scope so authors can preview
 * scheduled content. `posted_at` is stamped to the save time for ordinary
 * content (see ContentManagerCrud), so only explicitly future-dated rows are
 * ever affected; a NULL posted_at is always treated as already-published.
 *
 * It is delivered as a trait (not a Content::booted() override) because Post,
 * Page and Product all extend Content and each defines its own booted() that
 * registers a subtype scope WITHOUT calling parent::booted(). A trait boot
 * method (bootHasScheduledPublishingScope) is invoked additively by Laravel
 * for the base class AND every subclass, so the scheduling guard applies
 * uniformly to posts, pages and products without colliding with their
 * existing booted() overrides.
 */
trait HasScheduledPublishingScope
{
    public static function bootHasScheduledPublishingScope(): void
    {
        static::addGlobalScope('mwScheduledPublish', function ($query) {
            // Admins (and the Live Edit canvas, which runs in an admin
            // session) must still see scheduled content so they can preview
            // it before it goes live.
            if (function_exists('is_admin') && is_admin()) {
                return;
            }

            $table = $query->getModel()->getTable();
            $query->where(function ($inner) use ($table) {
                $inner->whereNull($table . '.posted_at')
                    ->orWhere($table . '.posted_at', '<=', now());
            });
        });
    }
}
