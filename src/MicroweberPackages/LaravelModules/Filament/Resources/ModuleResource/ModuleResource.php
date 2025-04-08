<?php

namespace MicroweberPackages\LaravelModules\Filament\Resources\ModuleResource;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Filament\Tables\Columns\ClickableColumn;
use MicroweberPackages\Filament\Tables\Columns\SVGColumn;
use MicroweberPackages\LaravelModules\Filament\Resources\ModuleResource\Pages\ListModules;
use MicroweberPackages\LaravelModules\Models\SystemModulesSushi;

class ModuleResource extends Resource
{
    protected static ?string $model = SystemModulesSushi::class;

    protected static ?string $navigationIcon = 'mw-modules';

    protected static ?string $navigationGroup = 'Other';

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

                ClickableColumn::make([
                    SVGColumn::make('icon')
                        ->state(function (SystemModulesSushi $module) {
                            return $module->getIconInline();
                        })
                        ->grow(false),

                    Tables\Columns\TextColumn::make('name')
                        ->searchable()
                        ->weight(FontWeight::Bold)
//                        ->action(function (SystemModulesSushi $module) {
//                            return redirect($module->adminUrl());
//                        })
                        ->grow(false),
                ])->url(function (SystemModulesSushi $module) {
                    return $module->adminUrl();
                }),


            ])
            ->contentGrid([
                'md' => 4,
                'xl' => 4,
            ])
            ->paginated(false)
            ->filters([

//                Tables\Filters\SelectFilter::make('installed')
//                    ->label('Status')
//                    ->options([
//                        '1' => 'Installed',
//                        '0' => 'Not Installed',
//                    ])
//                    ->label('Installed')
//                    ->placeholder('All')
//                    ->default('1'),

                Tables\Filters\Filter::make('type')
                    ->form([
                        Forms\Components\Select::make('type')
                            ->options([
                                'all' => 'All Modules',

                            ])->default('1'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (isset($data['type'])) {

                            if ($data['type'] == 'all') {
                                //$query->where('type', 1);
                            }
                        }
                        return $query;
                    }),

            ], layout: Tables\Enums\FiltersLayout::Modal)
            ->filtersTriggerAction(
                fn(Tables\Actions\Action $action) => $action
                    ->slideOver()
            )
            ->actions([

            ])
            ->bulkActions([

            ]);
    }

//    public static function getGloballySearchableAttributes(): array
//    {
//        return ['name'];
//    }
//
//    public static function getGlobalSearchResultDetails(Model $record): array
//    {
//        /** @var SystemModulesSushi $record */
//
//        return [
//            'Module' => $record->name,
//        ];
//    }
//
//    public static function getGlobalSearchResultActions(Model $record): array
//    {
//        return [
//            Action::make('view')
//                ->url(static::getUrl('view', ['record' => $record])),
//        ];
//    }
//
//    /** @return Builder<SystemModulesSushi> */
//    public static function getGlobalSearchEloquentQuery(): Builder
//    {
//        return parent::getGlobalSearchEloquentQuery();
//    }

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
        ];
    }
}
