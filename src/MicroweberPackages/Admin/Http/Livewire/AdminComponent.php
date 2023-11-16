<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class AdminComponent extends Component
{
    use AuthorizesRequests;

    public $globalListeners = [];

    public function __construct($id = null)
    {
        try {
            $this->authorize('isAdmin');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(401, 'Unauthorized action.');
        }

        parent::__construct($id);

    }

    public function dispatchGlobalBrowserEvent($event, $data = null)
    {
        $this->dispatchBrowserEvent('dispatch-global-browser-event', [
            'event' => $event,
            'data' => $data
        ]);
    }

}
