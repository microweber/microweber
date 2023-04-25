<?php

namespace MicroweberPackages\User\Http\Livewire\Admin;

use LivewireUI\Modal\ModalComponent;

class UserLoginAttemptsModal extends ModalComponent
{
    public $userId;

    public function mount($userId)
    {
        $this->userId = $userId;
    }

    public function render()
    {
        return view('user::admin.livewire.users.user-login-attempts-modal');
    }
}
