<?php

namespace Modules\Comments\Filament;

use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class CommentsModuleSettingsLiveEdit extends LiveEditModuleSettings
{
    public string $module = 'comments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('options.show_on_current_content')
                    ->label('Show Comments on Current Content')
                    ->helperText('Enable to display comments for the current content item')
                    ->default(true)
                    ->live(debounce: 500),

                TextInput::make('options.comments_per_page')
                    ->label('Comments Per Page')
                    ->type('number')
                    ->default(10)
                    ->minValue(1)
                    ->maxValue(100)
                    ->live(debounce: 500),

                Select::make('options.sort_order')
                    ->label('Sort Order')
                    ->options([
                        'newest' => 'Newest First',
                        'oldest' => 'Oldest First',
                        'most_liked' => 'Most Liked'
                    ])
                    ->default('newest')
                    ->live(debounce: 500),

                Toggle::make('options.show_user_avatar')
                    ->label('Show User Avatar')
                    ->helperText('Display user avatars next to comments')
                    ->default(true)
                    ->live(debounce: 500),
            ]);
    }
}
