<?php

namespace Modules\Teamcard\Filament;

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
use Modules\Teamcard\Models\Teamcard;
use Modules\Teamcard\Models\TeamcardItem;


class TeamcardTableList extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $moduleId = null;

    public function editFormArray()
    {
        return [
            Hidden::make('module_id')
                ->default($this->moduleId),
            TextInput::make('name')
                ->label('Team Member Name')
                ->helperText('Enter the full name of the team member.'),
            MwFileUpload::make('file')
//                ->live()
//                ->afterStateUpdated(function ($state) {
////                    dump($state);
//                })
                ->label('Team Member Picture')
                ->helperText('Upload a picture of the team member.'),
//            FileUpload::make('file')
//                    ->label('Team Member Picture')
//                    ->helperText('Upload a picture of the team member.'),
            Textarea::make('bio')
                ->label('Team Member Bio')
                ->helperText('Provide a short biography of the team member.'),
            TextInput::make('role')
                ->label('Team Member Role')
                ->helperText('Specify the role of the team member in the team.'),
            TextInput::make('website')
                ->label('Team Member Website')
                ->helperText('Enter the personal or professional website of the team member.'),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Teamcard::query()->where('module_id', $this->moduleId))
            ->columns([
                ImageColumn::make('file')
                    ->label('Picture')
                    ->circular(),
                TextColumn::make('name')
                    ->label('Name'),
            ])
            ->filters([
                // ...
            ])
            ->headerActions([
                CreateAction::make()
                    ->form($this->editFormArray())
            ])
            ->actions([
                EditAction::make()
                    ->form($this->editFormArray()),
                DeleteAction::make()
            ])
            ->reorderable()
            ->bulkActions([
//                BulkActionGroup::make([
//                    DeleteBulkAction::make()
//                ])
            ]);
    }


    public function render()
    {
        return view('modules.teamcard::teamcard-table-list');
    }

}
