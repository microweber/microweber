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

    /** The email the page loaded with — used to detect an actual change. */
    public ?string $originalEmail = null;

    public function mount(): void
    {
        $user = Auth::user();
        $this->originalEmail = $user->email;
        $this->form->fill([
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'thumbnail' => $user->thumbnail,
        ]);

        $this->passwordForm->fill();
    }

    /** True when the email field currently differs from the loaded value. */
    protected function emailIsChanging($get): bool
    {
        return trim((string) $get('email')) !== trim((string) $this->originalEmail);
    }

    /** May the current user change their own email? Admins always can. */
    protected function emailChangeAllowed(): bool
    {
        if (is_admin()) {
            return true;
        }

        return app()->user_manager->userMayChangeOwnEmail(Auth::user());
    }

    /** Why the email field is locked (null when it is editable). */
    protected function emailDisabledReason(): ?string
    {
        if ($this->emailChangeAllowed()) {
            return null;
        }

        if (app()->user_manager->isSocialLoginUser(Auth::user())) {
            return __('Your email is managed by your social login and cannot be changed here.');
        }

        return __('Changing your email address is disabled. Please contact an administrator.');
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
                $this->getCurrentPasswordForEmailComponent(),
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
            ->live(onBlur: true)
            ->disabled(fn (): bool => ! $this->emailChangeAllowed())
            ->helperText(function ($get): ?string {
                if (! $this->emailChangeAllowed()) {
                    return $this->emailDisabledReason();
                }

                return $this->emailIsChanging($get)
                    ? __('Changing your email needs your current password, and a confirmation link will be sent to the new address before it takes effect.')
                    : null;
            })
            ->unique('users', 'email', ignorable: Auth::user());
    }

    /**
     * Confirm the current password — only shown/required while the email is
     * actually being changed (a sensitive action) and that is permitted. Not
     * persisted to the user.
     */
    protected function getCurrentPasswordForEmailComponent(): Component
    {
        return TextInput::make('current_password_for_email')
            ->label(__('Current Password'))
            ->password()
            ->visible(fn ($get): bool => $this->emailChangeAllowed() && $this->emailIsChanging($get))
            ->required(fn ($get): bool => $this->emailChangeAllowed() && $this->emailIsChanging($get))
            ->dehydrated(true);
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

            $currentPasswordForEmail = $data['current_password_for_email'] ?? null;
            unset($data['current_password_for_email']);

            // Email is handled separately (verified change); never let the direct
            // update touch it.
            $newEmail = isset($data['email']) ? trim((string) $data['email']) : null;
            unset($data['email']);
            $emailChanged = $newEmail !== null && $newEmail !== '' && $newEmail !== trim((string) $user->email);

            // Policy may forbid the change (social login / flag off, non-admin);
            // the field is also disabled in that case, this is defence in depth.
            if ($emailChanged && ! $this->emailChangeAllowed()) {
                $emailChanged = false;
                $newEmail = null;
            }

            if ($emailChanged) {
                // Sensitive action: require the current password.
                if (empty($currentPasswordForEmail) || !Hash::check($currentPasswordForEmail, $user->password)) {
                    throw ValidationException::withMessages([
                        'data.current_password_for_email' => __('The current password is incorrect.'),
                    ]);
                }
            }

            // Save every other profile field immediately.
            $user->update($data);

            if ($emailChanged) {
                if (app()->user_manager->emailChangeVerificationEnabled()) {
                    // Park the change and email a confirmation link to the new
                    // address (old address keeps working + is notified).
                    app()->user_manager->requestEmailChange($user, $newEmail);
                    $this->originalEmail = $user->email; // unchanged until confirmed

                    Notification::make()
                        ->title(lang('Confirm your new email'))
                        ->body(lang('A confirmation link was sent to your new address. Your email changes once you confirm it.'))
                        ->success()
                        ->send();

                    return;
                }

                // Verification disabled: apply immediately (still password-gated).
                $user->update(['email' => $newEmail]);
                $this->originalEmail = $newEmail;
            }

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
