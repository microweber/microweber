<?php

namespace Modules\MailTemplate\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\MailTemplate\Database\Factories\MailTemplateFactory;
use Modules\MailTemplate\Services\MailTemplateService;

class MailTemplate extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\MailTemplate\Events\MailTemplateWasCreated::class,
        'updated' => \Modules\MailTemplate\Events\MailTemplateWasUpdated::class,
        'deleted' => \Modules\MailTemplate\Events\MailTemplateWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

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
