<?php

namespace MicroweberPackages\LiveEdit\Http\Livewire\ItemsEditor;


use App\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use MicroweberPackages\LiveEdit\Models\ModuleItemSushi;
use MicroweberPackages\Modules\Tabs\Models\Tab;

class ModuleSettingsItemsEditorComponent extends LiveEditModuleSettings implements HasTable
{

    use InteractsWithTable;

    public string $module = '';
    public array $editorSettings = [];

    protected static string $view = 'microweber-live-edit::module-items-editor';


    public function getEditorSettings() : array
    {
        return [];
    }
    public function table(Table $table): Table
    {
        $builtTable = $table->query(ModuleItemSushi::queryForOptionGroup($this->getOptionGroup()));

        $editorSettings = $this->getEditorSettings();

        $formFields = [];
        if (isset($editorSettings['schema'])) {
            foreach ($editorSettings['schema'] as $schema) {
                if ($schema['type'] == 'text') {
                    $formFields[] = TextInput::make($schema['name'])
                        ->label($schema['label'])
                        ->placeholder($schema['placeholder'])
                        ->maxLength(255);

                }if ($schema['type'] == 'textarea') {
                    $formFields[] = Textarea::make($schema['name'])
                        ->label($schema['label'])
                        ->placeholder($schema['placeholder'])
                        ->maxLength(255);
                }
                if ($schema['type'] == 'image') {
                    $formFields[] = FileUpload::make($schema['name'])
                        ->label($schema['label'])
                        ->placeholder($schema['placeholder']);
                }
                if ($schema['type'] == 'color') {
                    $formFields[] = ColorPicker::make($schema['name'])
                        ->label($schema['label'])
                        ->placeholder($schema['placeholder']);
                }
                if ($schema['type'] == 'select') {
                    $formFields[] = Select::make($schema['name'])
                        ->label($schema['label'])
                         ->options($schema['options'])
                        ->placeholder($schema['placeholder']);
                }
                if ($schema['type'] == 'toggle') {
                    $formFields[] = Toggle::make($schema['name'])
                        ->label($schema['label']);
                }
            //    dump($schema);
            }
        }
        if (isset($editorSettings['config']['listColumns'])) {
            foreach ($editorSettings['config']['listColumns'] as $key=>$value) {
                $builtTable->columns([
                    TextColumn::make($key)
                        ->label($value)
                        ->searchable(),
                ]);
            }
        }

        $headerActions = [];
        if (isset($editorSettings['config']['addButtonText'])) {
            $headerActions[] = CreateAction::make()
                ->label($editorSettings['config']['addButtonText'])
                ->modalHeading($editorSettings['config']['addButtonText'])
                ->slideOver()
                ->form($formFields)
                ->createAnother(false)
                ->after(function () {
                    $this->dispatch('mw-option-saved',
                        optionGroup: $this->optionGroup
                    );
                });
        }
        $actions = [];
        if (isset($editorSettings['config']['editButtonText'])) {
            $actions[] = EditAction::make($editorSettings['config']['editButtonText'])
                    ->slideOver()
                    ->hiddenLabel(true)
                    ->modalHeading($editorSettings['config']['editButtonText'])
                    ->form($formFields)->after(function () {
                        $this->dispatch('mw-option-saved',
                            optionGroup: $this->optionGroup
                        );
                    });
        }
        if (isset($editorSettings['config']['deleteButtonText'])) {
            $actions[] = DeleteAction::make($editorSettings['config']['deleteButtonText'])
                ->slideOver()
                ->modalHeading($editorSettings['config']['deleteButtonText'])
                ->hiddenLabel(true)
                ->after(function () {
                    $this->dispatch('mw-option-saved',
                        optionGroup: $this->optionGroup
                    );
                });
        }

        //            $builtTable->contentGrid([
//                'md' => 1,
//                'lg' => 1,
//                'xl' => 1,
//            ])

        $builtTable->reorderRecordsTriggerAction(function () {
                $tableRecords = $this->getTableRecords();
                if ($tableRecords) {
                    foreach ($tableRecords->toArray() as $tableRecord) {
                        if (isset($tableRecord['id'])) {
                            $findTab = ModuleItemSushi::where('id', $tableRecord['id'])->first();
                            if ($findTab) {
                                $findTab->position = $tableRecord['position'];
                                $findTab->save();
                            }
                        }
                    }
                }
            })
            ->defaultSort('position')
            ->reorderable('position')
            ->headerActions($headerActions)
            ->actions($actions);


        return $builtTable;
    }

}
