<?php

namespace Modules\Slider\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
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
                    ->label('Slides')
                    ->schema([
                        TextInput::make('title')
                            ->label('Slide Title')
                            ->placeholder('Insert slide title')
                            ->live(),

                        TextInput::make('description')
                            ->label('Description')
                            ->live(),

                        MwFileUpload::make('image')
                            ->label('Image URL')
                            ->live(),

                        TextInput::make('buttonText')
                            ->label('Button Text')
                            ->live(),

                        MwLinkPicker::make('url')
                            ->label('Button URL')
                            ->helperText('Select or enter the URL the button should link to.')
                            ->live()
                            ->setSimpleMode(true)
                            ->columnSpanFull(),

                        Select::make('alignItems')
                            ->label('Align Items')
                            ->options([
                                'left' => 'Left',
                                'center' => 'Center',
                                'right' => 'Right',
                            ])
                            ->live(),

                        TextInput::make('titleColor')
                            ->label('Title Color')
                            ->live(),

                        TextInput::make('descriptionColor')
                            ->label('Description Color')
                            ->live(),

                        TextInput::make('buttonColor')
                            ->label('Button Color')
                            ->live(),

                        TextInput::make('buttonTextColor')
                            ->label('Button Text Color')
                            ->live(),

                        TextInput::make('titleFontSize')
                            ->label('Title Font Size')
                            ->live(),

                        TextInput::make('descriptionFontSize')
                            ->label('Description Font Size')
                            ->live(),

                        TextInput::make('buttonFontSize')
                            ->label('Button Font Size')
                            ->live(),

                        TextInput::make('titleFontFamily')
                            ->label('Title Font Family')
                            ->live(),

                        TextInput::make('descriptionFontFamily')
                            ->label('Description Font Family')
                            ->live(),

                        TextInput::make('imageBackgroundColor')
                            ->label('Image Background Color')
                            ->live(),

                        TextInput::make('imageBackgroundOpacity')
                            ->label('Image Background Opacity')
                            ->live(),
                        
                        Select::make('showButton')
                            ->label('Show Button')
                            ->options([
                                '1' => 'Yes',
                                '0' => 'No',
                            ])
                            ->default('1')
                            ->live(),
                    ])
                    ->minItems(1)
                    ->live()
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
