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

    public ?int $subscriberId = null;

    public ?string $subscriberName = null;

    public string $pageState = self::STATE_INVALID;

    public function mount(?string $email = null): void
    {
        $this->email = $email ?: request()->query('email');

        $this->loadSubscriberState();
    }

    public function unsubscribe(): void
    {
        if ($this->pageState !== self::STATE_CONFIRM || ! $this->subscriberId) {
            return;
        }

        $subscriber = NewsletterSubscriber::find($this->subscriberId);

        if (! $subscriber) {
            $this->pageState = self::STATE_INVALID;

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
        $email = is_string($this->email) ? trim($this->email) : null;

        if (! filled($email)) {
            $this->pageState = self::STATE_INVALID;

            return;
        }

        $this->email = $email;

        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if (! $subscriber) {
            $this->pageState = self::STATE_INVALID;

            return;
        }

        $this->subscriberId = $subscriber->id;
        $this->subscriberName = $subscriber->name;

        if ($subscriber->status === 'unsubscribed' || $subscriber->is_subscribed === 0) {
            $this->pageState = self::STATE_ALREADY_UNSUBSCRIBED;

            return;
        }

        $this->pageState = self::STATE_CONFIRM;
    }

    public function render()
    {
        return view('microweber-module-newsletter::livewire.unsubscribe');
    }

}
