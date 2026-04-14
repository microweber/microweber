<?php

namespace Modules\Newsletter\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Newsletter\Models\NewsletterAutomationQueue;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterCampaignClickedLink;
use Modules\Newsletter\Models\NewsletterCampaignPixel;
use Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterSubscriberList;
use Modules\Newsletter\Models\NewsletterTemplate;

class SeedDemoData extends Command
{
    protected $signature = 'newsletter:seed-demo-data
                            {--fresh : Remove existing newsletter demo/test marketing data before seeding}';

    protected $description = 'Seed realistic newsletter demo data for UI evaluation';

    /**
     * @var array<int, array{name: string, description: string, is_public: bool}>
     */
    protected array $lists = [
        [
            'name' => 'Weekly Product Updates',
            'description' => 'Customers who want the latest product launches, feature drops, and release notes.',
            'is_public' => true,
        ],
        [
            'name' => 'VIP Customers',
            'description' => 'High-value customers receiving early access invites, perks, and retention campaigns.',
            'is_public' => false,
        ],
        [
            'name' => 'Seasonal Promotions',
            'description' => 'Shoppers interested in holiday bundles, flash sales, and limited-time offers.',
            'is_public' => true,
        ],
        [
            'name' => 'Workshop Registrants',
            'description' => 'Leads and customers who signed up for educational webinars and live training events.',
            'is_public' => false,
        ],
    ];

    /**
     * @var array<int, array{title: string, text: string}>
     */
    protected array $templates = [
        [
            'title' => 'Product Launch Spotlight',
            'text' => '<h1>Meet our newest release</h1><p>See what changed, why it matters, and how to get started today.</p>',
        ],
        [
            'title' => 'Weekend Offer',
            'text' => '<h1>This weekend only</h1><p>Save on our most-loved products with a short, high-converting promotional email.</p>',
        ],
        [
            'title' => 'Customer Education Digest',
            'text' => '<h1>What to watch next</h1><p>Share guides, videos, and workshop reminders in one clean educational layout.</p>',
        ],
    ];

    /**
     * @var array<int, array<string, mixed>>
     */
    protected array $subscribers = [
        ['email' => 'amelia.brooks@example.com', 'name' => 'Amelia Brooks', 'list' => 'Weekly Product Updates', 'status' => 'active'],
        ['email' => 'marcus.lee@example.com', 'name' => 'Marcus Lee', 'list' => 'Weekly Product Updates', 'status' => 'active'],
        ['email' => 'sophia.turner@example.com', 'name' => 'Sophia Turner', 'list' => 'VIP Customers', 'status' => 'active'],
        ['email' => 'oliver.nguyen@example.com', 'name' => 'Oliver Nguyen', 'list' => 'VIP Customers', 'status' => 'active'],
        ['email' => 'chloe.davis@example.com', 'name' => 'Chloe Davis', 'list' => 'Seasonal Promotions', 'status' => 'active'],
        ['email' => 'ethan.carter@example.com', 'name' => 'Ethan Carter', 'list' => 'Seasonal Promotions', 'status' => 'active'],
        ['email' => 'mia.hall@example.com', 'name' => 'Mia Hall', 'list' => 'Workshop Registrants', 'status' => 'active'],
        ['email' => 'noah.foster@example.com', 'name' => 'Noah Foster', 'list' => 'Workshop Registrants', 'status' => 'active'],
        ['email' => 'ava.cooper@example.com', 'name' => 'Ava Cooper', 'list' => 'Weekly Product Updates', 'status' => 'active'],
        ['email' => 'logan.ward@example.com', 'name' => 'Logan Ward', 'list' => 'Seasonal Promotions', 'status' => 'unsubscribed'],
        ['email' => 'grace.morris@example.com', 'name' => 'Grace Morris', 'list' => 'VIP Customers', 'status' => 'active'],
        ['email' => 'henry.scott@example.com', 'name' => 'Henry Scott', 'list' => 'Workshop Registrants', 'status' => 'bounced'],
    ];

    /**
     * @var array<int, array<string, mixed>>
     */
    protected array $campaigns = [
        [
            'name' => 'April Product Roundup',
            'subject' => 'See what shipped this month',
            'list' => 'Weekly Product Updates',
            'template' => 'Product Launch Spotlight',
            'type' => NewsletterCampaign::CAMPAIGN_TYPE_BROADCAST,
            'status' => NewsletterCampaign::STATUS_DRAFT,
        ],
        [
            'name' => 'VIP Early Access Invite',
            'subject' => 'Your early access starts now',
            'list' => 'VIP Customers',
            'template' => 'Product Launch Spotlight',
            'type' => NewsletterCampaign::CAMPAIGN_TYPE_BROADCAST,
            'status' => NewsletterCampaign::STATUS_PENDING,
        ],
        [
            'name' => 'Summer Sale Preview',
            'subject' => 'Preview this weekend’s best offers',
            'list' => 'Seasonal Promotions',
            'template' => 'Weekend Offer',
            'type' => NewsletterCampaign::CAMPAIGN_TYPE_BROADCAST,
            'status' => NewsletterCampaign::STATUS_SCHEDULED,
            'scheduled_at' => '+2 days',
        ],
        [
            'name' => 'Workshop Reminder',
            'subject' => 'Your workshop starts tomorrow',
            'list' => 'Workshop Registrants',
            'template' => 'Customer Education Digest',
            'type' => NewsletterCampaign::CAMPAIGN_TYPE_BROADCAST,
            'status' => NewsletterCampaign::STATUS_PROCESSING,
        ],
        [
            'name' => 'Abandoned Cart Recovery',
            'subject' => 'Still thinking it over?',
            'list' => 'Seasonal Promotions',
            'template' => 'Weekend Offer',
            'type' => NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED,
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
            'delay_minutes' => 60,
            'status' => NewsletterCampaign::STATUS_DRAFT,
        ],
        [
            'name' => 'Welcome to the Community',
            'subject' => 'Thanks for joining us',
            'list' => 'Weekly Product Updates',
            'template' => 'Customer Education Digest',
            'type' => NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED,
            'trigger_event' => NewsletterCampaign::TRIGGER_USER_REGISTERED,
            'delay_minutes' => 0,
            'status' => NewsletterCampaign::STATUS_DRAFT,
        ],
    ];

