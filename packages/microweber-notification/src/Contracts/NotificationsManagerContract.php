<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Contracts;

/**
 * Public API for notification management used by the CMS and standalone apps.
 */
interface NotificationsManagerContract
{
    /**
     * Persist a legacy-style notification to admin notifiables.
     *
     * @param  array<string, mixed>|string  $params
     * @return array<string, mixed>
     */
    public function save(array|string $params): array;

    /**
     * @param  array<string, mixed>|string|false  $params
     * @return array<int, mixed>|int
     */
    public function get(array|string|false $params = false): array|int;

    public function get_unread_count(): int;
}
