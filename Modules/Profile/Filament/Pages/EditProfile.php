<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
            ])
            ->statePath('data');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Profile');
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
