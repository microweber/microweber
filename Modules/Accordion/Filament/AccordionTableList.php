<?php

namespace Modules\Accordion\Filament;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Livewire\Component;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwIconPicker;
use Modules\Accordion\Models\Accordion;


class AccordionTableList extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $moduleId = null;

    public function editFormArray()
    {
        return [
            Hidden::make('module_id')
                ->default($this->moduleId),
            TextInput::make('title')
                ->label('Title')
                ->required(),
            MwIconPicker::make('icon')
                ->label('Icon'),
            Textarea::make('content')
                ->label('Content')
                ->required(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Accordion::query()->where('module_id', $this->moduleId))
            ->defaultSort('position', 'asc')
            ->columns([
                TextColumn::make('title')
                    ->label('Title'),
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
//                BulkActionGroup::make([
//                    DeleteBulkAction::make()
//                ])
            ]);
    }


    public function render()
    {
        return view('modules.accordion::accordion-table-list');
    }

}
