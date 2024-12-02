<?php

namespace Modules\Slider\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class SliderModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'slider';
    public array $slides;

    public function mount(): void
    {
        parent::mount();
        $this->slides = @json_decode($this->getOption('slides'), true) ?? [];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('slides')
                    ->deleteAction(
                        function (Forms\Get $get, Forms\Set $set) {
                            $this->slides = array_values($this->slides);
                            $set('slides', $this->slides);
                        }
                    )
                    ->itemLabel(fn(array $state): ?string => $state['title'] ?? 'New Slide') // Dynamically sets item label
                    ->label('Slides')
                    ->schema([
                        // Main Section
                        Section::make()
                            ->heading('Slide')
                            ->compact()
                            ->schema([
                                TextInput::make('title')
                                    ->label('Slide Title')
                                    ->placeholder('Insert slide title')
                                    ->live(false, 1500),

                                TextInput::make('description')
                                    ->label('Description')
                                    ->live(false, 1500),

                                MwFileUpload::make('image')
                                    ->label('Image URL')
                                    ->live(false, 1500),

                                TextInput::make('buttonText')
                                    ->label('Button Text')
                                    ->live(false, 1500),

                                MwLinkPicker::make('url')
                                    ->label('Button URL')
                                    ->helperText('Select or enter the URL the button should link to.')
                                    ->live()
                                    ->setSimpleMode(true)
                                    ->columnSpanFull(),
                            ])
                            ->collapsible(false), // Non-collapsible main section

                        // Collapsible Advanced Section
                        Section::make()
                            ->label('Advanced')
                            ->heading('Advanced')
                            ->compact()
                            ->columns(2)
                            ->schema([

                                TextInput::make('titleFontSize')
                                    ->label('Title Font Size')
                                    ->numeric()
                                    ->live(false, 1500),
                                ColorPicker::make('titleColor')
                                    ->label('Title Color')
                                    ->live(false, 1500),

                                TextInput::make('descriptionFontSize')
                                    ->label('Description Font Size')
                                    ->numeric()
                                    ->live(false, 1500),

                                ColorPicker::make('descriptionColor')
                                    ->label('Description Color')
                                    ->live(false, 1500),


                                TextInput::make('buttonFontSize')
                                    ->label('Button Font Size')
                                    ->numeric()
                                    ->live(false, 1500),

                                ColorPicker::make('buttonColor')
                                    ->label('Button Color')
                                    ->live(false, 1500),

                                ColorPicker::make('buttonTextColor')
                                    ->label('Button Text Color')
                                    ->live(false, 1500),


                                TextInput::make('titleFontFamily')
                                    ->label('Title Font Family')
                                    ->live(false, 1500),

                                TextInput::make('descriptionFontFamily')
                                    ->label('Description Font Family')
                                    ->live(false, 1500),

                                ColorPicker::make('imageBackgroundColor')
                                    ->label('Image Background Color')
                                    ->live(false, 1500),

                                TextInput::make('imageBackgroundOpacity')
                                    ->label('Image Background Opacity')
                                    ->numeric()
                                    ->live(false, 1500),
                                Select::make('alignItems')
                                    ->label('Align Items')
                                    ->options([
                                        'left' => 'Left',
                                        'center' => 'Center',
                                        'right' => 'Right',
                                    ])
                                    ->live(false, 1500),
                                Select::make('showButton')
                                    ->label('Show Button')
                                    ->options([
                                        '1' => 'Yes',
                                        '0' => 'No',
                                    ])
                                    ->default('1')
                                    ->live(false, 1500),
                            ])
                            ->collapsible(true)
                            ->collapsed(),
                    ])
                    ->collapsible(true)
                    ->minItems(0)

            ]);
    }


    public function updated($propertyName, $value): void
    {
        parent::updated($propertyName, $value);
        $this->saveOption('slides', json_encode($this->slides));
    }

    public function save(): void
    {
        parent::save();
        $this->saveOption('slides', json_encode($this->slides));
    }
}
