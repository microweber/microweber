<?php

namespace Modules\Settings\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\KeyValue;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use MicroweberPackages\Module\ModuleManager;
use Modules\Settings\Filament\Resources\ModuleConfigurationResource\Pages;

class ModuleConfigurationResource extends Resource
{
    protected static ?string $model = \MicroweberPackages\Module\Models\Module::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-puzzle-piece';
    protected static ?string $navigationLabel = 'Module Configuration';
    protected static string | \UnitEnum | null $navigationGroup = 'Customization Settings';
    protected static ?string $breadcrumb = 'Modules';
    protected static ?string $pluralLabel = 'Module Configurations';
    protected static ?string $slug = 'module-configuration';
    public static string $description = 'Manage module settings and configurations';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'module', 'type'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->module) {
            $details['Module'] = $record->module;
        }

        if ($record->type) {
            $details['Type'] = ucfirst($record->type);
        }

        return $details;
    }

    public function getDescription(): string
    {
        return static::$description;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Module Information')
                    ->icon('heroicon-m-cube')
                    ->description('Basic information about the module')
                    ->schema([
                        TextInput::make('name')
                            ->label('Module Name')
                            ->disabled()
                            ->placeholder('Module name'),

                        Textarea::make('description')
                            ->label('Description')
                            ->disabled()
                            ->placeholder('Module description')
                            ->rows(3),
                    ]),

                Section::make('Configuration')
                    ->icon('heroicon-m-cog-6-tooth')
                    ->description('Module-specific configuration settings')
                    ->schema([
                        Tabs::make('Settings')
                            ->tabs([
                                Tabs\Tab::make('General')
                                    ->schema([
                                        Toggle::make('is_enabled')
                                            ->label('Module Enabled')
                                            ->helperText('Enable or disable this module')
                                            ->live()
                                            ->default(true),

                                        TextInput::make('priority')
                                            ->label('Priority')
                                            ->numeric()
                                            ->helperText('Lower values are loaded first')
                                            ->default(0),
                                    ]),

                                Tabs\Tab::make('Settings')
                                    ->schema([
                                        KeyValue::make('settings')
                                            ->label('Module Settings')
                                            ->helperText('Key-value pairs of module settings')
                                            ->keyLabel('Setting Key')
                                            ->valueLabel('Setting Value')
                                            ->addable()
                                            ->reorderable()
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Module Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_enabled')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('priority')
                    ->label('Priority')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'enabled' => 'Enabled',
                        'disabled' => 'Disabled',
                    ])
                    ->query(function ($query, $data) {
                        if ($data['value'] === 'enabled') {
                            return $query->where('is_enabled', true);
                        } elseif ($data['value'] === 'disabled') {
                            return $query->where('is_enabled', false);
                        }
                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Configure')
                    ->icon('heroicon-o-cog-6-tooth'),

                Tables\Actions\Action::make('toggle')
                    ->label(fn ($record) => $record->isEnabled() ? 'Disable' : 'Enable')
                    ->icon(fn ($record) => $record->isEnabled() ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn ($record) => $record->isEnabled() ? 'danger' : 'success')
                    ->requiresConfirmation()
                    ->modalHeading(fn ($record) => ($record->isEnabled() ? 'Disable' : 'Enable') . ' Module')
                    ->modalDescription(fn ($record) => "Are you sure you want to " . ($record->isEnabled() ? 'disable' : 'enable') . " the {$record->getName()} module?")
                    ->action(function (Module $record) {
                        $moduleManager = app(ModuleManager::class);

                        if ($record->isEnabled()) {
                            $moduleManager->uninstall(['for_module' => $record->getName()]);
                        } else {
                            $moduleManager->set_installed(['for_module' => $record->getName()]);
                        }

                        Cache::forget('modules');

                        return redirect()->back()->with('success', "Module {$record->getName()} has been " . ($record->isEnabled() ? 'disabled' : 'enabled') . ".");
                    }),

                Tables\Actions\Action::make('refresh')
                    ->label('Refresh Cache')
                    ->icon('heroicon-o-arrow-path')
                    ->color('secondary')
                    ->action(function () {
                        Cache::forget('modules');
                        app(ModuleManager::class)->scan();

                        return redirect()->back()->with('success', 'Module cache has been refreshed.');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('enable')
                    ->label('Enable Selected')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Enable Selected Modules')
                    ->action(function (array $records) {
                        $moduleManager = app(ModuleManager::class);
                        $count = 0;

                        foreach ($records as $record) {
                            if (!$record->isEnabled()) {
                                $moduleManager->set_installed(['for_module' => $record->getName()]);
                                $count++;
                            }
                        }

                        Cache::forget('modules');

                        return redirect()->back()->with('success', "{$count} modules have been enabled.");
                    }),

                Tables\Actions\BulkAction::make('disable')
                    ->label('Disable Selected')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Disable Selected Modules')
                    ->action(function (array $records) {
                        $moduleManager = app(ModuleManager::class);
                        $count = 0;

                        foreach ($records as $record) {
                            if ($record->isEnabled()) {
                                $moduleManager->uninstall(['for_module' => $record->getName()]);
                                $count++;
                            }
                        }

                        Cache::forget('modules');

                        return redirect()->back()->with('success', "{$count} modules have been disabled.");
                    }),
            ])
            ->emptyStateHeading('No modules found')
            ->emptyStateDescription('Install modules to extend your website functionality.')
            ->emptyStateIcon('heroicon-o-puzzle-piece');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModuleConfigurations::route('/'),
            'edit' => Pages\EditModuleConfiguration::route('/{record}/edit'),
        ];
    }
}
