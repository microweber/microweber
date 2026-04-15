<?php

namespace Modules\SiteStats\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Modules\SiteStats\Models\Sessions;

class RecentVisitorsWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent Visitors';

    protected static ?int $sort = 7;

    protected int|string|array $columnSpan = 'full';

    protected int $defaultPaginationPageOption = 15;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Sessions::query()
                    ->select(
                        'stats_sessions.*',
                        'stats_browser_agents.browser',
                        'stats_browser_agents.platform',
                        'stats_geoip.country_code',
                        'stats_geoip.country_name'
                    )
                    ->leftJoin('stats_browser_agents', 'stats_sessions.browser_id', '=', 'stats_browser_agents.id')
                    ->leftJoin('stats_geoip', 'stats_sessions.geoip_id', '=', 'stats_geoip.id')
                    ->where('stats_sessions.updated_at', '>=', now()->subDays(7))
                    ->orderByDesc('stats_sessions.updated_at')
            )
            ->columns([
                TextColumn::make('updated_at')
                    ->label('Last Seen')
                    ->dateTime('M j, H:i')
                    ->sortable(),

                TextColumn::make('country_name')
                    ->label('Country')
                    ->placeholder('Unknown'),

                TextColumn::make('browser')
                    ->label('Browser')
                    ->placeholder('Unknown'),

                TextColumn::make('platform')
                    ->label('Platform')
                    ->badge()
                    ->placeholder('Unknown'),

                TextColumn::make('language')
                    ->label('Language')
                    ->placeholder('--'),
            ])
            ->defaultSort('updated_at', 'desc')
            ->paginated([15, 25, 50]);
    }
}
