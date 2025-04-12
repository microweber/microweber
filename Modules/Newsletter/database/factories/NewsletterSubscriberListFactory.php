<?php

namespace Modules\Newsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Newsletter\Models\NewsletterSubscriberList;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSubscriber;

class NewsletterSubscriberListFactory extends Factory
{
    protected $model = NewsletterSubscriberList::class;

    public function definition()
    {
        return [
            'list_id' => NewsletterList::factory(),
            'subscriber_id' => NewsletterSubscriber::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}