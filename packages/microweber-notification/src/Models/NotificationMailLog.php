<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Log of notification emails already sent (dedupe / audit).
 *
 * @property int $id
 * @property string|null $type
 * @property string|null $notifiable_type
 * @property string|null $notifiable_id
 * @property string|null $html
 * @property int|null $rel_id
 * @property string|null $rel_type
 * @property string|null $email
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class NotificationMailLog extends Model
{
    protected $table = 'notifications_mails_log';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'type',
        'notifiable_type',
        'notifiable_id',
        'rel_id',
        'rel_type',
        'email',
        'user_id',
        'html',
    ];
}
