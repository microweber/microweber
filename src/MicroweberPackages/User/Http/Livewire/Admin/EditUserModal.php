<?php

namespace MicroweberPackages\User\Http\Livewire\Admin;

use LivewireUI\Modal\ModalComponent;

class EditUserModal extends ModalComponent
{
    public function render()
    {
        return view('user::admin.users.edit-user-modal');
    }
}
