<?php

namespace MicroweberPackages\User\Http\Livewire\Admin;


use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\User\Models\User;

class CreateProfileInformationForm extends AdminComponent
{

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];

    public function createProfileInformation()
    {
        $this->resetErrorBag();

        Validator::make($this->state, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', Rule::unique(User::class)],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)],
            'phone' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:4']

        ])->validateWithBag('createProfileInformation');

        $user = new User();
        $user->fill($this->state);
        $user->save();

        $this->emit('saved');

        return redirect(route('admin.users.edit', $user->id));

    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('admin::livewire.users.create-profile-information-form');
    }
}
