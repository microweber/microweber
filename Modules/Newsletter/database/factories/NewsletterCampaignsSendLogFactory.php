<?php


namespace Modules\Newsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use Modules\Newsletter\Models\NewsletterSubscriber;

class NewsletterCampaignsSendLogFactory extends Factory
{
    protected $model = NewsletterCampaignsSendLog::class;

    public function definition()
    {
        return [
            'campaign_id' => 1,
            'subscriber_id' => NewsletterSubscriber::factory(),
            'is_sent' => $this->faker->boolean ? 1 : 0,
        ];
    }
}