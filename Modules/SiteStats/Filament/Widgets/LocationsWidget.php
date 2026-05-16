<?php

namespace Modules\SiteStats\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\SiteStats\Models\Sessions;

class LocationsWidget extends BaseWidget
{
    protected static ?string $heading = 'Visitor Locations';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 1;

    protected int $defaultPaginationPageOption = 5;

    public function getTableRecordKey(Model|array $record): string
    {
        if (is_array($record)) {
            return (string) ($record['geoip_id'] ?? '');
        }
        return (string) ($record->geoip_id ?? $record->getKey());
    }

    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->query(
                Sessions::query()
                    ->select(
                        'stats_sessions.geoip_id',
                        'stats_geoip.country_code',
                        'stats_geoip.country_name',
                        DB::raw('COUNT(DISTINCT stats_sessions.session_id) as visitor_count')
                    )
                    ->join('stats_geoip', 'stats_sessions.geoip_id', '=', 'stats_geoip.id')
                    ->where('stats_sessions.updated_at', '>=', now()->subDays(30))
                    ->where('stats_sessions.geoip_id', '>', 0)
                    ->groupBy('stats_sessions.geoip_id', 'stats_geoip.country_code', 'stats_geoip.country_name')
                    ->orderByDesc('visitor_count')
            )
            ->columns([
                TextColumn::make('country_name')
                    ->label('Country')
                    ->searchable()
                    // task-2026-05-16-321ef4: stats_geoip rows for unresolved IPs
                    // are stored with literal `country_name='unknown'` /
                    // `country_code='unknown'` (lowercase strings, NOT null), so
                    // Filament's `->placeholder('Unknown')` never fires. Coerce
                    // those literals to a friendlier display string here.
                    ->formatStateUsing(fn ($state) => $state && strtolower((string) $state) !== 'unknown'
                        ? $state
                        : 'Unknown'),

                TextColumn::make('country_code')
                    ->label('Code')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state && strtolower((string) $state) !== 'unknown'
                        ? strtoupper((string) $state)
                        : '—'),

                TextColumn::make('visitor_count')
                    ->label('Visitors')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('visitor_count', 'desc')
            ->paginated([5, 10, 25]);
    }
}
