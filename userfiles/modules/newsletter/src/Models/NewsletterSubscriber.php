<?php

namespace MicroweberPackages\Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $table = 'newsletter_subscribers';

    public $timestamps = false;

    protected $fillable = [
        'email',
        'name',
        'is_subscribed'
    ];

}
