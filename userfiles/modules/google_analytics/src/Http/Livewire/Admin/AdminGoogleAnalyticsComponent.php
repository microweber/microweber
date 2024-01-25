<?php

namespace MicroweberPackages\Modules\GoogleAnalytics\Http\Livewire\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

class AdminGoogleAnalyticsComponent extends AdminComponent
{
    use AuthorizesRequests;

    public function render()
    {
        return view('google_analytics::admin.livewire.index', [

        ]);
    }
}
