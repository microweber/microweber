<?php

namespace MicroweberPackages\Modules\Tabs\Http\Livewire;

use App\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\Modules\Tabs\Models\Tab;
use MicroweberPackages\Product\Models\Product;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;

class TabsModuleSettings extends LiveEditModuleSettings implements HasTable
{
    use InteractsWithTable;

    public string $module = 'tabs';

    protected static string $view = 'microweber-module-tabs::livewire.tabs-module-settings';

    public function table(Table $table): Table
    {
        return $table
            ->query(Tab::queryForOptionGroup($this->getOptionGroup()))
            ->columns([
//                TextColumn::make('position')
//                    ->searchable(),
//                TextColumn::make('id')
//                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
//                TextColumn::make('icon')
//                    ->searchable(),

            ])
//            ->contentGrid([
//                'md' => 1,
//                'lg' => 1,
//                'xl' => 1,
//            ])
            ->paginationPageOptions([
                1,
                3,
                5
            ])
            ->filters([
                // ...
            ])
            ->reorderRecordsTriggerAction(function () {
                $tableRecords = $this->getTableRecords();
                if ($tableRecords) {
                    foreach ($tableRecords->toArray() as $tableRecord) {
                        if (isset($tableRecord['id'])) {
                            $findTab = Tab::where('id', $tableRecord['id'])->first();
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
            ->headerActions([
                CreateAction::make()
                    ->slideOver()
                    ->form([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->after(function () {
                        $this->dispatch('mw-option-saved',
                            optionGroup: $this->optionGroup
                        );
                    }),

            ])
            ->actions([
                EditAction::make()
                    ->slideOver()
                    ->form([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                    ])->after(function () {
                        $this->dispatch('mw-option-saved',
                            optionGroup: $this->optionGroup
                        );
                    }),
                DeleteAction::make('Delete')
                    ->slideOver()
                    ->after(function () {
                        $this->dispatch('mw-option-saved',
                            optionGroup: $this->optionGroup
                        );
                    }),
            ])
            ->bulkActions([

            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make($this->getOptionFieldName('title'))
                    ->label('Title')
                    ->live(),

            ]);
    }

}
