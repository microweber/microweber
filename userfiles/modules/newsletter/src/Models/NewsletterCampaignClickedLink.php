<?php

namespace MicroweberPackages\Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin\NewsletterSubscribersList;

class NewsletterCampaignClickedLink extends Model
{
    public $table = 'newsletter_campaigns_clicked_link';

    public $fillable = [
        'campaign_id',
        'email',
        'ip',
        'user_agent',
        'link',
        'created_at',
        'updated_at',
    ];
}
