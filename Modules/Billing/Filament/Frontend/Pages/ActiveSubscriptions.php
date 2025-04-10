<?php

namespace Modules\Billing\Filament\Frontend\Pages;

use Filament\Pages\Page;
use Modules\Billing\Models\Subscription;

class ActiveSubscriptions extends Page
{
    protected static string $view = 'modules.billing::filament.pages.active-subscriptions';

    protected static ?string $title = 'My Active Subscriptions';

    protected static ?string $slug = 'my-active-subscriptions';

    public array $activeSubscriptions = [];

    public function mount()
    {
        $user = auth()->user();
        if ($user) {
            $this->activeSubscriptions = Subscription::with('plan')
                ->where('user_id', $user->id)
                ->where('stripe_status', 'active')
                ->get()
                ->toArray();
        }
    }
}