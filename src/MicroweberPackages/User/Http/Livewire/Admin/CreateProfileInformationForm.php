<?php

namespace MicroweberPackages\User\Http\Livewire\Admin;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Livewire\Component;
use Livewire\WithFileUploads;
use MicroweberPackages\User\Models\User;

class CreateProfileInformationForm extends Component
{
    use WithFileUploads;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];

    public function createProfileInformation()
    {
        $this->resetErrorBag();

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
