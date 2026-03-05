<?php

namespace Modules\MailTemplate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\MailTemplate\Database\Factories\MailTemplateFactory;
use Modules\MailTemplate\Services\MailTemplateService;

class MailTemplate extends Model
{
    use HasFactory;

    protected static function newFactory(): MailTemplateFactory
    {
        return new MailTemplateFactory();
    }
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
