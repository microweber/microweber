<?php

namespace MicroweberPackages\Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriberList extends Model
{
    protected $table = 'newsletter_subscribers_lists';

    protected $fillable =[
        'subscriber_id',
        'list_id',
    ];

}
