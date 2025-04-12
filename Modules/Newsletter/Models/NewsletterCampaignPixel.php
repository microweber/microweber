<?php

namespace Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Newsletter\Database\Factories\NewsletterCampaignPixelFactory;
use Modules\Newsletter\Livewire\Admin\NewsletterSubscribersList;

class NewsletterCampaignPixel extends Model
{
    use HasFactory;

    public $table = 'newsletter_campaigns_pixel';

    public $fillable = [
        'campaign_id',
        'email',
        'ip',
        'user_agent',
        'created_at',
        'updated_at',
    ];

    protected static function newFactory()
    {
        return NewsletterCampaignPixelFactory::new();
    }
}
