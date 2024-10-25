<?php

namespace Modules\Teamcard\Filament;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
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
use Modules\Teamcard\Models\TeamcardItem;


class TeamcardTableList extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function editFormArray()
    {
        return [
            TextInput::make('name')
                ->label('Team Member Name')
                ->required()
                ->helperText('Enter the full name of the team member.'),
            MwFileUpload::make('file')
                ->label('Team Member Picture')
                ->required()
                ->helperText('Upload a picture of the team member.'),
            Textarea::make('bio')
                ->label('Team Member Bio')
                ->required()
                ->helperText('Provide a short biography of the team member.'),
            TextInput::make('role')
                ->label('Team Member Role')
                ->required()
                ->helperText('Specify the role of the team member in the team.'),
            TextInput::make('website')
                ->label('Team Member Website')
                ->required()
                ->helperText('Enter the personal or professional website of the team member.'),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(TeamcardItem::query())
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
                // ...
            ]);
    }


    public function render()
    {
        return view('modules.teamcard::teamcard-table-list');
    }

}
