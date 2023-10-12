<?php

namespace MicroweberPackages\User\Http\Livewire\Admin;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Livewire\Component;
use Livewire\WithFileUploads;
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\User\Models\User;

class UpdateProfileInformationForm extends AdminComponent
{
    use WithFileUploads;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];

    /**
     * The new avatar for the user.
     *
     * @var mixed
     */
    public $photo;

    /**
     * @var string
     */
    public $photoUrl;


    public $userId = false;

    /**
     * Prepare the component.
     *
     * @return void
     */
    public function mount($userId = false)
    {
        if ($userId) {
            $this->userId = $userId;
            $this->state = User::where('id', $userId)->first()->withoutRelations()->toArray();
        } else {
            $this->state = Auth::user()->withoutRelations()->toArray();
        }

        if (!empty($this->state['thumbnail'])) {
            $this->photoUrl = user_picture($this->state['id'], 165,165);
        }
    }


    /**
     * Delete user's profile photo.
     *
     * @return void
     */
    public function deleteProfilePhoto()
    {
        if ($this->userId) {
            $user = User::where('id', $this->userId)->first();
        } else {
            $user = Auth::user();
        }

        $user->thumbnail = null;
        $user->save();

        $this->photo = null;
        $this->photoUrl = null;
    }

    /**
     * Update the user's profile information.
     * @return void
     */
    public function updateProfileInformation()
    {
        $this->resetErrorBag();

        if ($this->userId) {
            $user = User::where('id', $this->userId)->first();
        } else {
            $user = Auth::user();
        }

        $input = $this->state;

        if (isset($this->photo) && !empty($this->photo)) {
            if (method_exists($this->photo, 'guessExtension')) {
                $photoExt = $this->photo->guessExtension();
                $photoContent = $this->photo->get();
                $photoFile = media_base_path() . 'users/' . $user->id . '-avatar.' . $photoExt;
                if (!is_dir(dirname($photoFile))) {
                    mkdir_recursive(dirname($photoFile));
                }
                file_put_contents($photoFile, $photoContent);
                $user->thumbnail = media_base_url() . 'users/' . $user->id . '-avatar.' . $photoExt;
                $user->save();
            }
        }

        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ])->validateWithBag('updateProfileInformation');


        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'username' => $input['username'],
                'phone' => $input['phone'],
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'email' => $input['email'],
            ])->save();
        }

        if (isset($this->photo)) {
            return redirect()->route('admin.profile.show');
        }

        $this->emit('saved');
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser($user, array $input)
    {
        $user->forceFill([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'email' => $input['email'],
            'phone' => isset($input['phone']) ? $input['phone'] : null,
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }

    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    public function getUserProperty()
    {
        return Auth::user();
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('admin::livewire.edit-user.update-profile-information-form');
    }
}
