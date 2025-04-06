<?php

namespace Modules\Billing\Http\Livewire\Admin;

use Livewire\Component;
use Modules\Billing\Models\Subscription;

class Subscriptions extends Component
{
    public function render()
    {
        $subscriptions = Subscription::all();

        return view('billing::admin.livewire.subscriptions', compact('subscriptions'));
    }
}
