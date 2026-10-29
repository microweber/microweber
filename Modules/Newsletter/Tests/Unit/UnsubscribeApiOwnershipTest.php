<?php

namespace Modules\Newsletter\Tests\Unit;

use Modules\Newsletter\Models\NewsletterSubscriber;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Regression guard: the public newsletter unsubscribe API must require proof of
 * ownership (the per-subscriber confirmation_code) from a guest, so a stranger
 * cannot force-unsubscribe an arbitrary known email address.
 */
class UnsubscribeApiOwnershipTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        NewsletterSubscriber::query()->delete();
    }

    private function makeSubscriber(string $code): NewsletterSubscriber
    {
        return NewsletterSubscriber::create([
            'email' => 'zz_unsub_' . uniqid() . '@example.com',
            'status' => 'active',
            'is_subscribed' => true,
            'confirmation_code' => $code,
        ]);
    }

    #[Test]
    public function a_guest_cannot_unsubscribe_without_a_confirmation_code(): void
    {
        $sub = $this->makeSubscriber('code-' . uniqid());

        $response = $this->postJson(route('api.module.newsletter.unsubscribe'), [
            'email' => $sub->email,
        ]);

        $response->assertStatus(403);
        $sub->refresh();
        $this->assertSame('active', $sub->status, 'subscriber must NOT be unsubscribed without the code');
    }

    #[Test]
    public function a_wrong_confirmation_code_does_not_unsubscribe(): void
    {
        $sub = $this->makeSubscriber('right-' . uniqid());

        $response = $this->postJson(route('api.module.newsletter.unsubscribe'), [
            'email' => $sub->email,
            'confirmation_code' => 'totally-wrong-code',
        ]);

        // Request is accepted but matches nothing -> no row changed.
        $sub->refresh();
        $this->assertSame('active', $sub->status, 'a wrong code must not unsubscribe anyone');
    }

    #[Test]
    public function the_correct_confirmation_code_unsubscribes(): void
    {
        $code = 'correct-' . uniqid();
        $sub = $this->makeSubscriber($code);

        $response = $this->postJson(route('api.module.newsletter.unsubscribe'), [
            'email' => $sub->email,
            'confirmation_code' => $code,
        ]);

        $response->assertStatus(200);
        $sub->refresh();
        $this->assertSame('unsubscribed', $sub->status);
        $this->assertSame(0, (int) $sub->is_subscribed);
    }
}
