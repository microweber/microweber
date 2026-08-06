<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Services;

/**
 * Placeholder for future email-notification orchestration.
 *
 * Kept for backward compatibility with the old CMS manager class.
 */
class EmailNotificationsManager
{
    /**
     * Optional host application reference (CMS `mw()` / Laravel app).
     */
    public mixed $app = null;

    public function __construct(mixed $app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } elseif (function_exists('mw')) {
            try {
                $this->app = mw();
            } catch (\Throwable) {
                $this->app = null;
            }
        }
    }
}
