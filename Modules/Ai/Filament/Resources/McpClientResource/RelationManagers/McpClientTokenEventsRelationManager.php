<?php

declare(strict_types=1);

namespace Modules\Ai\Filament\Resources\McpClientResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class McpClientTokenEventsRelationManager extends RelationManager
{
    protected static string $relationship = 'tokenEvents';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('action')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('token.name')
                    ->label('Key')
                    ->placeholder('Client-level event'),
                Tables\Columns\TextColumn::make('actor.email')
                    ->label('Actor')
                    ->placeholder('System'),
                Tables\Columns\TextColumn::make('ip_address')
                    ->placeholder('N/A')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Occurred')
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}
