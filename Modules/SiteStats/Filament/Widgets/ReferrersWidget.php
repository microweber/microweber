<?php

namespace Modules\SiteStats\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\SiteStats\Models\Sessions;

class ReferrersWidget extends BaseWidget
{
    protected static ?string $heading = 'Top Referrers';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    protected int $defaultPaginationPageOption = 5;

    public function getTableRecordKey(Model|array $record): string
    {
        if (is_array($record)) {
            return (string) ($record['referrer_domain_id'] ?? '');
        }
        return (string) ($record->referrer_domain_id ?? $record->getKey());
    }

    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->query(
                Sessions::query()
                    ->select(
                        'stats_sessions.referrer_domain_id',
                        'stats_referrers_domains.referrer_domain',
                        DB::raw('COUNT(DISTINCT stats_sessions.session_id) as session_count')
                    )
                    ->join('stats_referrers_domains', 'stats_sessions.referrer_domain_id', '=', 'stats_referrers_domains.id')
                    ->where('stats_sessions.updated_at', '>=', now()->subDays(30))
                    ->where('stats_sessions.referrer_domain_id', '>', 0)
                    ->groupBy('stats_sessions.referrer_domain_id', 'stats_referrers_domains.referrer_domain')
                    ->orderByDesc('session_count')
            )
            ->columns([
                TextColumn::make('referrer_domain')
                    ->label('Referrer')
                    ->limit(40)
                    ->searchable(),

                TextColumn::make('session_count')
                    ->label('Sessions')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('session_count', 'desc')
            ->paginated([5, 10, 25]);
    }
}
