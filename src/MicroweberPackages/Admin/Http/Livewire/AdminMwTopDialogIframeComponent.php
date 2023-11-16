<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AdminMwTopDialogIframeComponent extends AdminComponent
{
    use AuthorizesRequests;

    public function __construct($id = null)
    {
        try {
            $this->authorize('isAdmin');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(401, 'Unauthorized action.');
        }

        parent::__construct($id);

    }

    public function closeModal()
    {
        $this->emit('closeMwTopDialogIframe');
    }
}
