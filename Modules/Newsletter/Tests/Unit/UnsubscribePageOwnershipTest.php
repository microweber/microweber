<?php

namespace Modules\Newsletter\Tests\Unit;

use Livewire\Livewire;
use Modules\Newsletter\Livewire\UnsubscribePage;
use Modules\Newsletter\Models\NewsletterSubscriber;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * The unsubscribe page must require the per-subscriber confirmation_code when
 * the subscriber has one, so a stranger can't opt out an arbitrary address by
 * email alone — and the check must survive Livewire property tampering.
 */
class UnsubscribePageOwnershipTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        NewsletterSubscriber::query()->delete();
    }

    private function subscriberWithCode(string $code): NewsletterSubscriber
    {
        return NewsletterSubscriber::create([
            'email' => 'zz_unsubweb_' . uniqid() . '@example.com',
            'status' => 'active',
            'is_subscribed' => true,
            'confirmation_code' => $code,
        ]);
    }

    #[Test]
    public function correct_code_allows_unsubscribe(): void
    {
        $sub = $this->subscriberWithCode('ok-' . uniqid());

        Livewire::test(UnsubscribePage::class, ['email' => $sub->email, 'code' => $sub->confirmation_code])
            ->assertSet('pageState', 'confirm')
            ->call('unsubscribe')
            ->assertSet('pageState', 'success');

        $this->assertSame('unsubscribed', $sub->refresh()->status);
    }

    #[Test]
    public function missing_code_is_rejected_for_a_subscriber_that_has_one(): void
    {
        $sub = $this->subscriberWithCode('secret-' . uniqid());

        Livewire::test(UnsubscribePage::class, ['email' => $sub->email])
            ->assertSet('pageState', 'invalid')
            ->call('unsubscribe');

        $this->assertSame('active', $sub->refresh()->status, 'email-only must not unsubscribe when a code exists');
    }

    #[Test]
    public function tampering_livewire_state_cannot_bypass_the_code_check(): void
    {
        $sub = $this->subscriberWithCode('token-' . uniqid());

        // A crafted Livewire request forces the "confirm" state + a subscriber id
        // but supplies no/!wrong code. unsubscribe() must re-verify and refuse.
        Livewire::test(UnsubscribePage::class, ['email' => $sub->email, 'code' => 'wrong'])
            ->set('subscriberId', $sub->id)
            ->set('pageState', 'confirm')
            ->call('unsubscribe')
            ->assertSet('pageState', 'invalid');

        $this->assertSame('active', $sub->refresh()->status, 'tampered state must not unsubscribe');
    }

    #[Test]
    public function legacy_subscriber_without_a_code_still_unsubscribes_by_email(): void
    {
        // Backward compatibility: previously-stored subscribers have no code.
        $sub = NewsletterSubscriber::create([
            'email' => 'zz_legacy_' . uniqid() . '@example.com',
            'status' => 'active',
            'is_subscribed' => true,
        ]);

        Livewire::test(UnsubscribePage::class, ['email' => $sub->email])
            ->assertSet('pageState', 'confirm')
            ->call('unsubscribe')
            ->assertSet('pageState', 'success');

        $this->assertSame('unsubscribed', $sub->refresh()->status);
    }
}
