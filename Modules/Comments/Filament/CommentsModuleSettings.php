<?php
// Comment Policy Settings Form - Verified Structure
namespace Modules\Comments\Filament;

use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
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
                        Toggle::make('options.require_approval')
                            ->label('Require Approval')
                            ->default(true),
                        Toggle::make('options.notify_authors')
                            ->label('Notify Authors')
                            ->default(true),
                        Toggle::make('options.show_on_current_content')
                            ->label('Show Comments on Current Content')
                            ->default(true),
                        TextInput::make('options.comments_per_page')
                            ->label('Comments Per Page')
                            ->default(10),
                        Select::make('options.sort_order')
                            ->label('Sort Order')
                            ->options([
                                'newest' => 'Newest First',
                                'oldest' => 'Oldest First'
                            ])
                            ->default('newest'),
                        Toggle::make('options.show_user_avatar')
                            ->label('Show User Avatar')
                            ->default(true)
                    ])
            ]);
    }
}