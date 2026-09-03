<?php
// Comment Policy Settings Form - Verified Structure
namespace Modules\Comments\Filament;

use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class CommentsModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'comments';

    public function getSetting($key)
    {
        return $this->getOption($key, true);
    }

    public function setSettings(array $settings)
    {
        foreach ($settings as $key => $value) {
            save_option([
                'option_key' => $key,
                'option_value' => $value,
                'option_group' => $this->module
            ]);
        }
        return true;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Comments Settings')
                    ->schema([
                        Toggle::make('options.enable_moderation')
                            ->label('Require Approval')
                            ->live()
                            ->default(fn () => filter_var($this->getOption('enable_moderation', false), FILTER_VALIDATE_BOOL)),
                        Toggle::make('options.notify_admin')
                            ->label('Notify Admin')
                            ->live()
                            ->default(fn () => filter_var($this->getOption('notify_admin', false), FILTER_VALIDATE_BOOLEAN)),
                        Toggle::make('options.notify_users')
                            ->label('Notify Users on Reply')
                            ->live()
                            ->default(fn () => filter_var($this->getOption('notify_users', false), FILTER_VALIDATE_BOOLEAN)),
                        Toggle::make('options.show_on_current_content')
                            ->label('Show Comments on Current Content')
                            ->default(fn () => filter_var($this->getOption('show_on_current_content', true), FILTER_VALIDATE_BOOLEAN))->live(),
                        TextInput::make('options.comments_per_page')
                            ->label('Comments Per Page')
                            ->default(fn () => $this->getOption('comments_per_page', 10))->live()->numeric(),
                        Select::make('options.sort_order')
                            ->label('Sort Order')
                            ->options([
                                'newest' => 'Newest First',
                                'oldest' => 'Oldest First'
                            ])
                            ->default(fn () => $this->getOption('sort_order', 'newest'))->live(),
                        Toggle::make('options.show_user_avatar')
                            ->label('Show User Avatar')
                            ->default(fn () => filter_var($this->getOption('show_user_avatar', true), FILTER_VALIDATE_BOOL))->live()
                    ])
            ]);
    }
}