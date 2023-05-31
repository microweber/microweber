<?php

namespace MicroweberPackages\User\Http\Livewire\Admin;

use LivewireUI\Modal\ModalComponent;
use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;

class UserLoginAttemptsModal extends AdminModalComponent

{
    public $userId = 0;
    public $loginAttempts;

    public function mount($userId = 0)
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
