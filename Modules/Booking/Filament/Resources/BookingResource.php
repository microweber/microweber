<?php

namespace Modules\Booking\Filament\Resources;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Booking\Filament\Resources\BookingResource\Pages;
use Modules\Booking\Models\Booking;
use Modules\Product\Models\Product;

/**
 * Admin screen to view and operate bookings: check guests in/out, cancel
 * (releasing availability), and adjust a reservation.
 */
class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';
    protected static string | \UnitEnum | null $navigationGroup = 'Shop';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationLabel = 'Bookings';
    protected static ?string $modelLabel = 'booking';
    protected static ?string $pluralModelLabel = 'bookings';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Booking')->columns(2)->schema([
                Select::make('status')
                    ->options([
                        Booking::STATUS_PENDING => 'Pending',
                        Booking::STATUS_CONFIRMED => 'Confirmed',
                        Booking::STATUS_CHECKED_IN => 'Checked in',
                        Booking::STATUS_CHECKED_OUT => 'Checked out',
                        Booking::STATUS_CANCELLED => 'Cancelled',
                    ])
                    ->required(),
                TextInput::make('units')->numeric()->minValue(1)->default(1),
                DateTimePicker::make('starts_at')->seconds(false)->required(),
                DateTimePicker::make('ends_at')->seconds(false)->required(),
                TextInput::make('guests')->numeric()->minValue(1),
                TextInput::make('customer_email')->email(),
                TextInput::make('price')->numeric()->prefix(fn () => currency_symbol() ?: '$'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product_id')
                    ->label('Product')
                    ->formatStateUsing(fn ($state) => Product::find($state)?->title ?? ('#' . $state))
                    ->searchable(),
                TextColumn::make('booking_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'time_slot' ? 'Time slot' : 'Date range'),
                TextColumn::make('starts_at')->label('From')->dateTime('M j, Y H:i')->sortable(),
                TextColumn::make('ends_at')->label('To')->dateTime('M j, Y H:i')->sortable(),
                TextColumn::make('units')->label('Units'),
                TextColumn::make('guests')->label('Guests')->toggleable(),
                TextColumn::make('customer_email')->label('Customer')->searchable()->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        Booking::STATUS_CONFIRMED => 'success',
                        Booking::STATUS_CHECKED_IN => 'info',
                        Booking::STATUS_CHECKED_OUT => 'gray',
                        Booking::STATUS_CANCELLED => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('price')->money(fn ($record) => $record->currency ?: 'USD')->toggleable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')->options([
                    Booking::STATUS_PENDING => 'Pending',
                    Booking::STATUS_CONFIRMED => 'Confirmed',
                    Booking::STATUS_CHECKED_IN => 'Checked in',
                    Booking::STATUS_CHECKED_OUT => 'Checked out',
                    Booking::STATUS_CANCELLED => 'Cancelled',
                ]),
                \Filament\Tables\Filters\SelectFilter::make('booking_type')->options([
                    'date_range' => 'Date range',
                    'time_slot' => 'Time slot',
                ]),
            ])
            ->actions([
                \Filament\Actions\Action::make('check_in')
                    ->label('Check in')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->color('info')
                    ->visible(fn (Booking $r) => $r->status === Booking::STATUS_CONFIRMED)
                    ->requiresConfirmation()
                    ->action(fn (Booking $r) => $r->forceFill([
                        'status' => Booking::STATUS_CHECKED_IN,
                        'checked_in_at' => now(),
                    ])->save()),
                \Filament\Actions\Action::make('check_out')
                    ->label('Check out')
                    ->icon('heroicon-o-arrow-left-on-rectangle')
                    ->color('gray')
                    ->visible(fn (Booking $r) => $r->status === Booking::STATUS_CHECKED_IN)
                    ->requiresConfirmation()
                    ->action(fn (Booking $r) => $r->forceFill([
                        'status' => Booking::STATUS_CHECKED_OUT,
                        'checked_out_at' => now(),
                    ])->save()),
                \Filament\Actions\Action::make('cancel_booking')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Booking $r) => !in_array($r->status, [Booking::STATUS_CANCELLED, Booking::STATUS_CHECKED_OUT], true))
                    ->requiresConfirmation()
                    ->modalDescription('Cancelling releases the dates back to availability.')
                    ->action(fn (Booking $r) => $r->forceFill(['status' => Booking::STATUS_CANCELLED])->save()),
                \Filament\Actions\EditAction::make(),
            ])
            ->defaultSort('starts_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
