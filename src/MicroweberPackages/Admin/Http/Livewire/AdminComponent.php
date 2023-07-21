<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class AdminComponent extends Component
{
    use AuthorizesRequests;

    public function __construct()
    {
        try {
            $this->authorize('isAdmin');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(401, 'Unauthorized action.');
        }


    }

    public function dispatchBrowserEvent($name, $data = [])
    {
        $this->dispatch($name, $data);
    }

    public function emit($name, $data = [])
    {
        $this->dispatch($name, $data);
    }

}
