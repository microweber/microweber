<?php

namespace Modules\ContactForm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ContactForm\Database\Factories\FormFactory;

class Form extends Model
{
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
