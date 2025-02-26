<?php

namespace Modules\Multilanguage\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use MicroweberPackages\Multilanguage\Models\MultilanguageSupportedLocales;
use Modules\Multilanguage\Models\Language;

class LanguagesTable extends Component implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(MultilanguageSupportedLocales::query())
            ->columns([
                TextColumn::make('locale')
                    ->label('Locale')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('language')
                    ->label('Language')
                    ->searchable()
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),
            ])
            ->filters([
                // You can add filters here if needed
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        TextInput::make('locale')
                            ->required()
                            ->maxLength(10),
                        TextInput::make('language')
                            ->required()
                            ->maxLength(50),
                        Toggle::make('is_active')
                            ->label('Active'),
                    ])
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Language updated')
                            ->body('The language has been updated successfully.')
                    ),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Language deleted')
                            ->body('The language has been deleted successfully.')
                    ),
            ])
            ->bulkActions([
                // You can add bulk actions here if needed
            ])
            ->headerActions([
                Action::make('create')
                    ->label('Add Language')
                    ->form([
                        TextInput::make('locale')
                            ->required()
                            ->maxLength(10),
                        TextInput::make('language')
                            ->required()
                            ->maxLength(50),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->action(function (array $data): void {
                        MultilanguageSupportedLocales::create($data);

                        Notification::make()
                            ->success()
                            ->title('Language created')
                            ->body('The language has been created successfully.')
                            ->send();
                    }),
            ]);
    }

    public function render(): View
    {
        return view('modules.multilanguage::livewire.languages-table');
    }

}
