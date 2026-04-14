<?php

namespace Modules\Newsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Newsletter\Models\NewsletterList;

class NewsletterListFactory extends Factory
{
    protected $model = NewsletterList::class;

    /**
     * @var array<int, array{name: string, description: string}>
     */
    protected array $listProfiles = [
        [
            'name' => 'Weekly Product Updates',
            'description' => 'Customers who want release notes, feature updates, and launch announcements.',
        ],
        [
            'name' => 'VIP Customers',
            'description' => 'High-value customers receiving priority offers and early-access announcements.',
        ],
        [
            'name' => 'Seasonal Promotions',
            'description' => 'Shoppers interested in campaigns tied to seasonal launches and limited-time discounts.',
        ],
        [
            'name' => 'Workshop Registrants',
            'description' => 'Contacts who signed up for educational webinars and hands-on product sessions.',
        ],
        [
            'name' => 'Partner Updates',
            'description' => 'Agency and partner contacts receiving co-marketing news and enablement material.',
        ],
    ];

    public function definition()
    {
        $profile = fake()->randomElement($this->listProfiles);

        return [
            'name' => $profile['name'] . ' ' . fake()->unique()->numberBetween(1, 999),
            'description' => $profile['description'],
            'is_public' => true,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
