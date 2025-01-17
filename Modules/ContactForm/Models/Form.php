<?php

namespace Modules\ContactForm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Form extends Model
{

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

}
