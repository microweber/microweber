<?php

namespace MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Commands\Concerns\CanReadModelSchemas;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Option\Models\Option;

abstract class LiveEditModuleSettingsTable extends LiveEditModuleSettings
{

    public function getDataSourceFormSchema(): array
    {
        return [
            Select::make('options.rel_type')
                ->label('Data Source Type')
                ->helperText('Select the type of data source for the module')
                ->options(fn() => $this->getRelTypeOptions())
                ->live()
                ->reactive()
                ->searchable()
                ->afterStateUpdated(function ($state, callable $set) {
                    $this->dispatch('resetTableList', ['rel_type' => $state]);
                }),

            Select::make('options.rel_id')
                ->label('Data Source Identifier')
                ->helperText('Choose the specific identifier for the selected data source type')
                ->options(fn() => $this->getRelIdOptions())
                ->live()
                ->reactive()
                ->searchable()
                ->afterStateUpdated(function ($state, callable $set) {
                    $this->dispatch('resetTableList', ['rel_id' => $state]);
                }),
        ];
    }


    /**
     * Get options for the `rel_id` field dynamically.
     *
     * @return array
     */
    public function getRelIdOptions($rel_type = false): array
    {

        $vals = $this->modelName::query()->select('rel_id');
        if ($rel_type) {
            $vals->where('rel_type', $rel_type);
        }
        $vals = $vals->distinct()->pluck('rel_id')->toArray();
        return array_combine($vals, $vals);
    }

    public function getRelTypeOptions(): array
    {
        $vals = $this->modelName::query()->select('rel_type')->distinct()->pluck('rel_type')->toArray();
        return array_combine($vals, $vals);
    }
}
