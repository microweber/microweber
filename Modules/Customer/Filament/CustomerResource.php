<?php

namespace Modules\Customer\Filament;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Modules\Customer\Filament\CustomerResource\Pages\CreateCustomer;
use Modules\Customer\Filament\CustomerResource\Pages\EditCustomer;
use Modules\Customer\Filament\CustomerResource\Pages\ListCustomers;
use Modules\Customer\Models\Customer;
use Modules\Tag\Models\Tag;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';
    protected static string | \UnitEnum | null $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    public static string $description = 'Manage customers for your shop';
    public function getDescription(): string
    {

        return static::$description;
    }
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('first_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    // AI-85 / TICKET-AW (cycle-96 2026-05-09): ->tel()
                    // adds the matching HTML5 input type so mobile
                    // keyboards promote the dial-pad and the form
                    // field declares its semantic intent to AT.
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\Toggle::make('active')
                    ->label('Active')
                    ->required()
                    ->inline(false),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'username')
                    ->preload()
                    ->reactive()
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('currency_id')
                    ->label('Currency')
                    ->relationship('currency', 'name')
                    ->searchable()
                    ->preload()
                    ->default(fn () => \Modules\Currency\Models\Currency::getDefault()?->id)
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('US Dollar'),
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->maxLength(3)
                            ->minLength(3)
                            ->placeholder('USD')
                            ->unique()
                            ->dehydrateStateUsing(fn (string $state): string => strtoupper($state)),
                        Forms\Components\TextInput::make('symbol')
                            ->required()
                            ->maxLength(10)
                            ->placeholder('$'),
                        Forms\Components\TextInput::make('precision')
                            ->numeric()
                            ->default(2)
                            ->minValue(0)
                            ->maxValue(8),
                        Forms\Components\TextInput::make('thousand_separator')
                            ->maxLength(1)
                            ->default(','),
                        Forms\Components\TextInput::make('decimal_separator')
                            ->maxLength(1)
                            ->default('.'),
                    ])
                    ->createOptionAction(function ($action) {
                        return $action
                            ->modalHeading('Create Currency')
                            ->modalSubmitActionLabel('Create Currency')
                            ->modalWidth('lg')
                            ->slideOver();
                    }),
