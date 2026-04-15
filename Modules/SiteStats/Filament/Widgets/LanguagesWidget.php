<?php

namespace Modules\SiteStats\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;
use Modules\SiteStats\Models\Sessions;

class LanguagesWidget extends BaseWidget
{
    protected static ?string $heading = 'Visitor Languages';

    protected static ?int $sort = 8;

    protected int|string|array $columnSpan = 1;

    protected int $defaultPaginationPageOption = 10;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Sessions::query()
                    ->select(
                        'stats_sessions.language',
                        DB::raw('COUNT(DISTINCT stats_sessions.session_id) as visitor_count')
                    )
                    ->where('stats_sessions.updated_at', '>=', now()->subDays(30))
                    ->whereNotNull('stats_sessions.language')
                    ->where('stats_sessions.language', '!=', '')
                    ->groupBy('stats_sessions.language')
                    ->orderByDesc('visitor_count')
            )
            ->columns([
                TextColumn::make('language')
                    ->label('Language')
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
