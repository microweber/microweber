<?php

namespace MicroweberPackages\Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin\NewsletterSubscribersList;

class NewsletterCampaign extends Model
{
    public $table = 'newsletter_campaigns';

    public $timestamps = false;

    public $fillable = [
        'sender_account_id',
        'email_template_id',
        'list_id',
        'name',
        'subject',
        'from_name',
        'from_email',
        'sending_limit_per_day',
        'is_scheduled',
        'scheduled_at',
        'is_done',
        'recipients_from',
        'delivery_type',

    ];

    public function senderAccount()
    {
        return $this->hasOne(NewsletterSenderAccount::class, 'id', 'sender_account_id');
    }

    public function list()
    {
        return $this->hasOne(NewsletterList::class, 'id', 'list_id');
    }

    public function listName()
    {
        return $this->list->name;
    }

    public function countSubscribers()
    {
        if ($this->recipients_from == 'specific_list') {
            return NewsletterSubscriberList::where('list_id', $this->list_id)->count();
        } else {
            return NewsletterSubscriber::all()->count();
        }
    }

    public function getSubscribersAttribute()
    {
        return NewsletterSubscriberList::where('list_id', $this->list_id)->count();

    }

    public function getScheduledAttribute()
    {
        return 0;
    }

    public function getScheduledAtAttribute()
    {
        return 0;
    }

    public function getDoneAttribute()
    {
        return 0;
    }
}
