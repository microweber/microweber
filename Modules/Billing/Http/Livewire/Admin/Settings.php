<?php

namespace Modules\Billing\Http\Livewire\Admin;

use Livewire\Component;
use Modules\Billing\Models\Stripe\Subscription;

class Settings extends Component
{
    public function render()
    {
        return view('billing::admin.livewire.settings');
    }
}
