<?php

namespace MicroweberPackages\User\Http\Livewire\Admin;

use LivewireUI\Modal\ModalComponent;

class UserTosLogModal extends ModalComponent
{
    public $userId;
    public $terms;

    public function mount($userId)
    {
        $this->userId = $userId;

        $terms = false;
        if ($this->userId) {
            $terms_params = array();
            $terms_params['user_id'] = $this->userId;

            $terms = new \MicroweberPackages\User\TosManager();
            $terms = $terms->get($terms_params);
        }

        $this->terms = $terms;
    }

    public function render()
    {
        return view('user::admin.livewire.users.user-tos-log-modal');
    }
}
