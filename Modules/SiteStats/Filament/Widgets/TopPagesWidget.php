<?php

namespace Modules\SiteStats\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\SiteStats\Models\Log;

class TopPagesWidget extends BaseWidget
{
    protected static ?string $heading = 'Top Pages';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    protected int $defaultPaginationPageOption = 10;

    public function getTableRecordKey(Model|array $record): string
    {
        if (is_array($record)) {
            return (string) ($record['url_id'] ?? '');
        }
        return (string) ($record->url_id ?? $record->getKey());
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Log::query()
                    ->select(
                        'stats_visits_log.url_id',
                        'stats_urls.url',
                        'stats_urls.content_id',
                        DB::raw('SUM(stats_visits_log.view_count) as total_views'),
                        DB::raw('COUNT(DISTINCT stats_visits_log.session_id_key) as unique_sessions')
                    )
                    ->join('stats_urls', 'stats_visits_log.url_id', '=', 'stats_urls.id')
                    ->where('stats_visits_log.updated_at', '>=', now()->subDays(30))
                    ->groupBy('stats_visits_log.url_id', 'stats_urls.url', 'stats_urls.content_id')
                    ->orderByDesc('total_views')
            )
            ->columns([
                TextColumn::make('url')
                    ->label('Page')
                    ->formatStateUsing(function ($state) {
                        $slug = str_replace(site_url(), '', $state);
                        return $slug ?: '/';
                    })
                    ->limit(40)
                    ->tooltip(fn ($state) => $state),

                TextColumn::make('total_views')
                    ->label('Views')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('unique_sessions')
                    ->label('Visitors')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('total_views', 'desc')
            ->paginated([10, 25, 50]);
    }
}
