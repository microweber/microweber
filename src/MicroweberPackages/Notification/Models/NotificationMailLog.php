<?php
namespace MicroweberPackages\Notification\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;


class NotificationMailLog extends Model
{
    protected $table = 'notifications_mail_log';

    protected $fillable = [
        'rel_id',
        'rel_type',
        'email',
        'user_id',
    ];


}
