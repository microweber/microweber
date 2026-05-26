<?php

namespace Modules\Invoice\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Filament\Resources\InvoiceResource\Pages;
use Modules\Invoice\Filament\Exports\InvoiceExporter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Arr;
use Modules\Invoice\Services\InvoiceService;

class InvoiceResource extends Resource
{
    protected static string | \UnitEnum | null $navigationGroup = 'Shop Settings';
    protected static ?string $model = Invoice::class;
    protected static ?string $recordTitleAttribute = 'invoice_number';

    protected static string | null $navigationLabel = 'Invoices';
    protected static ?string $modelLabel = 'Invoice';
    protected static ?string $slug = 'invoices';
    protected static ?int $navigationSort = 120;



    public static string $description = 'Manage invoices';
    public function getDescription(): string
    {

        return static::$description;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['invoice_number', 'reference_number', 'customer.first_name', 'customer.last_name', 'customer.email'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->reference_number) {
            $details['Reference'] = $record->reference_number;
        }

        if ($record->customer) {
            $details['Customer'] = trim($record->customer->first_name . ' ' . $record->customer->last_name);
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
                Grid::make(2)
                    ->schema([
                        Forms\Components\Section::make('Invoice Details')
                            ->icon('heroicon-m-document-text')
                            ->schema([
                                Forms\Components\DatePicker::make('invoice_date')
                                    ->required()
                                    ->default(now()),

                                Forms\Components\DatePicker::make('due_date')
                                    ->required()
                                    ->default(now()->addDays(6)),

                                Forms\Components\TextInput::make('invoice_number')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->default(fn() => 'INV-' . Invoice::getNextInvoiceNumber('INV')),

                                Forms\Components\TextInput::make('reference_number'),

                                Forms\Components\Select::make('customer_id')
                                    ->relationship('customer', 'name')
                                    ->getOptionLabelFromRecordUsing(fn($record) => "[{$record->id}] {$record->getFullName()} ({$record->getEmail()})")                                    ->createOptionForm([

Forms\Components\Select::make('user_id')
->relationship('user', 'email')
->searchable()
->preload()
->required(),


                                        Forms\Components\TextInput::make('name')
                                            ->required(),

                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->required(),

                                        Forms\Components\TextInput::make('phone')
                                            ->tel()
                                            ->required(),

                                        Forms\Components\Textarea::make('address')
                                            ->rows(2)
                                            ->required(),
                                    ])
                                    ->createOptionAction(function ($action) {
                                        return $action
                                            ->modalHeading('Create Customer')
                                            ->modalSubmitActionLabel('Create Customer')
                                            ->modalWidth('lg')
                                            ->slideOver();
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\Select::make('status')
                                    ->options([
                                        Invoice::STATUS_DRAFT => 'Draft',
                                        Invoice::STATUS_SENT => 'Sent',
                                        Invoice::STATUS_VIEWED => 'Viewed',
                                        Invoice::STATUS_OVERDUE => 'Overdue',
                                        Invoice::STATUS_PAID => 'Paid',
                                        Invoice::STATUS_COMPLETED => 'Completed',
                                        Invoice::STATUS_VOID => 'Void',
                                    ])
                                    ->required(),

                                Forms\Components\Select::make('paid_status')
                                    ->options([
                                        Invoice::STATUS_UNPAID => 'Unpaid',
                                        Invoice::STATUS_PARTIALLY_PAID => 'Partially Paid',
                                        Invoice::STATUS_PAID => 'Paid',
                                        Invoice::STATUS_REFUNDED => 'Refunded',
                                    ])
                                    ->required(),
                            ])
                            ->columnSpanFull(),

                        Forms\Components\Section::make('Invoice Items')
                            ->icon('heroicon-m-queue-list')
                            ->schema([
                                Forms\Components\Repeater::make('items')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required(),

                                        Forms\Components\Textarea::make('description')
                                            ->rows(2),

                                        Forms\Components\TextInput::make('price')
                                            ->numeric()
                                            ->required()
                                            // task-2026-05-17-fc0b22 / AI-818 Slice B —
                                            // was hardcoded '$' which broke non-USD shops.
                                            // Pull from Microweber's canonical shop
                                            // currency_symbol() helper, fall back to '$'
                                            // when unset/empty.
                                            ->prefix(fn () => (function_exists('currency_symbol') ? currency_symbol() : null) ?: '$')
                                            ->afterStateHydrated(function ($state, $set) {
                                                if ($state) {
                                                    $set('price', $state);
                                                }
                                            })
                                            ->dehydrateStateUsing(fn($state) => $state),

                                        Forms\Components\TextInput::make('quantity')
                                            ->numeric()
                                            ->default(1)
                                            ->required(),

                                        Forms\Components\Placeholder::make('subtotal')
                                            ->content(function ($get) {
                                                $price = $get('price') ?? 0;
                                                $quantity = $get('quantity') ?? 0;
                                                return '$' . number_format($price * $quantity, 2);
                                            }),
                                    ])
                                    ->columns(3)
                                    ->columnSpanFull()
                                    ->live(),

                                Forms\Components\Placeholder::make('total')
                                    ->content(function ($get) {
                                        $total = collect($get('items') ?? [])->reduce(function ($sum, $item) {
                                            return $sum + ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
                                        }, 0);
                                        return 'Total: $' . number_format($total, 2);
                                    })
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

public static function getEloquentQuery(): Builder
{
return parent::getEloquentQuery()
->with(['customer', 'items']);
}

    public static function table(Table $table): Table
    {
        return $table
            ->emptyState(function (Table $table) {
                $modelName = static::$model;
                return view('modules.content::filament.admin.empty-state', ['modelName' => $modelName]);

            })
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->getStateUsing(fn($record) => $record->customer?->getFullName())
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('invoice_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total')
                    ->money()
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Invoice::STATUS_OVERDUE => 'danger',
                        Invoice::STATUS_DRAFT => 'warning',
                        Invoice::STATUS_PAID, Invoice::STATUS_COMPLETED => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('paid_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Invoice::STATUS_UNPAID => 'danger',
                        Invoice::STATUS_PARTIALLY_PAID => 'warning',
                        Invoice::STATUS_PAID => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        Invoice::STATUS_DRAFT => 'Draft',
                        Invoice::STATUS_SENT => 'Sent',
                        Invoice::STATUS_VIEWED => 'Viewed',
                        Invoice::STATUS_OVERDUE => 'Overdue',
                        Invoice::STATUS_PAID => 'Paid',
                        Invoice::STATUS_COMPLETED => 'Completed',
                        Invoice::STATUS_VOID => 'Void',
                    ]),

Tables\Filters\SelectFilter::make('paid_status')
            ->options([
                Invoice::STATUS_UNPAID => 'Unpaid',
                Invoice::STATUS_PARTIALLY_PAID => 'Partially Paid',
                Invoice::STATUS_PAID => 'Paid',
                Invoice::STATUS_REFUNDED => 'Refunded',
            ]),
        ])
        ->headerActions([
            Tables\Actions\ExportAction::make()
                ->exporter(InvoiceExporter::class)
                ->icon('heroicon-m-cloud-arrow-down')
                ->color('gray')
                ->form(function (Tables\Actions\ExportAction $action): array {
                    $exportColumns = InvoiceExporter::getColumns();
                    $schemaSchema = [];
                    foreach ($exportColumns as $column) {
                        $schemaSchema[] = Checkbox::make($column->getName())
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
                    $url = route('filament.admin.export.invoices', ['columns' => $selectedColumns, 'export_multiple' => $exportMultiple]);
                    return redirect()->to($url);
                }),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Action::make('pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function (Invoice $record) {
                    $pdf = Pdf::loadView('modules.invoice::pdf', ['invoice' => $record]);
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, $record->invoice_number . '.pdf');
                }),
            Action::make('send_email')
                ->label('Send Email')
                ->icon('heroicon-o-envelope')
                ->color('success')
                ->modalHeading('Send Invoice Email')
                ->modalDescription('Send this invoice to the customer via email with PDF attachment.')
                ->form([
                    Section::make('Email Details')
                        ->icon('heroicon-m-envelope')
                        ->schema([
                            TextInput::make('to_email')
                                ->label('To Email')
                                ->email()
                                ->required()
                                ->default(fn(Invoice $record) => $record->customer?->email)
                                ->placeholder('customer@example.com'),

                            Textarea::make('custom_message')
                                ->label('Custom Message')
                                ->placeholder('Optional: Add a personal message to the invoice email.')
                                ->rows(3),
                        ]),
                ])
                ->action(function (array $data, Invoice $record) {
                    $invoiceService = app(InvoiceService::class);
                    $result = $invoiceService->sendInvoiceEmail(
                        $record->id,
                        $data['to_email'],
                        $data['custom_message'] ?? null
                    );

                    if ($result['success']) {
                        return redirect()->back()->with('success', $result['message']);
                    } else {
                        return redirect()->back()->with('error', $result['message']);
                    }
                })
                ->requiresConfirmation()
                ->modalButton('Send Invoice'),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\ExportBulkAction::make()
                    ->form(function (Tables\Actions\BulkAction $action): array {
                        $exportColumns = InvoiceExporter::getColumns();
                        $schemaSchema = [];
                        foreach ($exportColumns as $column) {
                            $schemaSchema[] = Checkbox::make($column->getName())
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
                        $route = route('filament.admin.export.invoices', ['columns' => $selectedColumns, 'selected_ids' => implode(',', $selectedRecordIds), 'export_multiple' => $data['export_multiple'] ?? false]);
                        return redirect()->to($route);
                    }),
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
