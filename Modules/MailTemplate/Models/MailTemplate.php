<?php

namespace Modules\MailTemplate\Models;

use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{
    protected $fillable = [
        'name',
        'type',
        'from_name',
        'from_email',
        'copy_to',
        'subject',
        'message',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public static function getTypes()
    {
        return [
            'new_order',
            'new_comment',
            'forgot_password',
            'new_registration',
            'contact_form',
            'newsletter_subscription'
        ];
    }
}
