<?php

namespace Modules\Rating\Filament;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Modules\Rating\Models\Rating;

class RatingTableList extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions;

    public string|null $rel_id = null;
    public string|null $rel_type = null;

    public function editFormArray()
    {
        return [
            TextInput::make('rating')
                ->label('Rating')
                ->numeric()
                ->required()
                ->minValue(1)
                ->maxValue(5),
            Textarea::make('comment')
                ->label('Comment'),
            Hidden::make('rel_id')
                ->default($this->rel_id),
            Hidden::make('rel_type')
                ->default($this->rel_type),
            Hidden::make('session_id')
                ->default(session()->getId()),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Rating::query()->where('rel_id', $this->rel_id)->where('rel_type', $this->rel_type))
            ->columns([
                TextColumn::make('rating')
                    ->label('Rating')
                    ->sortable(),
                TextColumn::make('comment')
                    ->label('Comment')
                    ->limit(50),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_by')
                    ->label('Created By')
                    ->sortable(),
            ])
            ->filters([
                // Add filters if needed
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
            ->defaultSort('created_at', 'desc');
    }

    public function render()
    {
        return view('modules.rating::filament.rating-table-list');
    }
}
