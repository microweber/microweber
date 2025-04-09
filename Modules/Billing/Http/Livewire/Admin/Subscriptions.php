<?php

namespace Modules\Billing\Http\Livewire\Admin;

use Livewire\Component;
use Modules\Billing\Models\Subscription;
/* @deprecated */
class Subscriptions extends Component
{
    public function render()
    {
        $subscriptions = Subscription::all();

        return view('modules.billing::admin.livewire.subscriptions', compact('subscriptions'));
    }
}
