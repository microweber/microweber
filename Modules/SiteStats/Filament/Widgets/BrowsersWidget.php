<?php

namespace Modules\SiteStats\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\SiteStats\Models\Sessions;

class BrowsersWidget extends BaseWidget
{
    protected static ?string $heading = 'Browsers & Platforms';

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 1;

    protected int $defaultPaginationPageOption = 10;

    public function getTableRecordKey(Model|array $record): string
    {
        if (is_array($record)) {
            return (string) ($record['browser_id'] ?? '');
        }
        return (string) ($record->browser_id ?? $record->getKey());
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Sessions::query()
                    ->select(
                        'stats_sessions.browser_id',
                        'stats_browser_agents.browser',
                        'stats_browser_agents.platform',
                        DB::raw('COUNT(DISTINCT stats_sessions.session_id) as visitor_count')
                    )
                    ->join('stats_browser_agents', 'stats_sessions.browser_id', '=', 'stats_browser_agents.id')
                    ->where('stats_sessions.updated_at', '>=', now()->subDays(30))
                    ->where('stats_sessions.browser_id', '>', 0)
                    ->groupBy('stats_sessions.browser_id', 'stats_browser_agents.browser', 'stats_browser_agents.platform')
                    ->orderByDesc('visitor_count')
            )
            ->columns([
                TextColumn::make('browser')
                    ->label('Browser')
                    ->searchable(),

                TextColumn::make('platform')
                    ->label('Platform')
                    ->badge()
                    ->searchable(),

                TextColumn::make('visitor_count')
                    ->label('Visitors')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('visitor_count', 'desc')
            ->paginated([10, 25, 50]);
    }
}
