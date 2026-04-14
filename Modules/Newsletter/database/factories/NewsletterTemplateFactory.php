<?php

namespace Modules\Newsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Newsletter\Models\NewsletterTemplate;

class NewsletterTemplateFactory extends Factory
{
    protected $model = NewsletterTemplate::class;

    /**
     * @var array<int, array{title: string, text: string}>
     */
    protected array $templateProfiles = [
        [
            'title' => 'Product Launch Spotlight',
            'text' => '<h1>Introducing a new release</h1><p>Highlight key product improvements with a polished launch template.</p>',
        ],
        [
            'title' => 'Promotion Highlight',
            'text' => '<h1>Your next best offer</h1><p>Use this template for short-term campaigns with strong calls to action.</p>',
        ],
        [
            'title' => 'Education Digest',
            'text' => '<h1>Learn something new</h1><p>Share recordings, guides, and workshop resources in a clean digest layout.</p>',
        ],
    ];

    public function definition()
    {
        $profile = fake()->randomElement($this->templateProfiles);

        return [
            'title' => $profile['title'] . ' ' . fake()->unique()->numberBetween(1, 999),
            'text' => $profile['text'],
            'json' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
