<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Models\Order;
use Modules\Customer\Models\Customer;

class OrderHistory extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'modules.profile::pages.order-history';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 2;

    public function getTitle(): string
    {
        return __('Order History');
    }

    public static function getNavigationLabel(): string
    {
        return __('Order History');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Profile');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getOrdersQuery())
            ->columns([
                Tables\Columns\TextColumn::make('order_reference_id')
                    ->label(__('Order #'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Date'))
                    ->dateTime('M j, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('order_status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'new' => __('New'),
                            'completed' => __('Completed'),
                            'pending' => __('Pending'),
                            default => $state,
                        };
                    })
                    ->color(function ($state) {
                        return match ($state) {
                            'new' => 'gray',
                            'completed' => 'success',
                            'pending' => 'warning',
                            default => 'gray',
                        };
                    }),

                Tables\Columns\TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->money(fn($record) => $record->currency ?? 'USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('is_paid')
                    ->label(__('Payment Status'))
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return $state ? __('Paid') : __('Unpaid');
                    })
                    ->color(function ($state) {
                        return $state ? 'success' : 'danger';
                    }),

                Tables\Columns\TextColumn::make('customer.city')
                    ->label(__('City'))
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('new')
                    ->label(__('New Orders'))
                    ->query(fn(Builder $query) => $query->where('order_status', 'new')),
                Tables\Filters\Filter::make('completed')
                    ->label(__('Completed Orders'))
                    ->query(fn(Builder $query) => $query->where('order_status', 'completed')),
                Tables\Filters\Filter::make('pending')
                    ->label(__('Pending Orders'))
                    ->query(fn(Builder $query) => $query->where('order_status', 'pending')),
            ])
            ->actions([
                Action::make('view')
                    ->label(__('View Details'))
                    ->icon('heroicon-o-eye')
                    ->modalHeading(function (Order $record) {
                        return __('Order #:reference', ['reference' => $record->order_reference_id]);
                    })
                    ->modalContent(function (Order $record) {
                        return view('modules.profile::pages.order-details', ['order' => $record]);
                    })
                    ->modalWidth('3xl'),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading(__('No Orders'))
            ->emptyStateDescription(__('You have not placed any orders yet.'));
    }

    protected function getOrdersQuery(): Builder
    {
        $user = Auth::user();

        // Get customer ID from the authenticated user
        $customerId = $this->getCustomerId($user);

        return Order::query()
            ->where('customer_id', $customerId)
            ->with(['customer', 'cart.products']);
    }

    protected function getCustomerId($user): ?int
    {
        // Check if user has a customer record
        $customer = Customer::where('user_id', $user->id)->first();

        if ($customer) {
            return $customer->id;
        }

        // Fallback: try to find orders by email
        return Customer::where('email', $user->email)->value('id');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check();
    }
}
