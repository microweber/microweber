<?php

namespace MicroweberPackages\User\Http\Livewire\Admin;

use LivewireUI\Modal\ModalComponent;

class UserLoginAttemptsModal extends ModalComponent
{
    public $userId;
    public $loginAttempts;

    public function mount($userId)
    {
        $this->userId = $userId;

        $this->loginAttempts = \MicroweberPackages\App\LoginAttempt::where('user_id', $userId)
            ->orderBy('time', 'desc')
            ->take(40)
            ->get();

    }

    public function render()
    {
        return view('user::admin.livewire.users.user-login-attempts-modal');
    }
}
