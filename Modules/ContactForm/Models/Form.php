<?php

namespace Modules\ContactForm\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ContactForm\Database\Factories\FormFactory;

class Form extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\ContactForm\Events\FormWasCreated::class,
        'updated' => \Modules\ContactForm\Events\FormWasUpdated::class,
        'deleted' => \Modules\ContactForm\Events\FormWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'list_id',
        'module_id',
        'description',
        'confirmation_message',
        'emails_notifications',
        'emails_notifications_subject',
        'is_active',
    ];

    protected static function newFactory()
    {
        return FormFactory::new();
    }
}
