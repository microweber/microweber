<?php

namespace Modules\Slider\Filament;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
            TextInput::make('name')
                ->label('Slide Name')
                ->helperText('Enter a name for this slide.'),
            Textarea::make('description')
                ->label('Slide Description')
                ->helperText('Provide a description for this slide.'),
            MwFileUpload::make('media')
                ->label('Slide Media')
                ->helperText('Upload media for this slide.'),
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
