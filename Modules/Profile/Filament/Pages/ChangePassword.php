<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ChangePassword extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static string $view = 'modules.profile::pages.change-password';
    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getCurrentPasswordFormComponent(),
                $this->getNewPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getCurrentPasswordFormComponent(): Component
    {
        return TextInput::make('current_password')
            ->label(__('Current Password'))
            ->password()
            ->required()
            ->minLength(1);
    }

    protected function getNewPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('New Password'))
            ->password()
            ->required()
            ->minLength(1)
            ->same('password_confirmation');
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('password_confirmation')
            ->label(__('Confirm New Password'))
            ->password()
            ->required()
            ->minLength(1)
            ->dehydrated(false);
    }

    public function save(): void
    {

        $data = $this->form->getState();
        $user = Auth::user();

        // Verify current password
        if (!Hash::check($data['current_password'], $user->password)) {

            Notification::make()
                ->title(lang('Error'))
                ->body(lang('The current password is incorrect.'))
                ->danger()
                ->send();
            return;
        }

        $user->update([
            'password' => bcrypt($data['password'])
        ]);

        //$this->notify('success', __('Password changed successfully.'));
        $this->form->fill();

        Notification::make()
            ->title(lang('Success'))
            ->body(lang('Password changed successfully.'))
            ->success()
            ->send();


    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check();
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Profile');
    }
}
