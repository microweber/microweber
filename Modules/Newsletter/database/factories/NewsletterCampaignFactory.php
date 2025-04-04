<?php

namespace Modules\Newsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterList;

class NewsletterCampaignFactory extends Factory
{
    protected $model = NewsletterCampaign::class;

    public function definition()
    {
        return [
            'list_id' => NewsletterList::factory(),
            'subject' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'status' => 'draft',
            'scheduled_at' => null,
            'sent_at' => null,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    public function scheduled()
    {
        return $this->state([
            'status' => 'scheduled',
            'scheduled_at' => now()->addDay()
        ]);
    }

    public function sending()
    {
        return $this->state([
            'status' => 'sending'
        ]);
    }
}