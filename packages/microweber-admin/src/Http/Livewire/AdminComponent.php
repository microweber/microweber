<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class AdminComponent extends Component
{
    use AuthorizesRequests;

    public $globalListeners = [];

    public function mount()
    {
        try {
            $this->authorize('isAdmin');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(401, 'Unauthorized action, you are not an admin');
        }
    }

//    public function dispatchGlobalBrowserEvent($event, $data = null)
//    {
//        $this->dispatch('dispatch-global-browser-event', [
//            'event' => $event,
//            'data' => $data
//        ]);
//    }

}
