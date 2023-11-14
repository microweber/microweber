<?php

namespace MicroweberPackages\User\Http\Livewire\Admin;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\User\Models\User;

class DeleteUserForm extends AdminComponent
{
    /**
     * Indicates if user deletion is being confirmed.
     *
     * @var bool
     */
    public $confirmingUserDeletion = false;

    /**
     * The user's current password.
     *
     * @var string
     */
    public $password = '';

    public $userId = false;

    /**
     * Confirm that the user would like to delete their account.
     *
     * @return void
     */
    public function confirmUserDeletion()
    {
        $this->password = '';

        $this->dispatchBrowserEvent('confirming-delete-user');

        $this->confirmingUserDeletion = true;
    }

    /**
     * Delete the current user.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $auth
     * @return void
     */
    public function deleteUser(StatefulGuard $auth)
    {
        $this->resetErrorBag();

//        if (! Hash::check($this->password, Auth::user()->password)) {
//            throw ValidationException::withMessages([
//                'password' => [__('This password does not match our records.')],
//            ]);
//        }

        if ($this->userId) {
            $user = User::where('id', $this->userId)->first();
        } else {
            $user = Auth::user();
        }

        $user->delete();

        return redirect()->route('admin.users.index');
    }

    public function mount($userId = false)
    {
        if ($userId) {
            $this->userId = $userId;
        }
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('admin::livewire.edit-user.delete-user-form');
    }
}
