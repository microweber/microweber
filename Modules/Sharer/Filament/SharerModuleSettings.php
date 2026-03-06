<?php

namespace Modules\Sharer\Filament;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class SharerModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'sharer';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Settings')
                    ->schema([
                        Tabs\Tab::make('Content')
                            ->schema([
                                Toggle::make('options.facebook_enabled')
                                    ->label('Enable Facebook Sharing')
                                    ->live()
                                    ->default(false),

                                Toggle::make('options.x_enabled')
                                    ->label('Enable X Sharing')
                                    ->live()
                                    ->default(false),

                                Toggle::make('options.pinterest_enabled')
                                    ->label('Enable Pinterest Sharing')
                                    ->live()
                                    ->default(false),

                                Toggle::make('options.linkedin_enabled')
                                    ->label('Enable LinkedIn Sharing')
                                    ->live()
                                    ->default(false),

                                Toggle::make('options.viber_enabled')
                                    ->label('Enable Viber Sharing')
                                    ->live()
                                    ->default(false),

                                Toggle::make('options.whatsapp_enabled')
                                    ->label('Enable WhatsApp Sharing')
                                    ->live()
                                    ->default(false),

                                Toggle::make('options.telegram_enabled')
                                    ->label('Enable Telegram Sharing')
                                    ->live()
                                    ->default(false),
                            ]),

                        Tabs\Tab::make('Design')
                            ->schema([
                                Section::make('Design Settings')
                                    ->schema($this->getTemplatesFormSchema()),
                            ]),
                    ]),
            ]);
    }
}
