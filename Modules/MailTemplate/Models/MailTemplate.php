<?php

namespace Modules\MailTemplate\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\MailTemplate\Services\MailTemplateService;

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

    public static function getTypes(): array
    {
        return app(MailTemplateService::class)->getTemplateTypes();
    }
}
