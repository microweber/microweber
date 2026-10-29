<?php
namespace Modules\Newsletter\Livewire;

use Livewire\Component;
use Modules\Newsletter\Models\NewsletterSubscriber;

class UnsubscribePage extends Component
{
    public const STATE_CONFIRM = 'confirm';
    public const STATE_SUCCESS = 'success';
    public const STATE_ALREADY_UNSUBSCRIBED = 'already_unsubscribed';
    public const STATE_INVALID = 'invalid';

    public ?string $email = null;

    public ?string $code = null;

    public ?int $subscriberId = null;

    public ?string $subscriberName = null;

    public string $pageState = self::STATE_INVALID;

    public function mount(?string $email = null, ?string $code = null): void
    {
        $this->email = $email ?: request()->query('email');
        $this->code = $code ?: request()->query('code');

        $this->loadSubscriberState();
    }

    public function unsubscribe(): void
    {
        // Re-verify from email+code; Livewire props ($subscriberId/$pageState)
        // are client-tamperable, so don't trust state set during mount().
        $subscriber = $this->resolveAuthorizedSubscriber();

        if (! $subscriber) {
            $this->pageState = self::STATE_INVALID;

            return;
        }

        if ($subscriber->status === 'unsubscribed' || (int) $subscriber->is_subscribed === 0) {
            $this->pageState = self::STATE_ALREADY_UNSUBSCRIBED;

            return;
        }

        $subscriber->update([
            'is_subscribed' => false,
            'status' => 'unsubscribed',
            'unsubscribed_at' => now(),
        ]);

        $this->pageState = self::STATE_SUCCESS;
    }

    protected function loadSubscriberState(): void
    {
        $subscriber = $this->resolveAuthorizedSubscriber();

        if (! $subscriber) {
            $this->pageState = self::STATE_INVALID;

            return;
        }

        $this->subscriberId = $subscriber->id;
        $this->subscriberName = $subscriber->name;

        if ($subscriber->status === 'unsubscribed' || (int) $subscriber->is_subscribed === 0) {
            $this->pageState = self::STATE_ALREADY_UNSUBSCRIBED;

            return;
        }

        $this->pageState = self::STATE_CONFIRM;
    }

    /**
     * Resolve the subscriber for the current email, requiring the matching
     * confirmation_code when one exists (legacy code-less rows stay email-only).
     */
    protected function resolveAuthorizedSubscriber(): ?NewsletterSubscriber
    {
        $email = is_string($this->email) ? trim($this->email) : null;

        if (! filled($email)) {
            return null;
        }

        $this->email = $email;

        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if (! $subscriber) {
            return null;
        }

        if (filled($subscriber->confirmation_code)
            && ! hash_equals((string) $subscriber->confirmation_code, (string) $this->code)) {
            return null;
        }

        return $subscriber;
    }

    public function render()
    {
        return view('microweber-module-newsletter::livewire.unsubscribe');
    }

}
