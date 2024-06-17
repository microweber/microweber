<?php

namespace MicroweberPackages\CustomField\Http\Livewire\Filament\Admin;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Livewire\Component;
use MicroweberPackages\App\Models\SystemLicenses;
use MicroweberPackages\CustomField\Enums\CustomFieldTypes;
use MicroweberPackages\CustomField\Fields\Text;
use MicroweberPackages\CustomField\Models\CustomField;

class ListCustomFields extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $relType = '';
    public $relId = '';

    public function table(Table $table): Table
    {
        $modelQuery = CustomField::queryForRelTypeRelId($this->relType, $this->relId);


        $editForm = [
            TextInput::make('name')
                ->label('Name')
                ->placeholder('Name')
                ->required(),
            Toggle::make('as_textarea')
                ->label('Use as textarea')
                ->columnSpanFull()
                ->default(false),
            TextInput::make('value')
                ->label('Value')
                ->placeholder('Value'),

            Toggle::make('show_placeholder')
                ->helperText('Toggle to turn on the placeholder and write your text below')
                ->label('Show placeholder')
                ->columnSpanFull(),
            Toggle::make('required')
                ->helperText('Toggle to make this field required for the user')
                ->label('Required'),
            Toggle::make('show_label')
                ->helperText('Toggle to turn on the label and write your text below')
                ->label('Show label'),
            Section::make([
                Grid::make(3)
                    ->schema([
                        Select::make('field_size_desktop')
                            ->label('Grid Desktop')
                            ->options([
                                'col-1' => 'col-1',
                                'col-2' => 'col-2',
                                'col-3' => 'col-3',
                                'col-4' => 'col-4',
                                'col-5' => 'col-5',
                                'col-6' => 'col-6',
                                'col-7' => 'col-7',
                                'col-8' => 'col-8',
                                'col-9' => 'col-9',
                                'col-10' => 'col-10',
                                'col-11' => 'col-11',
                                'col-12' => 'col-12',
                            ]),
                        Select::make('field_size_tablet')
                            ->label('Grid Tablet')
                            ->options([
                                'col-1' => 'col-1',
                                'col-2' => 'col-2',
                                'col-3' => 'col-3',
                                'col-4' => 'col-4',
                                'col-5' => 'col-5',
                                'col-6' => 'col-6',
                                'col-7' => 'col-7',
                                'col-8' => 'col-8',
                                'col-9' => 'col-9',
                                'col-10' => 'col-10',
                                'col-11' => 'col-11',
                                'col-12' => 'col-12',
                            ]),
                        Select::make('field_size_mobile')
                            ->label('Grid Mobile')
                            ->options([
                                'col-1' => 'col-1',
                                'col-2' => 'col-2',
                                'col-3' => 'col-3',
                                'col-4' => 'col-4',
                                'col-5' => 'col-5',
                                'col-6' => 'col-6',
                                'col-7' => 'col-7',
                                'col-8' => 'col-8',
                                'col-9' => 'col-9',
                                'col-10' => 'col-10',
                                'col-11' => 'col-11',
                                'col-12' => 'col-12',
                            ])
                    ])

                ]),
        ];

        return $table
            ->paginated(false)
            ->heading('Custom Fields')
            ->headerActions([
                CreateAction::make('custom-field-create')
                    ->label('Add custom field')
                    ->form([
                        Wizard::make([
                            Wizard\Step::make('Type')
                                ->schema([
                                    RadioDeck::make('type')
                                        ->options(CustomFieldTypes::class)
                                        ->descriptions(CustomFieldTypes::class)
                                        ->icons(CustomFieldTypes::class)
                                       // ->required()
                                        ->columns(3),
                                ]),
                            Wizard\Step::make('Settings')
                                ->schema($editForm),
                        ])
                    ]),
            ])
            ->query($modelQuery)
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
                EditAction::make('custom-field-edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->form($editForm),

                DeleteAction::make('custom-field-delete')
                    ->label('Delete')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->action(fn (CustomField $record) => $record->delete())
            ])
            ->bulkActions([
                DeleteBulkAction::make()
            ]);
    }

    public function render(): View
    {
        return view('custom_field::livewire.filament.admin.list-custom-fields');
    }
}
