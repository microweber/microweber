<?php

namespace Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Newsletter\Livewire\Admin\NewsletterSubscribersList;

class NewsletterCampaignPixel extends Model
{
    public $table = 'newsletter_campaigns_pixel';

    public $fillable = [
        'campaign_id',
        'email',
        'ip',
        'user_agent',
        'created_at',
        'updated_at',
    ];
}
