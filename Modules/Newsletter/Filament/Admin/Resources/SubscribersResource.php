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
use Filament\Actions\ImportAction as ImportTableAction;
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

    protected static ?string $label = 'Subscriber';
    protected static ?string $pluralLabel = 'Subscribers';

    protected static string | \UnitEnum | null $navigationGroup = 'Subscribers';

    protected static ?int $navigationSort = 1;

    protected static bool $isGloballySearchable = true;

    protected static ?bool $isGlobalSearchForcedCaseInsensitive = true;

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
                        // audit-test 2026-05-08 PM TASK-019 / TICKET-AN finding #9:
                        // badge color match was missing pending_confirmation,
                        // complained, invalid_email — all valid subscriber
                        // states the Newsletter pipeline can produce. These
                        // states fell through to the bare 'gray' default,
                        // hiding their semantic meaning from admins.
                        // 'unknown' fall-through is now explicit so an admin
                        // sees a visible 'gray' badge for any new state that
                        // ships without updating this list — better than a
                        // silent default.
                        ->color(fn (string $state): string => match ($state) {
                            'active' => 'success',
                            'unsubscribed' => 'danger',
                            'bounced' => 'warning',
                            'pending_confirmation' => 'info',
                            'complained' => 'danger',
                            'invalid_email' => 'warning',
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
            // audit-test 2026-05-08 PM TASK-019 / TICKET-AN finding #8:
            // empty filters array forced admins to grep for status / list.
            // Status options enumerated to match the badge-color table
            // (cycle-54 finding #9) so filter values and badge values stay
            // in sync. lists relationship via belongsToMany (see model).
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'unsubscribed' => 'Unsubscribed',
                        'bounced' => 'Bounced',
                        'pending_confirmation' => 'Pending Confirmation',
                        'complained' => 'Complained',
                        'invalid_email' => 'Invalid Email',
                    ])
                    ->multiple(),
                \Filament\Tables\Filters\SelectFilter::make('lists')
                    ->label('Lists')
                    ->relationship('lists', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->headerActions([
                \MicroweberPackages\Filament\Actions\ImportAction::make('importProducts')
                    ->icon('heroicon-m-cloud-arrow-up')
                    ->importer(NewsletterSubscriberImporter::class)
                    ->chunkSize(50),
                \Filament\Actions\ExportAction::make()
                    ->exporter(NewsletterSubscriberExporter::class)
                    ->icon('heroicon-m-cloud-arrow-down')
                    // audit-test 2026-05-08 PM TASK-019 / TICKET-AN finding #10:
                    // Section::make() grouping for column checkboxes + export
                    // options (matches the CampaignResource shape).
                    ->form(function (\Filament\Actions\ExportAction $action): array {
                        $exportColumns = NewsletterSubscriberExporter::getColumns();
                        $columnCheckboxes = [];
                        foreach ($exportColumns as $column) {
                            $columnCheckboxes[] = \Filament\Forms\Components\Checkbox::make($column->getName())
                                ->label($column->getLabel())
                                ->default(true);
                        }
                        return [
                            \Filament\Schemas\Components\Section::make('Columns to export')
                                ->schema($columnCheckboxes),
                            \Filament\Schemas\Components\Section::make('Export options')
                                ->schema([
                                    Checkbox::make('export_multiple')
                                        ->label('Export to multiple files (ZIP)'),
                                ]),
                        ];
                    })
                    ->action(function (array $data, Table $table) {
                        $selectedColumns = array_keys(array_filter(Arr::except($data, 'export_multiple')));
                        $exportMultiple = $data['export_multiple'] ?? false;
                        $url = route('filament.admin-newsletter.export.subscribers', ['columns' => $selectedColumns, 'export_multiple' => $exportMultiple]);
                        return redirect()->to($url);
                    }),
                \Filament\Actions\CreateAction::make(),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\ExportBulkAction::make()
                        ->exporter(NewsletterSubscriberExporter::class)
                        // audit-test 2026-05-08 PM TASK-019 / TICKET-AN finding #10:
                        // bulk-export Section grouping (matches the single
                        // export form above).
                        ->form(function (\Filament\Actions\BulkAction $action): array {
                            $exportColumns = NewsletterSubscriberExporter::getColumns();
                            $columnCheckboxes = [];
                            foreach ($exportColumns as $column) {
                                $columnCheckboxes[] = \Filament\Forms\Components\Checkbox::make($column->getName())
                                    ->label($column->getLabel())
                                    ->default(true);
                            }
                            return [
                                \Filament\Schemas\Components\Section::make('Columns to export')
                                    ->schema($columnCheckboxes),
                                \Filament\Schemas\Components\Section::make('Export options')
                                    ->schema([
                                        Checkbox::make('export_multiple')
                                            ->label('Export to multiple files (ZIP)')
                                            ->default(false),
                                    ]),
                            ];
                        })
                        ->action(function (array $data, \Filament\Actions\BulkAction $action) {
                            $selectedColumns = array_keys(array_filter(Arr::except($data, 'export_multiple')));
                            $selectedRecordIds = implode(',', $action->getRecords()->pluck('id')->toArray());
                            $exportMultiple = $data['export_multiple'] ?? false;
                            $url = route('filament.admin-newsletter.export.subscribers', ['columns' => $selectedColumns, 'selected_ids' => $selectedRecordIds, 'export_multiple' => $exportMultiple]);
                            return redirect()->to($url);
                        }),
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No subscribers yet')
            ->emptyStateDescription('Add subscribers manually or import them from a CSV file.')
            ->emptyStateActions([
                \Filament\Actions\CreateAction::make()
                    ->label('Add Subscriber'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSubscribers::route('/'),
        ];
    }
}
