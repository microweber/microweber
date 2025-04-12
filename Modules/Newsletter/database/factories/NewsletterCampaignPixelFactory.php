<?php

namespace Modules\Newsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Newsletter\Models\NewsletterCampaignPixel;
use Modules\Newsletter\Models\NewsletterCampaign;

class NewsletterCampaignPixelFactory extends Factory
{
    protected $model = NewsletterCampaignPixel::class;

    public function definition()
    {
        return [
            'campaign_id' => NewsletterCampaign::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}