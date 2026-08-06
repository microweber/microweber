<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Database notification for legacy CMS notification payloads.
 *
 * @phpstan-type LegacyPayload array<string, mixed>
 */
class LegacyNotification extends Notification implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** @var LegacyPayload|false */
    public array|false $data;

    /**
     * @param  LegacyPayload|false  $data
     */
    public function __construct(array|false $data = false)
    {
        $this->data = $data;
    }

    /**
     * @param  mixed  $notifiable
     * @return list<string>
     */
    public function via(mixed $notifiable): array
    {
        return ['database'];
    }

    /**
     * @param  mixed  $notifiable
     * @return array{notifiable: string, notification: LegacyPayload|false}
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'notifiable' => 'legacy',
            'notification' => $this->data,
        ];
    }
}
