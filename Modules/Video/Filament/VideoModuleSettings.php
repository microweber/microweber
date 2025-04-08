<?php

namespace Modules\Video\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Group;
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

    protected static ?string $navigationLabel = 'Video';
    protected static ?string $navigationIcon = 'heroicon-o-video-camera';


    public function form(Form $form): Form
    {

        return $form
            ->schema([

                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('Video')
                            ->columnSpanFull()
                            ->schema([
                                ToggleButtons::make('options.prior')
                                    ->label('Video Source')
                                    ->live()
                                    ->columnSpanFull()
                                    ->reactive()
                                    ->options([
                                        '1' => 'Embed Video',
                                        '2' => 'Upload Video',
                                    ])
                                    ->afterStateUpdated(function ($state, callable $set) {

                                    })
                                    ->default('1'),

                                Group::make([
                                    Textarea::make('options.embed_url')
                                        ->label('Paste video URL or Embed Code')
                                        ->live()
                                        ->visible(fn (Get $get) => $get('options.prior') !== '2')
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            if ($state) {
                                            //    $set('options.upload', null);
                                            }
                                        })
                                        ->helperText('Enter the URL or embed code for the video you want to display.'),

                                    MwFileUpload::make('options.upload')
                                        ->label('Upload Video')
                                        ->live()
                                        ->video()
                                        ->visible(fn (Get $get) => $get('options.prior') === '2')
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            if ($state) {
                                             //   $set('options.embed_url', null);
                                            }
                                        })
                                        ->helperText('Upload a video file from your computer.'),
                                ]),
                            ]),



                        Tabs\Tab::make('Settings')
                            ->schema([
                                Group::make([
                                    TextInput::make('options.width')
                                        ->label('Width')
                                        ->suffix('px')
                                        ->live()
                                        ->helperText('Specify the width of the video player in pixels.'),
                                    TextInput::make('options.height')
                                        ->label('Height')
                                        ->suffix('px')
                                        ->live()
                                        ->helperText('Specify the height of the video player in pixels.'),
                                ])->columns(2),
                                Group::make([
                                Toggle::make('options.autoplay')
                                    ->label('Autoplay')
                                    ->live()
                                    ->inline()
                                    ->default('0')
                                    ->helperText('Automatically start playing the video when the page loads.'),

                                Toggle::make('options.loop')
                                    ->label('Loop')
                                    ->live()
                                    ->inline()
                                    ->default('0')
                                    ->helperText('Replay the video automatically after it finishes.'),

                                Toggle::make('options.muted')
                                    ->label('Muted')
                                    ->live()
                                    ->inline()
                                    ->default('0')
                                    ->helperText('Mute the video by default when it starts playing.'),

                                Toggle::make('options.hide_controls')
                                    ->label('Hide Controls')
                                    ->live()
                                    ->inline()
                                    ->default('0')
                                    ->helperText('Hide the video player controls from the user.'),

                                Toggle::make('options.lazy_load')
                                    ->label('Lazy Load')
                                    ->live()
                                    ->inline()
                                    ->default('0')
                                    ->helperText('Delay loading the video until it is needed, improving page load times.'),

                                ])->columns(2),

                                MwFileUpload::make('options.thumbnail')
                                    ->label('Thumbnail')
                                    ->live()
                                    ->helperText('Upload a thumbnail image to display before the video plays.'),

                            ]),
                        Tabs\Tab::make('Design')
                            ->schema([
                            ]),

                    ])

            ]);
    }


}
