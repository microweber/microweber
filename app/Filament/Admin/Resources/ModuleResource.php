<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ModuleResource\Pages\ListModules;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\Module\Models\Module;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
//            ->recordClasses(function (Module $module) {
//                return [
//                   ''
//                ];
//            })
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
                        ->action(function (Module $module) {
                            return redirect($module->adminUrl());
                        })
                        ->grow(false),

                    Tables\Columns\TextColumn::make('name')
                        ->searchable()
                        ->sortable()
                        ->weight(FontWeight::Bold)
                        ->action(function (Module $module) {
                            return redirect($module->adminUrl());
                        })
                        ->grow(false),
                ])
                    ->space(3)
                    ->alignment(Alignment::Center)
            ])
            ->contentGrid([
                'md' => 4,
                'xl' => 4,
            ])
            ->paginated(false)
            ->filters([
                //
            ])
            ->actions([

            ])
            ->bulkActions([

            ]);
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
        ];
    }
}
