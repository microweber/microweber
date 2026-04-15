<?php

namespace Modules\Billing\Filament\Admin\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Support\Concerns\CanBeLazy;
use Modules\Billing\Models\Subscription;

class LatestSubscriptionsWidget extends BaseWidget
{
use CanBeLazy;

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected string $pollingInterval = '60s';

    public function table(Table $table): Table
    {
        return $table
            ->query(Subscription::query()->with(['plan'])->latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('User ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plan.name')
                    ->label('Plan')
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('stripe_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'trialing' => 'info',
                        'canceled' => 'danger',
                        'past_due' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
