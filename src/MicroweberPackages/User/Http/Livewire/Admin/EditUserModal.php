<?php

namespace MicroweberPackages\User\Http\Livewire\Admin;

use LivewireUI\Modal\ModalComponent;
use MicroweberPackages\User\Models\User;

class EditUserModal extends ModalComponent
{
    public $userId;
    public $state;

    public function mount($userId)
    {
        $this->userId = $userId;
        $this->state = $this->getUserData();
    }

    public function getUserData()
    {
        $user = User::where('id', $this->userId)->first();
        if (!empty($user)) {
            $userData = $user->toArray();
            unset($userData['password']);
            return $userData;
        }

        return false;
    }

    public function save()
    {
        $user = User::where('id', $this->userId)->first();
        if (!empty($user)) {
            $user->update([
                'first_name'=>$this->state['first_name'],
                'last_name'=>$this->state['last_name'],
            ]);
        }

        $this->emit('refreshUserList');
        $this->closeModal();
    }

    public function render()
    {
        return view('user::admin.users.edit-user-modal');
    }
}
