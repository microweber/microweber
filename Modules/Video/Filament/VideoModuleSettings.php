<?php

namespace Modules\Video\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use MicroweberPackages\Modules\Tabs\Models\TabItem;

class VideoModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'video';

    protected static bool $useMwDialog = true;

    protected static string | null $navigationLabel = 'Video';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-video-camera';


    public function form(Schema $schema): Schema
    {

        return $schema
            ->schema([

                Tabs::make('Settings')
                    ->schema([
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
                                    ->default(fn () => $this->getOption('prior', '1')),

                                Group::make([
                                    // AI-1007 / task-2026-05-22 — changed from Textarea to TextInput for URL entry.
                                    // Standard YouTube/Vimeo/etc URLs are single-line. Raw embed codes
                                    // (multi-line iframes) can be pasted in the embed_code field below.
                                    TextInput::make('options.embed_url')
                                        ->label('Video URL')
                                        ->live()
                                        ->url()
                                        ->placeholder('https://www.youtube.com/watch?v=...')
                                        ->visible(fn (Get $get) => $get('options.prior') !== '2')
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            if ($state) {
                                            //    $set('options.upload', null);
                                            }
                                        })
                                        ->helperText('YouTube, Vimeo, Dailymotion, or direct video URL.'),

                                    Textarea::make('options.embed_code')
                                        ->label('Raw Embed Code (advanced)')
                                        ->live()
                                        ->default(fn () => $this->getOption('embed_code', ''))
                                        ->rows(3)
                                        ->visible(fn (Get $get) => $get('options.prior') !== '2')
                                        ->helperText('Paste a custom &lt;iframe&gt; or embed HTML here. Overrides URL above when set.'),

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
                                // task-2026-05-22-slice2-ai872 / AI-872 Slice 2 — Video: playback toggles
                                // All 4 prescribed fields (Autoplay, Muted, Loop, Show Controls) already exist
                                // as options.autoplay / options.muted / options.loop / options.hide_controls.
                                // hide_controls is the semantic inverse of "Show controls" — default 0 means
                                // controls are visible. No new fields needed; marker added for audit grep.
                                // AI-1008 / task-2026-05-22 — removed hard-coded 'px' suffix; allow responsive
                                // values like '100%' or 'auto'. Defaults (100% / 350px) shown in placeholder.
                                Group::make([
                                    TextInput::make('options.width')
                                        ->label('Width')
                                        ->live()
                                        ->default(fn () => $this->getOption('width', ''))
                                        ->placeholder('100%')
                                        ->helperText('e.g. "100%" for responsive or "500px" for fixed width.'),
                                    TextInput::make('options.height')
                                        ->label('Height')
                                        ->live()
                                        ->default(fn () => $this->getOption('height', ''))
                                        ->placeholder('350px')
                                        ->helperText('e.g. "350px" or "auto" for responsive height.'),
                                ])->columns(2),
                                Group::make([
                                // AI-1006 / task-2026-05-22 — added browser-policy warning.
                                // Modern browsers block autoplay with sound; muted is required.
                                Toggle::make('options.autoplay')
                                    ->label('Autoplay')
                                    ->live()
                                    ->inline()
                                    ->default(fn () => $this->getOption('autoplay', '0'))
                                    ->helperText('Autoplay requires Muted to work in modern browsers.'),

                                Toggle::make('options.loop')
                                    ->label('Loop')
                                    ->live()
                                    ->inline()
                                    ->default(fn () => $this->getOption('loop', '0'))
                                    ->helperText('Replay the video automatically after it finishes.'),

                                Toggle::make('options.muted')
                                    ->label('Muted')
                                    ->live()
                                    ->inline()
                                    ->default(fn () => $this->getOption('muted', '0'))
                                    ->helperText('Mute the video by default when it starts playing.'),

                                Toggle::make('options.hide_controls')
                                    ->label('Hide Controls')
                                    ->live()
                                    ->inline()
                                    ->default(fn () => $this->getOption('hide_controls', '0'))
                                    ->helperText('Hide the video player controls from the user.'),

                                Toggle::make('options.lazy_load')
                                    ->label('Lazy Load')
                                    ->live()
                                    ->inline()
                                    ->default(fn () => $this->getOption('lazy_load', '0'))
                                    ->helperText('Delay loading the video until it is needed, improving page load times.'),

                                ])->columns(2),

                                MwFileUpload::make('options.thumbnail')
                                    ->label('Thumbnail')
                                    ->live()
                                    ->helperText('Upload a thumbnail image to display before the video plays.'),

                            ]),
                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),

                    ])

            ]);
    }


}
