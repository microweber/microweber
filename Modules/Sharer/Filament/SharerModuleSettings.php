<?php

namespace Modules\Sharer\Filament;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
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
                                Section::make('Share buttons')
                                    ->description('Choose which networks visitors can share to.')
                                    ->schema([
                                        Toggle::make('options.facebook_enabled')
                                            ->label('Facebook')
                                            ->live()
                                            ->default(fn () => filter_var($this->getOption('facebook_enabled', false), FILTER_VALIDATE_BOOLEAN)),

                                        Toggle::make('options.x_enabled')
                                            ->label('X')
                                            ->live()
                                            ->default(fn () => filter_var($this->getOption('x_enabled', false), FILTER_VALIDATE_BOOLEAN)),

                                        Toggle::make('options.pinterest_enabled')
                                            ->label('Pinterest')
                                            ->live()
                                            ->default(fn () => filter_var($this->getOption('pinterest_enabled', false), FILTER_VALIDATE_BOOLEAN)),

                                        Toggle::make('options.linkedin_enabled')
                                            ->label('LinkedIn')
                                            ->live()
                                            ->default(fn () => filter_var($this->getOption('linkedin_enabled', false), FILTER_VALIDATE_BOOLEAN)),

                                        Toggle::make('options.viber_enabled')
                                            ->label('Viber')
                                            ->live()
                                            ->default(fn () => filter_var($this->getOption('viber_enabled', false), FILTER_VALIDATE_BOOLEAN)),

                                        Toggle::make('options.whatsapp_enabled')
                                            ->label('WhatsApp')
                                            ->live()
                                            ->default(fn () => filter_var($this->getOption('whatsapp_enabled', false), FILTER_VALIDATE_BOOLEAN)),

                                        Toggle::make('options.telegram_enabled')
                                            ->label('Telegram')
                                            ->live()
                                            ->default(fn () => filter_var($this->getOption('telegram_enabled', false), FILTER_VALIDATE_BOOLEAN)),
                                    ]),
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
