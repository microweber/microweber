<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Schemas\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class EditProfile extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 1;
    protected string $view = 'modules.profile::pages.edit-profile';
    public ?array $data = [];
    public ?array $passwordData = [];

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

        $this->passwordForm->fill();
    }

    /**
     * This page hosts two independent forms: the profile details form and an
     * embedded "Change Password" form (moved here from the standalone
     * ChangePassword page, which is now hidden from the sidebar).
     *
     * @return array<int, string>
     */
    protected function getForms(): array
    {
        return [
            'form',
            'passwordForm',
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                $this->getAvatarFormComponent(),
                $this->getFirstNameFormComponent(),
                $this->getLastNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPhoneFormComponent(),
            ])
            ->statePath('data');
    }

    public function passwordForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('current_password')
                    ->label(__('Current Password'))
                    ->password()
                    ->required()
                    ->minLength(1),
                TextInput::make('password')
                    ->label(__('New Password'))
                    ->password()
                    ->required()
                    ->minLength(1)
                    ->same('password_confirmation'),
                TextInput::make('password_confirmation')
                    ->label(__('Confirm New Password'))
                    ->password()
                    ->required()
                    ->minLength(1)
                    ->dehydrated(false),
            ])
            ->statePath('passwordData');
    }

    public function changePassword(): void
    {
        $data = $this->passwordForm->getState();
        $user = Auth::user();

        if (! Hash::check($data['current_password'], $user->password)) {
            Notification::make()
                ->title(lang('Error'))
                ->body(lang('The current password is incorrect.'))
                ->danger()
                ->send();
            return;
        }

        $user->update(['password' => bcrypt($data['password'])]);
        $this->passwordForm->fill();

        Notification::make()
            ->title(lang('Success'))
            ->body(lang('Password changed successfully.'))
            ->success()
            ->send();
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Profile');
    }

    protected function getAvatarFormComponent(): Component
    {
        // Compact circular avatar uploader. The previous panelAspectRatio('2:1')
        // + integrated layout blew the drop zone into a huge full-width row that
        // pushed the rest of the form far down the page.
        return FileUpload::make('thumbnail')
            ->label(__('Profile Photo'))
            ->avatar()
            ->disk('public')
            ->directory('profile-photos')
            ->visibility('public')
            ->imageEditor()
            ->circleCropper()
            ->loadingIndicatorPosition('left')
            ->removeUploadedFileButtonPosition('right')
            ->uploadProgressIndicatorPosition('left')
            ->columnSpanFull();
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

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            $user = Auth::user();


            if (isset($data['is_admin']) && !is_admin()) {
                unset($data['is_admin']);
            }

            $user->update($data);

            Notification::make()
                ->title(lang('Success'))
                ->body(lang('Your profile has been updated.'))
                ->success()
                ->send();

        } catch (ValidationException $e) {
            throw $e;
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check();
    }
}
