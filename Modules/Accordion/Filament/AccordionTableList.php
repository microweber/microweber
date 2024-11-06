<?php

namespace Modules\Accordion\Filament;

use Filament\Actions\Concerns\InteractsWithActions;
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
    use InteractsWithActions;

    public string|null $rel_id = null;
    public string|null $rel_type = null;

    public function editFormArray()
    {
        return [

            TextInput::make('title')
                ->label('Title')
                ->required(),
            MwIconPicker::make('icon')
                ->label('Icon'),
            Textarea::make('content')
                ->label('Content')
                ->required(),

            Hidden::make('rel_id')
                ->default($this->rel_id),
            Hidden::make('rel_type')
                ->default($this->rel_type),
        ];
    }

    public function table(Table $table): Table
    {

        return $table
            ->query(Accordion::query()->where('rel_id', $this->rel_id)->where('rel_type', $this->rel_type))
            ->defaultSort('position', 'asc')
            ->columns([
                TextColumn::make('title')
                    ->label('Title'),
            ])
            ->filters([
                // ...
            ])
            ->headerActions([
                CreateAction::make('create')
                    ->slideOver()
                    ->form($this->editFormArray())
            ])
            ->actions([
                EditAction::make('edit')
                    ->slideOver()
                    ->form($this->editFormArray()),
                DeleteAction::make('delete')
            ])
            ->reorderable('position')
            ->bulkActions([
//                BulkActionGroup::make([
//                    DeleteBulkAction::make()
//                ])
            ]);
    }

//    public function create()
//    {
//        return CreateAction::make()
//            ->slideOver()
//            ->form($this->editFormArray());
//    }

    public function render()
    {
        return view('modules.accordion::accordion-table-list');
    }

}
