<?php

namespace Modules\CustomFields\Filament\Admin;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use Modules\CustomFields\Enums\CustomFieldTypes;
use Modules\CustomFields\Models\CustomField;

class ListCustomFields extends AdminComponent implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $relType = '';
    public $relId = '';
    public $type = '';
    public $createdBy = '';
    public $sessionId = '';

    public function table(Table $table): Table
    {

        $modelQuery = CustomField::where('rel_type', $this->relType)
            ->where('rel_id', $this->relId);

        if (isset($this->sessionId) && $this->sessionId) {
            $modelQuery = $modelQuery->where('session_id', $this->sessionId);
        }
        if (isset($this->createdBy) && $this->createdBy) {
            $modelQuery = $modelQuery->where('created_by', $this->createdBy);
        }


        $modelQuery = $modelQuery->orderBy('position', 'asc');


        $editForm = [];
        $editForm[] = TextInput::make('name')
            ->label('Name')
            ->placeholder('Name')
            ->required();
        $editForm[] = Hidden::make('rel_type')
            ->default($this->relType);
        $editForm[] = Hidden::make('rel_id')
            ->default($this->relId);

        if (isset($this->sessionId) && $this->sessionId) {
            $editForm[] = TextInput::make('session_id')
                ->default($this->sessionId)->hidden();
        }
        if (isset($this->createdBy) && $this->createdBy) {
            $editForm[] = TextInput::make('created_by')
                ->default($this->createdBy)->hidden();
        }


        $editForm[] = Group::make()
            ->hidden(function (Get $get) {
                $hide = false;
                if ($get('type') == 'radio'
                    || $get('type') == 'dropdown'
                    || $get('type') == 'checkbox') {
                    $hide = true;
                }
                return $hide;
            })
            ->schema([
                Group::make()
                    ->relationship('fieldValueSingle')
                    ->schema([
                        TextInput::make('value'),
                    ])->hidden(function (Get $get) {
                        if ($get('options.as_textarea')) {
                            return true;
                        }
                        return false;
                    }),
                Group::make()
                    ->relationship('fieldValueSingle')
                    ->schema([
                        Textarea::make('value'),
                    ])->hidden(function (Get $get) {
                        if ($get('options.as_textarea')) {
                            return false;
                        }
                        return true;
                    }),
                Toggle::make('options.as_textarea')
                    ->live()
                    ->label('Use as textarea')
                    ->columnSpanFull()
                    ->default(false)
            ]);


        $editForm[] = Repeater::make('fieldValue')
            ->relationship('fieldValue')
            ->reorderable()
            ->cloneable()
            ->collapsible()
            ->addable()
            ->schema([
                TextInput::make('value')
                    ->required(),
            ])
            ->hidden(function (Get $get) {
                $hide = true;
                if ($get('type') == 'radio'
                    || $get('type') == 'dropdown'
                    || $get('type') == 'checkbox') {
                    $hide = false;
                }
                return $hide;
            })
            ->columns(1);


        $editForm[] = Section::make('Advanced')
            ->collapsible()
            ->collapsed()
            ->compact()
            ->schema([
                Toggle::make('options.show_placeholder')
                    ->helperText('Toggle to turn on the placeholder and write your text below')
                    ->label('Show placeholder')
                    ->reactive()
                    ->columnSpanFull(),

                TextInput::make('options.placeholder')
                    ->label('Placeholder')
                    ->reactive()
                    ->visible(fn (Get $get) => $get('options.show_placeholder'))
                    ->placeholder('Placeholder text')
                    ,

                Toggle::make('options.required')
                    ->helperText('Toggle to make this field required for the user')
                    ->label('Required'),
                Toggle::make('options.show_label')
                    ->helperText('Toggle to turn on the label and write your text below')
                    ->label('Show label'),
                Section::make([
                    Grid::make(3)
                        ->schema([
                            Select::make('options.field_size_desktop')
                                ->label('Grid Desktop')
                                ->options([
                                    '1' => 'col-1',
                                    '2' => 'col-2',
                                    '3' => 'col-3',
                                    '4' => 'col-4',
                                    '5' => 'col-5',
                                    '6' => 'col-6',
                                    '7' => 'col-7',
                                    '8' => 'col-8',
                                    '9' => 'col-9',
                                    '10' => 'col-10',
                                    '11' => 'col-11',
                                    '12' => 'col-12',
                                ]),
                            Select::make('options.field_size_tablet')
                                ->label('Grid Tablet')
                                ->options([
                                    '1' => 'col-1',
                                    '2' => 'col-2',
                                    '3' => 'col-3',
                                    '4' => 'col-4',
                                    '5' => 'col-5',
                                    '6' => 'col-6',
                                    '7' => 'col-7',
                                    '8' => 'col-8',
                                    '9' => 'col-9',
                                    '10' => 'col-10',
                                    '11' => 'col-11',
                                    '12' => 'col-12',
                                ]),
                            Select::make('options.field_size_mobile')
                                ->label('Grid Mobile')
                                ->options([
                                    '1' => 'col-1',
                                    '2' => 'col-2',
                                    '3' => 'col-3',
                                    '4' => 'col-4',
                                    '5' => 'col-5',
                                    '6' => 'col-6',
                                    '7' => 'col-7',
                                    '8' => 'col-8',
                                    '9' => 'col-9',
                                    '10' => 'col-10',
                                    '11' => 'col-11',
                                    '12' => 'col-12',
                                ])
                        ])

                ]),
            ]);


        return $table
            ->paginated(false)
            ->heading('Custom Fields')
            ->reorderable('position')
            ->headerActions([
                CreateAction::make('custom-field-create-action')
                    // ->teleport('body')
                    ->label('Add custom field')
                    ->form([
                        Wizard::make([
                                Wizard\Step::make('Type')
                                    ->schema([
                                        RadioDeck::make('type')
                                            ->afterStateUpdated(function (Get $get, Set $set) {

                                                $set('type', $get('type'));
                                                $statePath = 'mountedTableActionsData.0';
                                                $this->dispatchFormEvent('wizard::nextStep', statePath: $statePath, currentStepIndex: 0);

                                            })
                                            ->label('Custom field type')
                                            ->options(CustomFieldTypes::class)
                                            //  ->descriptions(CustomFieldTypes::class)
                                            ->icons(CustomFieldTypes::class)
                                            ->required()
                                            ->live()
                                            ->color('primary')
                                            ->columns(3),
                                    ]),
                                Wizard\Step::make('Settings')
                                    ->schema($editForm),
                            ]
                        )
//                            ->startOnStep(function (Get $get) {
//
//                            $step = 1;
//                            if ($get('type') !== null) {
//                                $step = 2;
//                            }
//
//                         return $step;
//                        })

//                            ->afterStateUpdated(function (Get $get, Set $set) {
//
//                               // $set('type', $get('type'));
//                              //  $statePath = 'mountedTableActionsData.0.custom-fields-create';
//                              //  $this->dispatchFormEvent('wizard::nextStep' ,statePath: $statePath,currentStepIndex:0);
//
//                            })
                    ])->createAnother(false),
            ])
            ->query($modelQuery)
            ->columns([
                TextColumn::make('name')
                    ->label('Name'),
                IconColumn::make('type')
                    ->icon(function (CustomField $customField) {
                        $icon = CustomFieldTypes::from($customField->type);
                        return $icon->getIcons();
                    }),
                TextColumn::make('value')
                    ->state(function (CustomField $customField) {
                        if ($customField->type == 'radio'
                            || $customField->type == 'dropdown'
                            || $customField->type == 'checkbox') {
                            if ($customField->fieldValue) {
                                if (!empty($customField->fieldValue)) {
                                    $values = [];
                                    foreach ($customField->fieldValue as $value) {
                                        $values[] = $value->value;
                                    }
                                    return implode(', ', $values);
                                }
                            }
                        } else if ($customField->fieldValueSingle) {
                            return $customField->fieldValueSingle->value;
                        }
                    })->label('Value')
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
            ])
            ->bulkActions([
                DeleteBulkAction::make('delete-custom-fields')
                    ->icon('heroicon-o-trash')
                    ->label('Delete')
                    ->requiresConfirmation(),
            ]);
    }

    public function render(): View
    {
        return view('modules.custom_fields::filament.admin.list-custom-fields');
    }
}
