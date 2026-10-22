<?php

namespace Modules\DigitalProduct\Filament\Pages;

use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Customer\Models\Customer;
use Modules\DigitalProduct\Models\DigitalProductDownload;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;

class MyDownloads extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'modules.digitalproduct::filament.pages.my-downloads';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrow-down-tray';
    protected static ?int $navigationSort = 4;

    public function getTitle(): string
    {
        return __('My Downloads');
    }

    public static function getNavigationLabel(): string
    {
        return __('My Downloads');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Orders & Billing');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getDownloadsQuery())
            ->columns([
                TextColumn::make('product_id')
                    ->label(__('Product'))
                    ->formatStateUsing(fn ($state) => Product::find($state)?->title ?? '—'),

                TextColumn::make('file.original_name')
                    ->label(__('File')),

                TextColumn::make('downloads_count')
                    ->label(__('Downloads'))
                    ->formatStateUsing(fn ($state, DigitalProductDownload $record) => $record->download_limit
                        ? $state . ' / ' . $record->download_limit
                        : (string) $state),

                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->state(function (DigitalProductDownload $record): string {
                        if ($record->isExpired()) {
                            return __('Expired');
                        }
                        if ($record->hasReachedLimit()) {
                            return __('Limit reached');
                        }
                        return __('Available');
                    })
                    ->color(fn (DigitalProductDownload $record) => $record->canDownload() ? 'success' : 'gray'),

                TextColumn::make('created_at')
                    ->label(__('Purchased'))
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->actions([
                Action::make('download')
                    ->label(__('Download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (DigitalProductDownload $record) => route('digital-product.download', ['token' => $record->token]))
                    ->openUrlInNewTab()
                    ->visible(fn (DigitalProductDownload $record) => $record->canDownload()),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading(__('No downloads'))
            ->emptyStateDescription(__('Files you purchase will appear here.'));
    }

    protected function getDownloadsQuery(): Builder
    {
        $user = Auth::user();

        $customerId = $user ? $this->getCustomerId($user) : null;
        $email = $user->email ?? null;

        return DigitalProductDownload::query()
            ->with('file')
            ->where(function (Builder $q) use ($customerId, $email) {
                if ($customerId) {
                    $q->orWhereIn('order_id', Order::where('customer_id', $customerId)->pluck('id'));
                    $q->orWhere('customer_id', $customerId);
                }
                if ($email) {
                    $q->orWhere('customer_email', $email);
                }
                if (!$customerId && !$email) {
                    $q->whereRaw('1 = 0'); // not logged in → nothing
                }
            });
    }

    protected function getCustomerId($user): ?int
    {
        $customer = Customer::where('user_id', $user->id)->first();
        if ($customer) {
            return $customer->id;
        }

        return Customer::where('email', $user->email)->value('id');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check();
    }
}
