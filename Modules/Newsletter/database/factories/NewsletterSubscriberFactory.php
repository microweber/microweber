<?php

namespace Modules\Newsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterList;

class NewsletterSubscriberFactory extends Factory
{
    protected $model = NewsletterSubscriber::class;

    /**
     * @var array<int, string>
     */
    protected array $firstNames = ['Amelia', 'Marcus', 'Sophia', 'Oliver', 'Chloe', 'Ethan', 'Mia', 'Noah', 'Ava', 'Henry'];

    /**
     * @var array<int, string>
     */
    protected array $lastNames = ['Brooks', 'Lee', 'Turner', 'Nguyen', 'Davis', 'Carter', 'Hall', 'Foster', 'Cooper', 'Scott'];

    public function definition()
    {
        $firstName = fake()->randomElement($this->firstNames);
        $lastName = fake()->randomElement($this->lastNames);
        $emailHandle = strtolower($firstName . '.' . $lastName . fake()->unique()->numberBetween(1, 999));

        return [
            'list_id' => NewsletterList::factory(),
            'email' => $emailHandle . '@example.com',
            'name' => $firstName . ' ' . $lastName,
            'status' => 'active',
            'subscribed_at' => now(),
            'is_subscribed' => true,
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
