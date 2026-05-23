<?php

namespace MicroweberPackages\LaravelModules\Filament\Resources\ModuleResource;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Filament\Tables\Columns\ClickableColumn;
use MicroweberPackages\Filament\Tables\Columns\SVGColumn;
use MicroweberPackages\LaravelModules\Filament\Resources\ModuleResource\Pages\ListModules;
use MicroweberPackages\Filament\Support\AdminDisplayName;
use MicroweberPackages\LaravelModules\Models\SystemModulesSushi;

class ModuleResource extends Resource
{
    protected static ?string $model = SystemModulesSushi::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static string | \UnitEnum | null $navigationGroup = 'Modules';

    protected static ?string $label = 'Modules';

    protected static ?int $navigationSort = 1;

    public static string $description = 'Manage system modules';

    public function getDescription(): string
    {

        return static::$description;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
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

                    // task-2026-05-23-366972 / AI-1041 — PascalCase module names
                    // (e.g. 'LayoutContent', 'AiWizard') formatted via AdminDisplayName
                    // which space-splits camelCase and uppercases known acronyms (AI, SEO…).
                    Tables\Columns\TextColumn::make('name')
                        ->searchable()
                        ->formatStateUsing(fn (?string $state): string => AdminDisplayName::format($state))
                        ->weight(FontWeight::Bold)
//                        ->action(function (SystemModulesSushi $module) {
//                            return redirect($module->adminUrl());
//                        })
                        ->grow(false),

                    // task-2026-05-23-cf2dd3 / AI-1042 — 2-line truncated description
                    // from module.json (or the CamelCase-spaced fallback set by SystemModulesSushi).
                    // CSS -webkit-line-clamp applied via extraAttributes.
                    Tables\Columns\TextColumn::make('description')
                        ->color('gray')
                        ->size(Tables\Columns\TextColumn\TextColumnSize::ExtraSmall)
                        ->wrap()
                        ->extraAttributes([
                            'style' => 'overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;font-size:12px;opacity:0.7;',
                        ])
                        ->grow(false),

                    // task-2026-05-23-82ffcf / AI-1046 — subdued version badge.
                    // Only shown when version is set and not the default 'dev' placeholder.
                    Tables\Columns\TextColumn::make('version')
                        ->formatStateUsing(fn (?string $state): string => ($state && $state !== 'dev') ? 'v' . $state : '')
                        ->color('gray')
                        ->size(Tables\Columns\TextColumn\TextColumnSize::ExtraSmall)
                        ->extraAttributes(['style' => 'font-size:11px;opacity:0.5;'])
                        ->grow(false),
                ])->url(function (SystemModulesSushi $module) {
                    return $module->adminUrl();
                }),


            ])
            ->contentGrid([
                'md' => 4,
                'xl' => 4,
            ])
            // task-2026-05-23-bbad8f / AI-1043 — replace the default circle-X empty-state icon
            // (implies an error) with a puzzle-piece icon + CTA link to the Marketplace.
            ->emptyStateIcon('heroicon-o-puzzle-piece')
            ->emptyStateHeading('No modules found')
            ->emptyStateDescription('Install modules from the Marketplace to extend your site.')
            ->emptyStateActions([
                \Filament\Actions\Action::make('browse_marketplace')
                    ->label('Browse Marketplace →')
                    ->url('/admin/marketplace')
                    ->color('primary')
                    ->link(),
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
                fn($action) => $action
                    ->slideOver()
            )
            ->actions([

            ])
            ->bulkActions([

            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'alias', 'description'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var SystemModulesSushi $record */

        return [
            'Module' => $record->name,
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        $url = ($record->adminUrl());

        if (!$url) {
            $url = '#';
        }

        return $url;
    }

    public static function getGlobalSearchResultActions(Model $record): array
    {

        $url = ($record->adminUrl());

        return [
            Action::make('view')
                ->openUrlInNewTab()
                ->url($url),
            //->url(static::getUrl('view', ['record' => $record])),
        ];
    }

    /** @return Builder<SystemModulesSushi> */
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
        ];
    }
}
