<?php

namespace Modules\SiteStats\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\SiteStats\Models\Sessions;

class LanguagesWidget extends BaseWidget
{
    protected static ?string $heading = 'Visitor Languages';

    protected static ?int $sort = 8;

    protected int|string|array $columnSpan = 1;

    protected int $defaultPaginationPageOption = 5;

    public function getTableRecordKey(Model|array $record): string
    {
        if (is_array($record)) {
            return (string) ($record['language'] ?? '');
        }
        return (string) ($record->language ?? $record->getKey());
    }

    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->query(
                Sessions::query()
                    ->select(
                        'stats_sessions.language',
                        DB::raw('COUNT(DISTINCT stats_sessions.session_id) as visitor_count')
                    )
                    ->where('stats_sessions.updated_at', '>=', now()->subDays(30))
                    ->whereNotNull('stats_sessions.language')
                    ->where('stats_sessions.language', '!=', '')
                    // task-2026-05-16-321ef4: some clients (and historical
                    // imports) store a literal `'0'` in the language column —
                    // the existing `!= ''` filter doesn't catch it, and it
                    // renders as a blank-looking row. Also drop tokens shorter
                    // than 2 chars since those can't be a real BCP-47 tag.
                    ->where('stats_sessions.language', '!=', '0')
                    ->whereRaw('CHAR_LENGTH(stats_sessions.language) >= 2')
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
            ->paginated([5, 10, 25]);
    }
}
