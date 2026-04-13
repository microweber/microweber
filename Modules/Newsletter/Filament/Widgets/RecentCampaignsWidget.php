<?php

namespace Modules\Newsletter\Filament\Widgets;

use Filament\Support\Concerns\CanBeLazy;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterCampaignClickedLink;
use Modules\Newsletter\Models\NewsletterCampaignPixel;

class RecentCampaignsWidget extends BaseWidget
{
    use CanBeLazy;

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                NewsletterCampaign::query()
                    ->with('list')
                    ->latest()
                    ->limit(5)
            )
            ->heading('Recent Campaigns')
            ->paginated(false)
            ->columns([
                TextColumn::make('name')
                    ->weight('medium'),
                TextColumn::make('list.name')
                    ->label('List'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'sending', 'processing', 'queued', 'pending' => 'info',
                        'finished' => 'success',
                        'failed' => 'danger',
                        'scheduled' => 'warning',
                        'canceled' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('opened')
                    ->label('Opened')
                    ->state(fn (NewsletterCampaign $record) =>
                        NewsletterCampaignPixel::where('campaign_id', $record->id)->count()
                    )
                    ->color('gray')
                    ->alignCenter(),
                TextColumn::make('clicked')
                    ->label('Clicked')
                    ->state(fn (NewsletterCampaign $record) =>
                        NewsletterCampaignClickedLink::where('campaign_id', $record->id)->count()
                    )
                    ->color('gray')
                    ->alignCenter(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->color('gray'),
            ]);
    }
}
