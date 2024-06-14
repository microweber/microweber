<?php

namespace MicroweberPackages\CustomField\Http\Livewire\Filament\Admin;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use MicroweberPackages\App\Models\SystemLicenses;
use MicroweberPackages\CustomField\Models\CustomField;

class ListCustomFields extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->heading('Custom Fields')
            ->headerActions([

                Action::make('custom-field-create')
                    ->label('Add custom field')
                    ->form([


                    ]),
            ])
            ->query(CustomField::query())
            ->columns([
                TextColumn::make('name')
                    ->label('Name'),
                TextColumn::make('type')
                    ->label('Type'),
                TextColumn::make('value')
                    ->label('Value')
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Action::make('custom-field-delete')
                    ->label('Delete')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->action(fn (CustomField $record) => $record->delete())
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render(): View
    {
        return view('custom_field::livewire.filament.admin.list-custom-fields');
    }
}
