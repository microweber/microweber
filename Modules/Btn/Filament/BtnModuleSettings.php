<?php

namespace Modules\Btn\Filament;

use Filament\Forms\Components\ColorPicker;
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
use MicroweberPackages\Filament\Forms\Components\MwIconPicker;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use MicroweberPackages\Multilanguage\Forms\Actions\TranslateFieldAction;
use Modules\Menu\Models\Menu;

class BtnModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'btn';

    protected static bool $useMwDialog = true;

    public function form(Schema $schema): Schema
    {

        return $schema
            ->schema([
                Tabs::make('Settings')
                    ->schema([
                        Tabs\Tab::make('Content')
                            ->schema([

                                // task-2026-05-22-cd4d21 / AI-914 — Text and Link not pre-populated on
                                // open. Static ->default('Button') ignored when form mounts because
                                // Filament treats any non-null data value as authoritative; on new
                                // (empty-data) opens the static default wins but hides saved values.
                                // Fix: read the currently saved option via $this->getOption() so
                                // the field shows the real saved value on every open.
                                TextInput::make('options.text')
                                    ->label('Text')
                                    ->helperText('Enter the text to display on the button.')
                                    ->live()
                                    ->default(fn () => $this->getOption('text', 'Button'))
                                ->mwTranslatableOption()
                                ,


                                MwLinkPicker::make('options.url')

                                    ->label('Link')
                                    ->setSimpleMode(true)
                                    ->helperText('Select or enter the URL the button should link to.')
                                    ->live()
                                    ->default(fn () => $this->getOption('url', ''))

                                    ->columnSpanFull(),


                                // task-2026-05-22-cd4d21 / AI-913 — Align ToggleButtons renders
                                // all three options as active (solid blue) when form opens because
                                // there is no ->default() so Filament has no anchored value to
                                // compare against. Fix: read the saved align setting via
                                // $this->getOption() so the ToggleButtons initialises with exactly
                                // one option highlighted.
                                ToggleButtons::make('options.align')
                                    ->helperText('Choose the alignment of the button.')
                                    ->live()
                                    ->options([
                                        'left' => 'Left',
                                        'center' => 'Center',
                                        'right' => 'Right',
                                    ])
                                    ->inline()
                                    ->icons([
                                        'left' => 'heroicon-o-bars-3-bottom-left',
                                        'center' => 'heroicon-o-bars-3',
                                        'right' => 'heroicon-o-bars-3-bottom-right',
                                    ])
                                    ->default(fn () => $this->getOption('align', 'left')),

                                Toggle::make('options.urlBlank')
                                    ->helperText('Enable to open the link in a new window.')
                                    ->live()
                                    ->label('Open link in new window')
                                    ->default(fn () => filter_var($this->getOption('urlBlank', false), FILTER_VALIDATE_BOOLEAN))
                                    ->columnSpanFull(),

                            ]),
                        Tabs\Tab::make('Design')
                            ->schema([

                                Section::make('Icon Settings')
                                    ->columns(2)
                                    ->schema([
                                        MwIconPicker::make('options.icon')
                                            ->label('Button Icon')
                                            ->helperText('Select an icon to display on the button.')
                                            ->live()
                                            ->default(fn () => $this->getOption('icon', '')),

                                        ToggleButtons::make('options.iconPosition')
                                            ->label('Button Icon Position ')
                                            ->helperText('Choose the position of the icon on the button.')
                                            ->live()
                                            ->inline()
                                            ->options([
                                                'left' => 'Left',
                                                'right' => 'Right',
                                            ])
                                            ->icons([
                                                'left' => 'heroicon-o-bars-3-bottom-left',
                                                'right' => 'heroicon-o-bars-3-bottom-right',
                                            ])
                                            ->default(fn () => $this->getOption('iconPosition', 'left')),
                                    ]),

//                                Select::make('options.style')
//                                    ->label('Button Style')
//                                    ->helperText('Select the style of the button.')
//                                    ->live()
//                                    ->options([
//                                        'normal' => 'Normal',
//                                        'primary' => 'Primary',
//                                        'secondary' => 'Secondary',
//                                        'outline'=> 'Outline',
//                                        'link' => 'Link',
//                                    ])
//                                    ->default('btn-primary'),
//
//                                Select::make('options.size')
//                                    ->label('Button Size')
//                                    ->helperText('Select the size of the button.')
//                                    ->live()
//                                    ->options([
//                                        'default' => 'Default',
//                                        'large' => 'Large',
//                                        'medium' => 'Medium',
//                                        'small' => 'Small',
//                                        'mini' => 'Mini',
//                                    ]),
                                Section::make('Design Settings')->schema(
                                    $this->getTemplatesFormSchema()),


                                $this->getCustomSettingsFormSchema(),


                            ])

                    ])


            ]);
    }


    private function getCustomSettingsFormSchema()
    {
        return Section::make('Advanced settings')->schema([
            //button_action
            Select::make('options.action')
                ->label('Button Action')
                ->live()
                ->options([
                    'default' => 'Go to link',
                    'popup' => 'Popup',
//                                            'submit' => 'Submit',
//                                            'reset' => 'Reset',
                ])
                ->default(fn () => $this->getOption('action', 'default')),
            //popupcontent if action is popoup
            Textarea::make('options.popupContent')
                ->label('Popup Content')
                ->live()
                ->visible(function (Get $get) {

                    return $get('options.action') === 'popup';

                })
                ->default(fn () => $this->getOption('popupContent', '')),


            //backgroundColor

            ColorPicker::make('options.backgroundColor')
                ->label('Background Color')
                ->live()
                ->default(fn () => $this->getOption('backgroundColor', '')),


            ColorPicker::make('options.color')
                ->label('Text Color')
                ->live()
                ->default(fn () => $this->getOption('color', '')),


            ColorPicker::make('options.borderColor')
                ->label('Border color')
                ->live()
                ->default(fn () => $this->getOption('borderColor', '')),

            TextInput::make('options.borderWidth')
                ->label('Border width')
                ->live()
                ->numeric()
                ->default(fn () => $this->getOption('borderWidth', '')),

            TextInput::make('options.borderRadius')
                ->label('Border radius')
                ->live()
                ->numeric()
                ->default(fn () => $this->getOption('borderRadius', '')),


            // task-2026-05-31-d8c2a1 / AI-1197 — duplicate-word typo "Hover hover color".
            // This picker controls TEXT colour on hover (sibling to hoverbackgroundColor +
            // hoverborderColor). Renamed to "Hover text color" so the AT-announced label,
            // the visual label, and the field semantics agree.
            ColorPicker::make('options.hovercolor')
                ->label('Hover text color')
                ->live()
                ->default(fn () => $this->getOption('hovercolor', '')),

            ColorPicker::make('options.hoverbackgroundColor')
                ->label('Hover background color')
                ->live()
                ->default(fn () => $this->getOption('hoverbackgroundColor', '')),


            ColorPicker::make('options.hoverborderColor')
                ->label('Hover border color')
                ->live()
                ->default(fn () => $this->getOption('hoverborderColor', '')),


            TextInput::make('options.customSize')
                ->label('Custom size')
                ->live()
                ->numeric()
                ->default(fn () => $this->getOption('customSize', '')),


        ])->collapsed();
    }


}
