<?php

namespace Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Newsletter\Database\Factories\NewsletterCampaignClickedLinkFactory;
use Modules\Newsletter\Livewire\Admin\NewsletterSubscribersList;

class NewsletterCampaignClickedLink extends Model
{
    use HasFactory;

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

    protected static function newFactory()
    {
        return NewsletterCampaignClickedLinkFactory::new();
    }
}
