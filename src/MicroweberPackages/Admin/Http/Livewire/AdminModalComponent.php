<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class AdminModalComponent extends ModalComponent
{
    use AuthorizesRequests;

    public $modalSettings = [
        'width'=>'800px',
        'height'=>'444px',
        'overlay' => true,
        'overlayClose' => true,
    ];

    public function __construct($id = null)
    {
        try {
            $this->authorize('isAdmin');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(401, 'Unauthorized action.');
        }

        parent::__construct($id);

    }
}
