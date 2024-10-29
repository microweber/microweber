<?php

namespace Modules\FacebookPage\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class FacebookPageModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'facebook_page';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('General')
                            ->schema([
                                TextInput::make('options.fbPage')
                                    ->label('Facebook Page URL')
                                    ->helperText('Enter the URL of the Facebook page.')
                                    ->live()
                                    ->default('https://www.facebook.com/Microweber/'),

                                TextInput::make('options.width')
                                    ->label('Width')
                                    ->helperText('Enter the width of the Facebook page widget.')
                                    ->live()
                                    ->default('380'),

                                TextInput::make('options.height')
                                    ->label('Height')
                                    ->helperText('Enter the height of the Facebook page widget.')
                                    ->live()
                                    ->default('300'),

                                Toggle::make('options.friends')
                                    ->label('Show Friends Faces')
                                    ->helperText('Enable to show friends faces.')
                                    ->live()
                                    ->default(false),

                                Toggle::make('options.timeline')
                                    ->label('Show Timeline')
                                    ->helperText('Enable to show the timeline.')
                                    ->live()
                                    ->default(false),
                            ]),
                    ]),
            ]);
    }
}
