<?php

namespace Modules\Slider\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use Modules\Slider\Models\Slider;
use MicroweberPackages\LiveEdit\Filament\Admin\Tables\LiveEditModuleTable;

class SliderTableList extends LiveEditModuleTable implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public string|null $rel_id = null;
    public string|null $rel_type = null;
    public string|null $module_id = null;

    public function editFormArray()
    {
        return [
            Hidden::make('rel_id')
                ->default($this->rel_id),
            Hidden::make('rel_type')
                ->default($this->rel_type),
            MwFileUpload::make('media')
                ->label('Image')
                ->helperText('Upload image for this slide.'),
            TextInput::make('name')
                ->label('Slide Title')
                ->helperText('Enter a title for this slide.'),
            Textarea::make('description')
                ->label('Slide Description')
                ->helperText('Provide a description for this slide.'),
            Select::make('settings.alignItems')
                ->label('Align Items')
                ->options([
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right',
                ])
                ->default('center'),
            Toggle::make('settings.showButton')
                ->label('Show button'),
            TextInput::make('button_text')
                ->label('Button Text')
                ->helperText('Enter text for the button')
                ->visible(fn ($get) => $get('settings.showButton')),
            TextInput::make('link')
                ->label('Button URL')
                ->url()
                ->helperText('Enter a URL for the button')
                ->visible(fn ($get) => $get('settings.showButton')),
            ColorPicker::make('settings.buttonBackgroundColor')
                ->label('Button Background Color')
                ->visible(fn ($get) => $get('settings.showButton')),
            ColorPicker::make('settings.buttonBackgroundHoverColor')
                ->label('Button Background Hover Color')
                ->visible(fn ($get) => $get('settings.showButton')),
            ColorPicker::make('settings.buttonBorderColor')
                ->label('Button Border Color')
                ->visible(fn ($get) => $get('settings.showButton')),
            ColorPicker::make('settings.buttonTextColor')
                ->label('Button Text Color')
                ->visible(fn ($get) => $get('settings.showButton')),
            ColorPicker::make('settings.buttonTextHoverColor')
                ->label('Button Text Hover Color')
                ->visible(fn ($get) => $get('settings.showButton')),
            TextInput::make('settings.buttonFontSize')
                ->label('Button Font Size')
                ->suffix('px')
                ->numeric()
                ->minValue(8)
                ->maxValue(64)
                ->default(16)
                ->visible(fn ($get) => $get('settings.showButton')),
            ColorPicker::make('settings.titleColor')
                ->label('Title Color'),
            TextInput::make('settings.titleFontSize')
                ->label('Title Font Size')
                ->suffix('px')
                ->numeric()
                ->minValue(8)
                ->maxValue(64)
                ->default(24),
            ColorPicker::make('settings.descriptionColor')
                ->label('Description Color'),
            TextInput::make('settings.descriptionFontSize')
                ->label('Description Font Size')
                ->suffix('px')
                ->numeric()
                ->minValue(8)
                ->maxValue(64)
                ->default(16),
            ColorPicker::make('settings.imageBackgroundColor')
                ->label('Image Background Color'),
            TextInput::make('settings.imageBackgroundOpacity')
                ->label('Image Background Opacity')
                ->numeric()
                ->minValue(0)
                ->maxValue(1)
                ->step(0.1)
                ->default(1),
            Select::make('settings.imageBackgroundFilter')
                ->label('Image Background Filter')
                ->options([
                    'none' => 'None',
                    'blur' => 'Blur',
                    'mediumBlur' => 'Medium Blur',
                    'maxBlur' => 'Max Blur',
                    'grayscale' => 'Grayscale',
                    'hue-rotate' => 'Hue Rotate',
                    'invert' => 'Invert',
                    'sepia' => 'Sepia',
                ])
                ->default('none'),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Slider::query()->where('rel_id', $this->rel_id)->where('rel_type', $this->rel_type))
            ->defaultSort('position', 'asc')
            ->columns([
                ImageColumn::make('media')
                    ->label('Media'),
                TextColumn::make('name')
                    ->label('Name'),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50),
            ])
            ->filters([
                // ...
            ])
            ->headerActions([
                CreateAction::make()
                    ->slideOver()
                    ->form($this->editFormArray())
            ])
            ->actions([
                EditAction::make()
                    ->slideOver()
                    ->form($this->editFormArray()),
                DeleteAction::make()
            ])
            ->reorderable('position')
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('modules.slider::slider-table-list');
    }
}
