<?php

namespace Modules\Newsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterList;

class NewsletterCampaignFactory extends Factory
{
    protected $model = NewsletterCampaign::class;

    /**
     * @var array<int, array{name: string, subject: string, html: string}>
     */
    protected array $campaignProfiles = [
        [
            'name' => 'Spring Launch Announcement',
            'subject' => 'Meet our latest release',
            'html' => '<h1>Meet our latest release</h1><p>Explore the biggest product updates we shipped this season.</p>',
        ],
        [
            'name' => 'Customer Success Story',
            'subject' => 'How teams are launching faster',
            'html' => '<h1>Launch faster with the right workflow</h1><p>See how customers are using Microweber to simplify campaign delivery.</p>',
        ],
        [
            'name' => 'Weekend Promo Reminder',
            'subject' => 'Your weekend offer ends soon',
            'html' => '<h1>Last chance to save</h1><p>Wrap up the week with a timely promotion and a clear call to action.</p>',
        ],
        [
            'name' => 'Workshop Follow-up',
            'subject' => 'Resources from the live session',
            'html' => '<h1>Thanks for joining us</h1><p>Here are the recordings, templates, and next steps from the workshop.</p>',
        ],
    ];

    public function definition()
    {
        $profile = fake()->randomElement($this->campaignProfiles);

        return [
            'list_id' => NewsletterList::factory(),
            'name' => $profile['name'] . ' ' . fake()->unique()->numberBetween(1, 999),
            'email_content_html' => $profile['html'],
            'email_content_type' => 'html',
            'subject' => $profile['subject'],
            'content' => strip_tags($profile['html']),
            'status' => 'draft',
            'scheduled_at' => null,
            'sent_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'campaign_type' => NewsletterCampaign::CAMPAIGN_TYPE_BROADCAST,
            'trigger_event' => null,
            'delay_minutes' => 0,
            'is_active' => true,
            'trigger_conditions' => null,
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
            'status' => NewsletterCampaign::STATUS_SENDING,
        ]);
    }
}
