<?php

namespace Modules\Video\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use MicroweberPackages\Modules\Tabs\Models\TabItem;

class VideoModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'video';

    public function form(Form $form): Form
    {

        return $form
            ->schema([

                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('Video')
                            ->schema([
                                Textarea::make('options.embed_url')
                                    ->label('Paste video URL or Embed Code')
                                    ->live(),
                                MwFileUpload::make('options.upload')
                                    ->label('Upload Video')
                            ]),

                        Tabs\Tab::make('Settings')
                            ->schema([
                                TextInput::make('options.width')
                                    ->label('Width')
                                    ->live(),
                                TextInput::make('options.height')
                                    ->label('Height')
                                    ->live(),

                                Toggle::make('options.autoplay')
                                    ->label('Autoplay')
                                    ->live()
                                    ->inline()
                                    ->default('0'),

                                Toggle::make('options.loop')
                                    ->label('Loop')
                                    ->live()
                                    ->inline()
                                    ->default('0'),

                                Toggle::make('options.muted')
                                    ->label('Muted')
                                    ->live()
                                    ->inline()
                                    ->default('0'),

                                Toggle::make('options.hide_controls')
                                    ->label('Hide Controls')
                                    ->live()
                                    ->inline()
                                    ->default('0'),

                                Toggle::make('options.lazy_load')
                                    ->label('Lazy Load')
                                    ->live()
                                    ->inline()
                                    ->default('0'),

                                MwFileUpload::make('options.thumbnail')
                                    ->label('Thumbnail')
                                    ->live(),



                            ]),
                        Tabs\Tab::make('Design')
                            ->schema([
                            ]),

                    ])

            ]);
    }


}
