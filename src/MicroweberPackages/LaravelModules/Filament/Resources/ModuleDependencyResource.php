<?php

namespace MicroweberPackages\LaravelModules\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use MicroweberPackages\LaravelModules\Filament\Resources\ModuleDependencyResource\Pages;
use MicroweberPackages\LaravelModules\Models\ModuleDependency;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ModuleDependencyResource extends Resource
{
    protected static ?string $model = ModuleDependency::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static string | \UnitEnum | null $navigationGroup = 'Customization Settings';

    protected static ?string $label = 'Module Dependencies';

    protected static ?int $navigationSort = 121;

    protected static ?string $slug = 'module-dependencies';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make('Dependency Configuration')
                    ->description('Define module dependencies, conflicts, and suggestions')
                    ->schema([
                        Forms\Components\TextInput::make('module_name')
                            ->label('Module')
                            ->required()
                            ->placeholder('e.g., Cart')
                            ->helperText('The module that has this dependency'),

                        Forms\Components\TextInput::make('dependency_module_name')
                            ->label('Depends On')
                            ->required()
                            ->placeholder('e.g., Product')
                            ->helperText('The module being depended upon'),

                        Forms\Components\Select::make('dependency_type')
                            ->label('Type')
                            ->required()
                            ->options([
                                ModuleDependency::TYPE_REQUIRE => 'Require (Hard dependency)',
                                ModuleDependency::TYPE_CONFLICT => 'Conflict (Incompatible)',
                                ModuleDependency::TYPE_SUGGEST => 'Suggest (Optional)',
                                ModuleDependency::TYPE_REPLACE => 'Replace (Replaces module)',
                            ])
                            ->default(ModuleDependency::TYPE_REQUIRE)
                            ->helperText('The relationship type between modules'),

                        Forms\Components\TextInput::make('version_constraint')
                            ->label('Version Constraint')
                            ->placeholder('e.g., ^1.0, >=2.0, ~3.0')
                            ->helperText('Semver constraint (leave empty for any version)'),

                        Forms\Components\Toggle::make('is_optional')
                            ->label('Optional Dependency')
                            ->helperText('Soft dependencies are not required for module function')
                            ->default(false)
                            ->visible(fn (callable $get) => $get('dependency_type') === ModuleDependency::TYPE_REQUIRE),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Why this dependency is needed...')
                            ->rows(3)
                            ->visible(fn (callable $get) => $get('dependency_type') === ModuleDependency::TYPE_SUGGEST),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('module_name')
                    ->label('Module')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('dependency_module_name')
                    ->label('Depends On')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('version_constraint')
                    ->label('Version')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Any'),

                Tables\Columns\TextColumn::make('dependency_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state, ModuleDependency $record): string => match ($state) {
                        ModuleDependency::TYPE_REQUIRE => $record->is_optional ? 'Soft Require' : 'Required',
                        ModuleDependency::TYPE_CONFLICT => 'Conflict',
                        ModuleDependency::TYPE_SUGGEST => 'Suggested',
                        ModuleDependency::TYPE_REPLACE => 'Replaces',
                        default => $state,
                    })
                    ->color(fn (string $state, ModuleDependency $record): string => match ($state) {
                        ModuleDependency::TYPE_REQUIRE => $record->is_optional ? 'warning' : 'success',
                        ModuleDependency::TYPE_CONFLICT => 'danger',
                        ModuleDependency::TYPE_SUGGEST => 'info',
                        ModuleDependency::TYPE_REPLACE => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('is_optional')
                    ->label('Optional')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-minus-circle'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('module_name')
            ->filters([
                Tables\Filters\SelectFilter::make('module_name')
                    ->label('Module')
                    ->options(function () {
                        return ModuleDependency::distinct()
                            ->orderBy('module_name')
                            ->pluck('module_name', 'module_name')
                            ->toArray();
                    })
                    ->searchable(),

                Tables\Filters\SelectFilter::make('dependency_type')
                    ->label('Type')
                    ->options([
                        ModuleDependency::TYPE_REQUIRE => 'Required',
                        ModuleDependency::TYPE_CONFLICT => 'Conflict',
                        ModuleDependency::TYPE_SUGGEST => 'Suggested',
                        ModuleDependency::TYPE_REPLACE => 'Replaces',
                    ]),

                Tables\Filters\TernaryFilter::make('is_optional')
                    ->label('Optional')
                    ->trueLabel('Yes')
                    ->falseLabel('No')
                    ->placeholder('All'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListModuleDependencies::route('/'),
            'create' => Pages\CreateModuleDependency::route('/create'),
            'edit' => Pages\EditModuleDependency::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
