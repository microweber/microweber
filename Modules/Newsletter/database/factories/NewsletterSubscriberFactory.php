<?php

namespace Modules\Newsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterList;

class NewsletterSubscriberFactory extends Factory
{
    protected $model = NewsletterSubscriber::class;

    public function definition()
    {
        return [
            'list_id' => NewsletterList::factory(),
            'email' => $this->faker->unique()->safeEmail,
            'name' => $this->faker->name,
            'status' => 'active',
            'subscribed_at' => now(),
            'unsubscribed_at' => null,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    public function unsubscribed()
    {
        return $this->state([
            'status' => 'unsubscribed',
            'unsubscribed_at' => now()
        ]);
    }

    public function bounced()
    {
        return $this->state([
            'status' => 'bounced'
        ]);
    }
}