<?php

namespace Modules\Newsletter\Filament\Admin\Resources;

use Filament\Schemas\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\ManageCampaigns;
use Modules\Newsletter\Filament\Admin\Resources\ListResource\Pages\ManageLists;
use Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource\Pages\ManageSenderAccounts;
use Modules\Newsletter\Filament\Admin\Resources\TemplatesResource\Pages\ManageTemplates;
use Modules\Newsletter\Filament\Exports\NewsletterListExporter; // Added exporter import
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Modules\Newsletter\Models\NewsletterTemplate;
use Illuminate\Support\Arr;
use Filament\Forms\Components\Checkbox;


class ListResource extends Resource
{
    protected static ?string $model = NewsletterList::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-list-bullet';

//    protected static ?string $slug = 'newsletter/sender-accounts';

//    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $label = 'List';
    protected static ?string $pluralLabel = 'Lists';

    protected static string | \UnitEnum | null $navigationGroup = 'Campaigns';

    protected static ?int $navigationSort = 2;

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'description'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        $details = [];

        if ($record->description) {
            $details['Description'] = \Illuminate\Support\Str::limit($record->description, 80);
        }

        return $details;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([

                TextInput::make('name')
                    ->label('Name')
                    ->placeholder('Enter name')
                    ->required(),

                TextInput::make('description')
                    ->label('Description')
                    ->placeholder('Enter description'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('description')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('subscribersCount')
                    ->label('Subscribers')
                    ->sortable(query: fn ($query, string $direction) =>
                        $query->withCount('subscribers')->orderBy('subscribers_count', $direction)
                    )
                    ->alignCenter(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\Filter::make('has_subscribers')
                    ->label('With subscribers')
                    ->query(fn ($query) => $query->whereHas('subscribers')),
            ])
             ->headerActions([ // Added header actions
                 Tables\Actions\ExportAction::make()
                     ->exporter(NewsletterListExporter::class)
                     ->icon('heroicon-m-cloud-arrow-down')
                     ->form(function (Tables\Actions\ExportAction $action): array {
                         $exportColumns = NewsletterListExporter::getColumns();
                         $schemaSchema = [];
                         foreach ($exportColumns as $column) {
                             $schemaSchema[] = \Filament\Forms\Components\Checkbox::make($column->getName())
                                 ->label($column->getLabel())
                                 ->default(true);
                         }
                         $schemaSchema[] = Checkbox::make('export_multiple')
                             ->label('Export to multiple files (ZIP)');
                         return $schemaSchema;
                     })
                     ->action(function (array $data) {
                         $selectedColumns = array_keys(array_filter(Arr::except($data, 'export_multiple')));
                         $exportMultiple = $data['export_multiple'] ?? false;
                         $url = route('filament.admin-newsletter.export.lists', ['columns' => $selectedColumns, 'export_multiple' => $exportMultiple]);
                         return redirect()->to($url);
                     }),
             ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ExportBulkAction::make()
                        ->exporter(NewsletterListExporter::class)
                        ->form(function (Tables\Actions\BulkAction $action): array {
                            $exportColumns = NewsletterListExporter::getColumns();
                            $schemaSchema = [];
                            foreach ($exportColumns as $column) {
                                $schemaSchema[] = \Filament\Forms\Components\Checkbox::make($column->getName())
                                    ->label($column->getLabel())
                                    ->default(true);
                            }
                            $schemaSchema[] = Checkbox::make('export_multiple')
                                ->label('Export to multiple files (ZIP)')
                                ->default(false);
                            return $schemaSchema;
                        })
                        ->action(function (array $data, Tables\Actions\BulkAction $action) {
                            $selectedColumns = array_keys(array_filter(Arr::except($data, 'export_multiple')));
                            $selectedRecordIds = $action->getRecords()->pluck('id')->toArray();
                            $route = route('filament.admin-newsletter.export.lists', ['columns' => $selectedColumns, 'selected_ids' => implode(',', $selectedRecordIds), 'export_multiple' => $data['export_multiple'] ?? false]);
                            return redirect()->to($route);
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageLists::route('/'),
        ];
    }
}
