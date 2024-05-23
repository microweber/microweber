<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ModuleResource\Pages\ListModules;
use App\Filament\Admin\Resources\ModuleResource\Pages\ViewModule;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\Module\Models\Module;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;

    protected static ?string $navigationIcon = 'mw-modules';

    protected static ?string $label = 'Modules';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordClasses([
                'shadow',
            ])
            ->columns([
//                Tables\Columns\Layout\View::make('filament-panels::table.columns.layout.stack')
//                    ->components([
//
//                ]),
                Tables\Columns\Layout\Stack::make([
                    ImageUrlColumn::make('icon')
                        ->imageUrl(function (Module $module) {
                            return $module->icon();
                        })
//                        ->action(function (Module $module) {
//                            return redirect($module->adminUrl());
//                        })
                        ->grow(false),

                    Tables\Columns\TextColumn::make('name')
                        ->searchable()
                        ->weight(FontWeight::Bold)
//                        ->action(function (Module $module) {
//                            return redirect($module->adminUrl());
//                        })
                        ->grow(false),
                ])
                    ->space(3)
                    ->alignment(Alignment::Center),

            ])
            ->contentGrid([
                'md' => 4,
                'xl' => 4,
            ])
            ->paginated(false)
            ->filters([

                Tables\Filters\SelectFilter::make('installed')
                    ->label('Status')
                    ->options([
                        '1' => 'Installed',
                        '0' => 'Not Installed',
                    ])
                    ->label('Installed')
                    ->placeholder('All')
                    ->default('1'),

                Tables\Filters\Filter::make('type')
                    ->form([
                        Forms\Components\Select::make('type')
                            ->options([
                            'live_edit' => 'Live Edit Modules',
                            'admin' => 'Admin Modules',
                            'all' => 'All Modules',
                            'elements' => 'Elements',
                        ])->default('admin'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (isset($data['type'])) {
                            if ($data['type'] == 'live_edit') {
                                $query->where('ui', 1);
                            }
                            if ($data['type'] == 'admin') {
                                $query->where('ui_admin', 1);
                            }
                            if ($data['type'] == 'elements') {
                                $query->where('as_element', 1);
                            }
                        }
                        return $query;
                    }),

            ],layout: Tables\Enums\FiltersLayout::Modal)
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->slideOver()
            )
            ->actions([

            ])
            ->bulkActions([

            ]);
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Module $record */

        return [
            'Module' => $record->name,
        ];
    }

    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('view')
                ->url(static::getUrl('view', ['record' => $record])),
        ];
    }

    /** @return Builder<Module> */
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListModules::route('/'),
            'view' => ViewModule::route('/{record}'),
        ];
    }
}
