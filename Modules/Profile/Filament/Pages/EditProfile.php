<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use MicroweberPackages\User\UserManager;

class EditProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static string $view = 'modules.profile::pages.edit-profile';
    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user();
        $this->form->fill([
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'thumbnail' => $user->thumbnail,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getAvatarFormComponent(),
                $this->getFirstNameFormComponent(),
                $this->getLastNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPhoneFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getAvatarFormComponent(): Component
    {
        return FileUpload::make('thumbnail')
            ->label(__('Profile Photo'))
            ->image()
            ->disk('public')
            ->directory('profile-photos')
            ->visibility('public')
            ->imagePreviewHeight('150')
            ->loadingIndicatorPosition('left')
            ->panelAspectRatio('2:1')
            ->panelLayout('integrated')
            ->removeUploadedFileButtonPosition('right')
            ->uploadButtonPosition('left')
            ->uploadProgressIndicatorPosition('left');
    }

    protected function getFirstNameFormComponent(): Component
    {
        return TextInput::make('first_name')
            ->label(__('First Name'))
            ->required()
            ->maxLength(255);
    }

    protected function getLastNameFormComponent(): Component
    {
        return TextInput::make('last_name')
            ->label(__('Last Name'))
            ->required()
            ->maxLength(255);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('Email'))
            ->email()
            ->required()
            ->maxLength(255)
            ->unique('users', 'email', ignorable: Auth::user());
    }

    protected function getPhoneFormComponent(): Component
    {
        return TextInput::make('phone')
            ->label(__('Phone'))
            ->tel()
            ->maxLength(255);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('New Password'))
            ->password()
            ->minLength(8)
            ->same('password_confirmation');
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('password_confirmation')
            ->label(__('Confirm New Password'))
            ->password()
            ->minLength(8)
            ->dehydrated(false);
    }

    public function save(): void
    {
        try {
            $userManager = app(UserManager::class);
            
            $data = $this->form->getState();
            
            // Only include password if it's being changed
            if (empty($data['password'])) {
                unset($data['password']);
                unset($data['password_confirmation']);
            }
            
            $data['id'] = Auth::id();
            
            $response = $userManager->save($data);
            
            if (is_array($response) && isset($response['error'])) {
                throw ValidationException::withMessages([
                    'data.email' => $response['error'],
                ]);
            }

            $this->notify('success', __('Profile updated successfully.'));
            
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check();
    }
}