Forms\Components\Select::make('company_id')
->label('Company')
->relationship('company', 'name')
->searchable()
->preload()
->reactive()
->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('company_number')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('vat_number')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->maxLength(255)
                            ->unique(),

                        Forms\Components\TextInput::make('phone')
                            // AI-85 / TICKET-AW (cycle-96): ->tel()
                            // for mobile dial-pad + AT semantics.
                            ->tel()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('address')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('city')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('zip')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('country')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('website')
                            // AI-85 / TICKET-AW (cycle-96): ->url()
                            // adds HTML5 url-validation + Laravel
                            // url validation rule so save-time
                            // rejects "javascript:..." or "not-a-url"
                            // before they hit the database.
                            ->url()
                            ->maxLength(255),

                    ])
            ->createOptionAction(function ($action) {
                return $action
                ->modalHeading('Create company')
                ->modalSubmitActionLabel('Create company')
                ->modalWidth('lg')
                ->slideOver();
            }),
            Forms\Components\Section::make('Segmentation')
                ->description('Manage customer tags and segments')
                ->collapsible()
                ->schema([
                    Forms\Components\Select::make('tags')
                        ->label('Tags')
                        ->multiple()
                        ->relationship('tags', 'name')
                        ->preload()
                        ->searchable()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('slug')
                                ->maxLength(255)
                                ->helperText('Leave empty to auto-generate from name'),
                            Forms\Components\Textarea::make('description')
                                ->maxLength(65535),
                        ])
                        ->createOptionAction(function ($action) {
                            return $action
                                ->modalHeading('Create Tag')
                                ->modalSubmitActionLabel('Create Tag')
                                ->modalWidth('lg')
                                ->slideOver();
                        })
                        ->helperText('Assign tags to categorize and segment this customer'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        // task-2026-05-26 / AI-1095 — exclude multi-seed-source test customers.
        // PHPUnit factories produce @example.com emails and Faker names that
        // contaminate admin list with mismatched name columns.
        ->modifyQueryUsing(fn (EloquentBuilder $query) => $query
            ->where(function (EloquentBuilder $q) {
                $q->where('email', 'NOT LIKE', '%@example.com')
                  ->where('email', 'NOT LIKE', '%@example.org')
                  ->where('email', 'NOT LIKE', '%@example.net');
            })
        )
        ->emptyState(function (Table $table) {
            $modelName = static::$model;
            return view('modules.content::filament.admin.empty-state', ['modelName' => $modelName]);

        })
        // task-2026-05-28-2f5a6c / AI-1097 — column bloat reduction.
        // /admin/customers default-rendered 11 columns at desktop
        // (id, name, first_name, last_name, phone, email, active,
        // user.username, currency.name, company.name, tags) which
        // forces horizontal scroll at 1440px. Default-visible
        // reduced to the 5 highest-signal columns (name, email,
        // phone, active, tags); the other 6 are kept toggleable
        // (hidden by default) so power users can re-enable them
        // via the column-toggle menu without losing data access.
        ->columns([
            // task-2026-06-06-AI1101 — don't surface the raw auto-increment as a
            // bare number; present it as a stable customer reference code derived
            // purely from the id (no new data, fully reversible). e.g. 17426 -> C-17426.
            Tables\Columns\TextColumn::make('id')
                ->label('Customer')
                ->formatStateUsing(fn ($state) => $state ? 'C-' . str_pad((string) $state, 5, '0', STR_PAD_LEFT) : '—')
                ->sortable()->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('first_name')->sortable()->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('last_name')->sortable()->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('phone')->sortable()->searchable()
                ->visibleFrom('md'),
            Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
            Tables\Columns\IconColumn::make('active')
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->trueColor('success')
                ->falseIcon('heroicon-o-x-circle')
                ->falseColor('danger')
                ->sortable(),
            Tables\Columns\TextColumn::make('user.username')->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('currency.name')->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('company.name')->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('tags.name')
                ->label('Tags')
                ->badge()
                ->color('primary')
                ->separator(',')
                ->limitList(3)
                ->expandableLimitedList()
                ->searchable(),
        ])
        ->filters([
            Tables\Filters\Filter::make('active')
            ->query(fn($query) => $query->where('active', true)),
            Tables\Filters\SelectFilter::make('tags')
                ->label('Has Tags')
                ->multiple()
                ->relationship('tags', 'name')
                ->searchable()
                ->preload(),
            Tables\Filters\Filter::make('without_tags')
                ->label('Without Tags')
                ->toggle()
                ->query(fn(Builder $query) => $query->doesntHave('tags')),
            Tables\Filters\SelectFilter::make('status')
                ->label('Status')
                ->options([
                    'active' => 'Active',
                    'suspended' => 'Suspended',
                    'pending' => 'Pending',
                    'deleted' => 'Deleted',
                    'inactive' => 'Inactive',
                ]),
        ])
        ->actions([
            // TASK-020 / TICKET-K / AI-38 (cycle-60 2026-05-08):
            // record-contextual label + tooltip so screen readers
            // announce `Edit "jane@example.com"` and the hover
            // tooltip matches. Anchor on email; fall back to "#{id}"
            // for not-yet-saved rows / anonymized records.
            Tables\Actions\EditAction::make()
                ->label(fn (Model $record): string => 'Edit "' . static::customerRowLabel($record) . '"')
                ->tooltip(fn (Model $record): string => 'Edit "' . static::customerRowLabel($record) . '"'),
            Tables\Actions\DeleteAction::make()
                ->label(fn (Model $record): string => 'Delete "' . static::customerRowLabel($record) . '"')
                ->tooltip(fn (Model $record): string => 'Delete "' . static::customerRowLabel($record) . '"'),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
            Tables\Actions\BulkAction::make('addTags')
                ->label('Add Tags')
                ->icon('heroicon-m-tag')
                ->requiresConfirmation()
                ->modalHeading('Add Tags to Selected Customers')
                ->modalDescription('Select tags to add to the selected customers.')
                ->form([
                    Forms\Components\Select::make('tags')
                        ->label('Tags')
                        ->multiple()
                        ->options(fn() => Tag::pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                ])
                ->action(function ($records, array $data) {
                    $tagIds = $data['tags'] ?? [];
                    foreach ($records as $record) {
                        $record->addTags($tagIds);
                    }
                })
                ->deselectRecordsAfterCompletion(),
            Tables\Actions\BulkAction::make('removeTags')
                ->label('Remove Tags')
                ->icon('heroicon-m-minus-circle')
                ->requiresConfirmation()
                ->modalHeading('Remove Tags from Selected Customers')
                ->modalDescription('Select tags to remove from the selected customers.')
                ->form([
                    Forms\Components\Select::make('tags')
                        ->label('Tags to Remove')
                        ->multiple()
                        ->options(fn() => Tag::pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                ])
                ->action(function ($records, array $data) {
                    $tagIds = $data['tags'] ?? [];
                    foreach ($records as $record) {
                        $record->removeTags($tagIds);
                    }
                })
                ->deselectRecordsAfterCompletion(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }

    /**
     * Get the attributes that should be searchable globally.
     *
     * @return array
     */
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'first_name', 'last_name', 'email', 'phone', 'company.name'];
    }

    /**
     * Get the title for the global search result.
     *
     * @param Model $record
     * @return string
     */
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name ?? $record->email ?? 'Customer #' . $record->id;
    }

    /**
     * Get the details for the global search result.
     *
     * @param Model $record
     * @return array
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Email' => $record->email,
            'Phone' => $record->phone,
            'Company' => $record->company?->name,
            'Status' => $record->active ? 'Active' : 'Inactive',
        ];
    }

    /**
     * Get the actions for the global search result.
     *
     * @param Model $record
     * @return array
     */
    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('edit')
                ->url(static::getUrl('edit', ['record' => $record->id])),
        ];
    }

    /**
     * TASK-020 / TICKET-K / AI-38 (cycle-60 2026-05-08): build a
     * record-contextual label for screen-reader announcements on
     * customer-row actions. Anchor on email; fall back to "#{id}"
     * for not-yet-saved rows or anonymized records.
     */
    public static function customerRowLabel(Model $record): string
    {
        $email = trim((string) ($record->email ?? ''));
        if ($email !== '') {
            return $email;
        }
        $id = $record->id ?? '';
        return $id !== '' ? '#' . $id : 'unknown';
    }
}
