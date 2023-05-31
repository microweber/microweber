<?php

namespace MicroweberPackages\User\Http\Livewire\Admin;

use Illuminate\Support\Facades\Hash;
use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\User\Models\User;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Validator;

class UpdatePasswordWithoutConfirmFormModal extends AdminModalComponent
{
    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [
        'password' => '',
        'password_confirm' => '',
    ];

    public $userId;

    public function mount($userId = false) {

        if ($userId) {
            $this->userId = $userId;
        }
    }

    public $saved = false;

    /**
     * Update the user's password.
     *
     * @return void
     */
    public function updatePassword()
    {
        $this->resetErrorBag();

        $input = $this->state;

        Validator::make($input, [
            'password' => 'required|min:1|required_with:password_confirm|same:password_confirm',
            'password_confirm' => 'min:1',
        ])->validateWithBag('updateUserPassword');

        $user = User::where('id', $this->userId)->first();

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();

        $this->saved = true;
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
