<?php

namespace Modules\Newsletter\Filament\Admin\Resources;

use Filament\Actions\ImportAction;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Actions\ImportAction as ImportTableAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use League\Csv\Reader as CsvReader;
use League\Csv\Statement;
use Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource\Pages\ManageSenderAccounts;
use Modules\Newsletter\Filament\Admin\Resources\SubscribersResource\Pages\ManageSubscribers;
use Modules\Newsletter\Filament\Exports\NewsletterSubscriberExporter;
use Modules\Newsletter\Filament\Imports\NewsletterSubscriberImporter;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Modules\Newsletter\Models\NewsletterSubscriber;

class SubscribersResource extends Resource
{
    protected static ?string $model = NewsletterSubscriber::class;

    protected static ?string $recordTitleAttribute = 'email';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

    protected static ?string $label = 'Subscribers';

    protected static string | \UnitEnum | null $navigationGroup = 'Subscribers';

    protected static ?int $navigationSort = 1;

    public static function getGloballySearchableAttributes(): array
    {
        return ['email', 'name'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        $details = [];

        if ($record->name) {
            $details['Name'] = $record->name;
        }

        if ($record->status) {
            $details['Status'] = ucfirst($record->status);
        }

        return $details;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('email')
                    ->label('Email')
                    ->placeholder('Enter email')
                    ->required()
                    ->email(),

                TextInput::make('name')
                    ->label('Name')
                    ->placeholder('Enter name'),

                CheckboxList::make('lists')
                    ->relationship('lists', 'name')
                    ->label('Subscribed for lists')
                    ->options(fn () => static::getSubscriberListOptions())


            ]);
    }

    public static function getSubscriberListOptions(): array
    {
        $defaultList = NewsletterList::findOrCreateDefault();
        $lists = NewsletterList::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();

        if (! isset($lists[$defaultList->id])) {
            return $lists;
        }

        $defaultOption = [$defaultList->id => $lists[$defaultList->id]];
        unset($lists[$defaultList->id]);

        return $defaultOption + $lists;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('lists'))
            ->columns([

                    Tables\Columns\TextColumn::make('mobile_identity')
                        ->label('Subscriber')
                        ->state(fn (NewsletterSubscriber $record): string => $record->name ?: $record->email)
                        ->searchable(['name', 'email'])
                        ->sortable()
                        ->weight('medium')
                        ->description(fn (NewsletterSubscriber $record): ?string => $record->email)
                        ->hiddenFrom('md')
                        ->alignLeft(),

                    Tables\Columns\TextColumn::make('name')
                        ->searchable(['name', 'email'])
                        ->sortable()
                        ->weight('medium')
                        ->visibleFrom('md')
                        ->alignLeft(),

                    Tables\Columns\TextColumn::make('email')
                        ->label('Email address')
                        ->searchable()
                        ->sortable()
                        ->color('gray')
                        ->visibleFrom('md')
                        ->alignLeft(),

                    Tables\Columns\TextColumn::make('lists.name')
                        ->label('Lists')
                        ->listWithLineBreaks()
                        ->limitList(2)
                        ->expandableLimitedList()
                        ->visibleFrom('md'),

                    Tables\Columns\TextColumn::make('status')
                        ->label('Status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'active' => 'success',
                            'unsubscribed' => 'danger',
                            'bounced' => 'warning',
                            default => 'gray',
                        })
                        ->sortable()
                        ->toggleable(),

                    Tables\Columns\TextColumn::make('created_at')
                        ->label('Created')
                        ->dateTime('M j, Y')
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                \MicroweberPackages\Filament\Tables\Actions\ImportAction::make('importProducts')
                    ->icon('heroicon-m-cloud-arrow-up')
                    ->importer(NewsletterSubscriberImporter::class)
                    ->chunkSize(50),
                Tables\Actions\ExportAction::make()
                    ->exporter(NewsletterSubscriberExporter::class)
                    ->icon('heroicon-m-cloud-arrow-down')
                    ->form(function (Tables\Actions\ExportAction $action): array {
                        $exportColumns = NewsletterSubscriberExporter::getColumns();
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
                    ->action(function (array $data, Table $table) {
                        $selectedColumns = array_keys(array_filter(Arr::except($data, 'export_multiple')));
                        $exportMultiple = $data['export_multiple'] ?? false;
                        $url = route('filament.admin-newsletter.export.subscribers', ['columns' => $selectedColumns, 'export_multiple' => $exportMultiple]);
                        return redirect()->to($url);
                    }),
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ExportBulkAction::make()
                        ->exporter(NewsletterSubscriberExporter::class)
                        ->form(function (Tables\Actions\BulkAction $action): array {
                            $exportColumns = NewsletterSubscriberExporter::getColumns();
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
                            $selectedRecordIds = implode(',', $action->getRecords()->pluck('id')->toArray());
                            $exportMultiple = $data['export_multiple'] ?? false;
                            $url = route('filament.admin-newsletter.export.subscribers', ['columns' => $selectedColumns, 'selected_ids' => $selectedRecordIds, 'export_multiple' => $exportMultiple]);
                            return redirect()->to($url);
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSubscribers::route('/'),
        ];
    }
}
