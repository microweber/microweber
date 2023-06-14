<?php

namespace MicroweberPackages\User\Http\Livewire\Admin;

use Illuminate\Contracts\Auth\StatefulGuard;
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

class LoginAsUserForm extends AdminComponent
{
    /**
     * Indicates if user deletion is being confirmed.
     *
     * @var bool
     */
    public $confirmingUserLogin = false;


    public $userId = false;

    /**
     * Confirm that the user would like to delete their account.
     *
     * @return void
     */
    public function confirmUserLogin()
    {


        $this->confirmingUserLogin = true;
    }

    /**
     * Delete the current user.
     *
     * @param \Illuminate\Contracts\Auth\StatefulGuard $auth
     * @return void
     */
    public function loginAsUser(StatefulGuard $auth)
    {
        if (!is_admin()) {
            return;
        }
        app()->user_manager->make_logged($this->userId);
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
        return view('admin::livewire.edit-user.login-as-user-form');
    }
}
