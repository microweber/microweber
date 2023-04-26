<?php

namespace MicroweberPackages\User\Http\Livewire\Admin;

use Illuminate\Support\Facades\Hash;
use MicroweberPackages\User\Models\User;
use LivewireUI\Modal\ModalComponent;

class UpdatePasswordWithoutConfirmFormModal extends ModalComponent
{
    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [
        'password' => '',
    ];

    public $userId;

    public function mount($userId = false) {
        if ($userId) {
            $this->userId = $userId;
        }
    }

    /**
     * Update the user's password.
     *
     * @return void
     */
    public function updatePassword()
    {
        $this->resetErrorBag();

        $user = User::where('id', $this->userId)->first();

        $input = $this->state;

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();

        $this->state = [
            'password' => '',
        ];

        $this->emit('saved');
    }

    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    public function getUserProperty()
    {
        return User::where('id', $this->userId)->first();

    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('user::admin.livewire.edit-user.update-password-without-confirm-form-modal');
    }
}