    public function handle(): int
    {
        if ($this->option('fresh')) {
            $this->cleanupExistingNewsletterData();
            $this->info('Removed existing newsletter marketing data.');
        }

        DB::transaction(function (): void {
            $sender = $this->seedSenderAccount();
            $lists = $this->seedLists();
            $templates = $this->seedTemplates();

            $this->seedSubscribers($lists);
            $this->seedCampaigns($lists, $templates, $sender);
        });

        $this->info('Newsletter demo data is ready.');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Lists', NewsletterList::count()],
                ['Subscribers', NewsletterSubscriber::count()],
                ['Templates', NewsletterTemplate::count()],
                ['Campaigns', NewsletterCampaign::count()],
                ['Sender accounts', NewsletterSenderAccount::count()],
            ]
        );

        return self::SUCCESS;
    }

    protected function cleanupExistingNewsletterData(): void
    {
        NewsletterAutomationQueue::query()->delete();
        NewsletterCampaignsSendLog::query()->delete();
        NewsletterCampaignClickedLink::query()->delete();
        NewsletterCampaignPixel::query()->delete();
        NewsletterSubscriberList::query()->delete();
        NewsletterCampaign::query()->delete();
        NewsletterSubscriber::query()->delete();
        NewsletterTemplate::query()->delete();
        NewsletterSenderAccount::query()->delete();
        NewsletterList::query()->delete();
    }

    protected function seedSenderAccount(): NewsletterSenderAccount
    {
        return NewsletterSenderAccount::updateOrCreate(
            ['from_email' => 'newsletter@microweber.test'],
            [
                'name' => 'Microweber Marketing',
                'from_name' => 'Microweber Team',
                'reply_email' => 'support@microweber.test',
                'account_type' => 'smtp',
                'smtp_username' => 'newsletter@microweber.test',
                'smtp_password' => 'demo-password',
                'smtp_host' => 'smtp.microweber.test',
                'smtp_port' => '587',
                'is_active' => true,
            ]
        );
    }

    /**
     * @return array<string, NewsletterList>
     */
    protected function seedLists(): array
    {
        $lists = [];

        foreach ($this->lists as $data) {
            $lists[$data['name']] = NewsletterList::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }

        return $lists;
    }

    /**
     * @return array<string, NewsletterTemplate>
     */
    protected function seedTemplates(): array
    {
        $templates = [];

        foreach ($this->templates as $data) {
            $templates[$data['title']] = NewsletterTemplate::updateOrCreate(
                ['title' => $data['title']],
                $data
            );
        }

        return $templates;
    }

    /**
     * @param array<string, NewsletterList> $lists
     */
    protected function seedSubscribers(array $lists): void
    {
        foreach ($this->subscribers as $data) {
            $list = $lists[$data['list']];
            $status = $data['status'];

            $subscriber = NewsletterSubscriber::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'status' => $status,
                    'is_subscribed' => $status === 'active',
                    'subscribed_at' => $status === 'active' ? now()->subDays(7) : now()->subDays(14),
                    'unsubscribed_at' => $status === 'unsubscribed' ? now()->subDays(2) : null,
                    'list_id' => $list->id,
                ]
            );

            NewsletterSubscriberList::updateOrCreate(
                [
                    'subscriber_id' => $subscriber->id,
                    'list_id' => $list->id,
                ],
                []
            );
        }
    }

    /**
     * @param array<string, NewsletterList> $lists
     * @param array<string, NewsletterTemplate> $templates
     */
    protected function seedCampaigns(array $lists, array $templates, NewsletterSenderAccount $sender): void
    {
        foreach ($this->campaigns as $data) {
            $scheduledAt = $data['scheduled_at'] ?? null;

            NewsletterCampaign::updateOrCreate(
                ['name' => $data['name']],
                [
                    'subject' => $data['subject'],
                    'list_id' => $lists[$data['list']]->id,
                    'sender_account_id' => $sender->id,
                    'email_content_type' => 'design',
                    'email_template_id' => $templates[$data['template']]->id,
                    'campaign_type' => $data['type'],
                    'trigger_event' => $data['trigger_event'] ?? null,
                    'delay_minutes' => $data['delay_minutes'] ?? 0,
                    'is_active' => true,
                    'recipients_from' => 'specific_list',
                    'delivery_type' => NewsletterCampaign::DELIVERY_TYPE_SEND_NOW,
                    'status' => $data['status'],
                    'scheduled_at' => $scheduledAt ? now()->modify($scheduledAt) : null,
                    'scheduled_timezone' => config('app.timezone', 'UTC'),
                    'email_content_html' => $templates[$data['template']]->text,
                ]
            );
        }
    }
}
