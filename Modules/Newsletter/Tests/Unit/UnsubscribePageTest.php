<?php

namespace Modules\Newsletter\Tests\Unit;

use Livewire\Livewire;
use Modules\Newsletter\Livewire\UnsubscribePage;
use Modules\Newsletter\Models\NewsletterSubscriber;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UnsubscribePageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        NewsletterSubscriber::query()->delete();
    }

    #[Test]
    public function unsubscribe_route_renders_for_a_valid_subscriber(): void
    {
        $subscriber = NewsletterSubscriber::factory()->create([
            'email' => 'unsubscribe-test@example.com',
            'status' => 'active',
            'is_subscribed' => true,
        ]);

        $response = $this->get(route('modules.newsletter.unsubscribe') . '?email=' . urlencode($subscriber->email));

        $response->assertSuccessful();
        $response->assertSee('Unsubscribe from our newsletter');
        $response->assertSee($subscriber->email);
        $response->assertSee('Are you sure you want to continue?');
    }

    #[Test]
    public function unsubscribe_action_marks_the_subscriber_as_unsubscribed(): void
    {
        $subscriber = NewsletterSubscriber::factory()->create([
            'email' => 'unsubscribe-me@example.com',
            'status' => 'active',
            'is_subscribed' => true,
            'unsubscribed_at' => null,
        ]);

        Livewire::test(UnsubscribePage::class, [
            'email' => $subscriber->email,
        ])
            ->call('unsubscribe')
            ->assertSee('You have been unsubscribed successfully.');

        $subscriber->refresh();

        $this->assertSame('unsubscribed', $subscriber->status);
        $this->assertSame(0, $subscriber->is_subscribed);
        $this->assertNotNull($subscriber->unsubscribed_at);
    }

    #[Test]
    public function unsubscribe_route_shows_invalid_state_when_email_is_missing(): void
    {
        $response = $this->get(route('modules.newsletter.unsubscribe'));

        $response->assertSuccessful();
        $response->assertSee('This unsubscribe link is invalid or has expired.');
    }
}
