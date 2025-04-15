<?php

namespace Modules\Invoice\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Filament\Resources\InvoiceResource\Pages;
use Illuminate\Database\Eloquent\Builder;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables\Actions\Action;

class InvoiceResource extends Resource
{
    protected static ?string $navigationGroup = 'Shop';
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationLabel = 'Invoices';
    protected static ?string $modelLabel = 'Invoice';
    protected static ?string $slug = 'invoices';
    protected static ?int $navigationSort = 12;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Section::make('Invoice Details')
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
                                            ->prefix('$')
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

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => Invoice::STATUS_OVERDUE,
                        'warning' => Invoice::STATUS_DRAFT,
                        'success' => [Invoice::STATUS_PAID, Invoice::STATUS_COMPLETED],
                    ]),

                Tables\Columns\BadgeColumn::make('paid_status')
                    ->colors([
                        'danger' => Invoice::STATUS_UNPAID,
                        'warning' => Invoice::STATUS_PARTIALLY_PAID,
                        'success' => Invoice::STATUS_PAID,
                    ]),
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
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function (Invoice $record) {
                        $pdf = Pdf::loadView('invoice::pdf', ['invoice' => $record]);
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, $record->invoice_number . '.pdf');
                    }),
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
