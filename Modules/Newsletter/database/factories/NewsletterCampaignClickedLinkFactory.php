<?php

namespace Modules\Newsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Newsletter\Models\NewsletterCampaignClickedLink;
use Modules\Newsletter\Models\NewsletterCampaign;

class NewsletterCampaignClickedLinkFactory extends Factory
{
    protected $model = NewsletterCampaignClickedLink::class;

    public function definition()
    {
        return [
            'campaign_id' => NewsletterCampaign::factory(),
            'link' => $this->faker->url,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}