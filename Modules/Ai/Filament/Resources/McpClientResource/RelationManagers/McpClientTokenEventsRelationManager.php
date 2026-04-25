<?php

declare(strict_types=1);

namespace Modules\Ai\Filament\Resources\McpClientResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class McpClientTokenEventsRelationManager extends RelationManager
{
    protected static string $relationship = 'tokenEvents';

    /**
     * Action → badge colour mapping. Keeps the events list readable
     * at a glance — denials and rate-limits stand out in red, normal
     * traffic stays neutral, lifecycle events (issuance, rotation,
     * revocation) use accent colours so an operator can scan for
     * anomalies without reading every row.
     */
    private const ACTION_COLOR_MAP = [
        // Hot-path traffic
        'token.used' => 'gray',

        // Denials / problems
        'token.denied' => 'danger',
        'token.rate_limited' => 'danger',
        'token.unauthorized' => 'danger',

        // Lifecycle
        'token.issued' => 'success',
        'token.rotated' => 'warning',
        'token.revoked' => 'danger',
        'client.created' => 'success',
        'client.updated' => 'warning',
    ];

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('action')
                    ->badge()
                    ->color(fn (string $state): string => self::ACTION_COLOR_MAP[$state] ?? 'gray')
                    ->searchable(),
                Tables\Columns\TextColumn::make('token.name')
                    ->label('Key')
                    ->placeholder('Client-level event'),
                Tables\Columns\TextColumn::make('actor.email')
                    ->label('Actor')
                    ->placeholder('System'),
                Tables\Columns\TextColumn::make('metadata')
                    ->label('Detail')
                    ->state(fn ($record): string => $this->summariseMetadata((array) ($record->metadata ?? [])))
                    ->limit(80)
                    ->tooltip(fn ($record): string => $this->summariseMetadata((array) ($record->metadata ?? [])))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->placeholder('N/A')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Occurred')
                    ->since(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options(array_combine(
                        array_keys(self::ACTION_COLOR_MAP),
                        array_keys(self::ACTION_COLOR_MAP),
                    ))
                    ->multiple(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50, 100])
            ->striped();
    }

    /**
     * Compact one-line representation of the metadata JSON column.
     * Picks the most operationally-useful keys (reason, denial,
     * rate-limited info) and renders them as `key=value` pairs so
     * an operator can scan denial reasons at a glance without
     * expanding the row.
     */
    private function summariseMetadata(array $metadata): string
    {
        if ($metadata === []) {
            return '';
        }

        $priorityKeys = ['reason', 'denial', 'tool', 'token_name', 'rate_limited', 'limit', 'attempts'];
        $parts = [];
        foreach ($priorityKeys as $key) {
            if (! array_key_exists($key, $metadata)) {
                continue;
            }
            $value = $metadata[$key];
            if (is_array($value)) {
                $value = implode(',', array_map(static fn ($v) => (string) $v, $value));
            }
            $parts[] = $key . '=' . (string) $value;
        }

        if ($parts === []) {
            // No priority key matched — fall back to the first
            // few JSON keys so the row isn't blank.
            foreach ($metadata as $k => $v) {
                if (is_scalar($v)) {
                    $parts[] = $k . '=' . (string) $v;
                }
                if (count($parts) >= 3) {
                    break;
                }
            }
        }

        return implode(' ', $parts);
    }
}
