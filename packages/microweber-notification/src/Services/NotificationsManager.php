<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use MicroweberPackages\Notification\Contracts\NotificationsManagerContract;
use MicroweberPackages\Notification\Models\Notification;
use MicroweberPackages\Notification\Notifications\LegacyNotification;

/**
 * Application-facing notification manager (legacy CMS API preserved).
 *
 * Standalone apps can bind a custom admin_user_model in config.
 */
class NotificationsManager implements NotificationsManagerContract
{
    public string $table = 'notifications';

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

    /**
     * @param  array<string, mixed>|string  $params
     * @return array<string, mixed>
     */
    public function save(array|string $params): array
    {
        $params = $this->normalizeParams($params);

        $notifiables = $this->resolveAdminNotifiables();
        foreach ($notifiables as $notifiable) {
            NotificationFacade::send($notifiable, new LegacyNotification($params));
        }

        return [];
    }

    /**
     * @param  array<string, mixed>|string|false  $params
     * @return array<int, mixed>|int
     */
    public function get(array|string|false $params = false): array|int
    {
        $params = $this->normalizeParams($params === false ? [] : $params);

        if (isset($params['count'])) {
            return $this->get_unread_count();
        }

        return $this->formatUnreadMessages();
    }

    public function get_unread_count(): int
    {
        return (int) Notification::query()->count();
    }

    /**
     * @deprecated Legacy no-op kept for API compatibility.
     *
     * @param  mixed  $id
     * @return array<string, mixed>
     */
    public function read(mixed $id): array
    {
        return [];
    }

    /**
     * @deprecated Legacy no-op kept for API compatibility.
     *
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    public function read_selected(array $params): array
    {
        return [];
    }

    /**
     * @deprecated Legacy no-op kept for API compatibility.
     *
     * @return array<string, mixed>
     */
    public function mark_as_read(mixed $module): array
    {
        return [];
    }

    /**
     * @deprecated Legacy no-op kept for API compatibility.
     *
     * @return array<string, mixed>
     */
    public function reset(mixed $id = false): array
    {
        return [];
    }

    /**
     * @deprecated Legacy no-op kept for API compatibility.
     *
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    public function reset_selected(array $params): array
    {
        return [];
    }

    /**
     * @deprecated Legacy no-op kept for API compatibility.
     *
     * @param  array<string, mixed>|false  $params
     * @return array<string, mixed>|bool
     */
    public function mark_all_as_read(array|false $params = false): array|bool
    {
        return [];
    }

    /**
     * @deprecated Legacy no-op kept for API compatibility.
     *
     * @return array<string, mixed>|bool
     */
    public function delete(mixed $id): array|bool
    {
        return [];
    }

    /**
     * @deprecated Legacy no-op kept for API compatibility.
     *
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    public function delete_selected(array $params): array
    {
        return [];
    }

    /**
     * @deprecated Legacy no-op kept for API compatibility.
     *
     * @return array<string, mixed>|bool
     */
    public function delete_for_module(mixed $module): array|bool
    {
        return [];
    }

    /**
     * @deprecated Legacy no-op kept for API compatibility.
     *
     * @return array<string, mixed>
     */
    public function get_by_id(mixed $id): array
    {
        return [];
    }

    /**
     * @return list<object>
     */
    protected function resolveAdminNotifiables(): array
    {
        $modelClass = config('microweber-notification.admin_user_model');

        if (! is_string($modelClass) || $modelClass === '' || ! class_exists($modelClass)) {
            $candidates = [
                'MicroweberPackages\\User\\Models\\User',
                'App\\Models\\User',
            ];
            $modelClass = null;
            foreach ($candidates as $candidate) {
                if (class_exists($candidate)) {
                    $modelClass = $candidate;
                    break;
                }
            }
        }

        if (! is_string($modelClass) || ! class_exists($modelClass)) {
            return [];
        }

        /** @var class-string<Model> $modelClass */
        $query = $modelClass::query();

        $column = config('microweber-notification.admin_column', 'is_admin');
        $value = config('microweber-notification.admin_value', 1);

        if (is_string($column) && $column !== '') {
            $query->where($column, $value);
        }

        /** @var list<object> $users */
        $users = $query->get()->all();

        return $users;
    }

    /**
     * Build display payloads for the authenticated user's unread notifications.
     *
     * @return list<mixed>
     */
    protected function formatUnreadMessages(): array
    {
        $readyNotifications = [];

        $user = Auth::user();
        if (! $user instanceof Authenticatable) {
            return $readyNotifications;
        }

        if (! property_exists($user, 'unreadNotifications') && ! method_exists($user, 'unreadNotifications')) {
            return $readyNotifications;
        }

        /** @var iterable<int, object> $unread */
        $unread = $user->unreadNotifications ?? [];

        foreach ($unread as $notification) {
            if (! is_object($notification) || ! isset($notification->type) || ! is_string($notification->type)) {
                continue;
            }

            if (! class_exists($notification->type)) {
                continue;
            }

            $typeClass = $notification->type;
            $data = $notification->data ?? null;

            try {
                /** @var object $messageType */
                $messageType = new $typeClass($data);
            } catch (\Throwable) {
                continue;
            }

            if (! method_exists($messageType, 'message')) {
                continue;
            }

            if (method_exists($messageType, 'setNotification')) {
                $messageType->setNotification($notification);
            }

            $readyNotifications[] = $messageType->message($notification);
        }

        return $readyNotifications;
    }

    /**
     * @param  array<string, mixed>|string  $params
     * @return array<string, mixed>
     */
    protected function normalizeParams(array|string $params): array
    {
        if (is_array($params)) {
            return $params;
        }

        if (function_exists('parse_params')) {
            $parsed = parse_params($params);

            return is_array($parsed) ? $this->stringifyKeys($parsed) : [];
        }

        $parsed = [];
        parse_str($params, $parsed);

        return $this->stringifyKeys($parsed);
    }

    /**
     * @param  array<int|string, mixed>  $params
     * @return array<string, mixed>
     */
    protected function stringifyKeys(array $params): array
    {
        $result = [];
        foreach ($params as $key => $value) {
            $result[(string) $key] = $value;
        }

        return $result;
    }
}
