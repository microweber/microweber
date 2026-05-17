<?php

namespace Modules\CustomFields\Filament\Admin;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Wizard;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Enums\Width as MaxWidth;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\Multilanguage\Forms\Actions\TranslateFieldAction;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use Modules\CustomFields\Enums\CustomFieldTypes;
use Modules\CustomFields\Models\CustomField;

class ListCustomFields extends AdminComponent implements HasForms, HasTable, HasActions
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions;

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

        $editForm[] = Hidden::make('multilanguage')
            ->visible(MultilanguageHelpers::multilanguageIsEnabled());

        $editForm[] = TextInput::make('name')
            ->label('Name')
            ->placeholder('Name')
            ->hintAction(
                TranslateFieldAction::make('name')->label('')
            )
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
                        if ($get('options.as_text_area')) {
                            return true;
                        }
                        return false;
                    }),
                Group::make()
                    ->relationship('fieldValueSingle')
                    ->schema([
                        Textarea::make('value'),
                    ])->hidden(function (Get $get) {
                        if ($get('options.as_text_area')) {
                            return false;
                        }
                        return true;
                    }),
                Toggle::make('options.as_text_area')
                    ->live()
                    ->label('Use as textarea')
                    ->columnSpanFull()
                    ->visible(fn (Get $get) => $get('type') == 'text' )
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
                    ->live()
                    ->columnSpanFull(),

                TextInput::make('options.placeholder')
                    ->label('Placeholder')
                    ->live()
                    ->visible(fn (Get $get) => $get('options.show_placeholder'))
                    ->placeholder('Placeholder text')
                    ,

                Toggle::make('options.required')
                    ->live()
                    ->helperText('Toggle to make this field required for the user')
                    ->label('Required'),
                Toggle::make('options.hide_label')
                    ->live()
                    ->helperText('Toggle to turn off the label and write your text below')
                    ->label('Hide label'),
                Section::make([
                    Grid::make(3)
                        ->schema([
                            Select::make('options.field_size_desktop')
                                ->label('Grid Desktop')
                                ->live()
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
                                ->live()
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
                                ->live()
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
                  //  ->teleport('body')
                    ->label('Add custom field')
                    ->slideOver()
                    ->modalWidth(MaxWidth::ExtraLarge)
                    ->form([
                        Wizard::make([
                                Wizard\Step::make('Type')
                                    ->schema([
                                        RadioDeck::make('type')
                                            ->afterStateUpdated(function (Get $get, Set $set) {
                                                $set('type', $get('type'));
                                                $this->js('setTimeout(() => { const w = document.querySelector(".fi-sc-wizard"); if (w && w._x_dataStack) { w._x_dataStack[0].goToNextStep(); } }, 100)');
                                            })
                                            ->label('Custom field type')
                                            ->options(CustomFieldTypes::class)
                                            // task-2026-05-17-6027b9 / AI-788 CHANGE — descriptions
                                            // call was previously commented out so the actionable
                                            // copy data shipped via CustomFieldTypes::getDescriptions()
                                            // (PHPUnit DataProvider enforces ≥10 chars +
                                            // non-tautological) never reached the RadioDeck UI.
                                            // Designer's runtime probe caught 0 description nodes
                                            // per option. Single-character fix per dispatch.
                                            ->descriptions(CustomFieldTypes::class)
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
                        ->skippable()
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



                    IconColumn::make('type')
                        ->action( EditAction::make('custom-field-edit'))
                        ->grow(false)
                        ->icon(function (CustomField $customField) {
                            $icon = CustomFieldTypes::from($customField->type);
                            return $icon->getIcons();
                        }),

                TextColumn::make('name')
                    ->action( EditAction::make('custom-field-edit'))
                    ->label('Name'),

                TextColumn::make('value')
                    ->action( EditAction::make('custom-field-edit'))
                    ->grow(false)
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
                    ->slideOver()
                    ->modalWidth(MaxWidth::ExtraLarge)

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
