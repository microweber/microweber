<?php

declare(strict_types=1);

namespace Modules\Ai\Filament\Resources\McpClientResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Ai\Filament\Resources\McpClientResource;
use Modules\Ai\Models\McpClientToken;
use Modules\Ai\Services\Mcp\McpClientTokenManager;

class McpClientTokensRelationManager extends RelationManager
{
    protected static string $relationship = 'tokens';

    protected static ?string $recordTitleAttribute = 'name';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('token_last_eight')
                    ->label('Last 8')
                    ->copyable(),
                Tables\Columns\TextColumn::make('abilities')
                    ->label('Scopes')
                    ->formatStateUsing(fn (mixed $state): string => is_array($state) ? implode(',', $state) : (string) $state)
                    ->separator(',')
                    ->badge()
                    ->limitList(3)
                    ->expandableLimitedList(),
                Tables\Columns\TextColumn::make('status')
                    ->state(fn (McpClientToken $record): string => $record->isRevoked() ? 'Revoked' : ($record->isExpired() ? 'Expired' : 'Active'))
                    ->badge()
                    ->color(fn (McpClientToken $record): string => $record->isRevoked() ? 'danger' : ($record->isExpired() ? 'warning' : 'success')),
                Tables\Columns\TextColumn::make('last_used_at')
                    ->label('Last used')
                    ->since()
                    ->placeholder('Never'),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->placeholder('No expiry'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                Action::make('issueToken')
                    ->label('Issue key')
                    ->icon('heroicon-o-key')
                    ->schema(McpClientResource::tokenFormSchema())
                    ->action(function (array $data): void {
                        $generatedToken = McpClientResource::issueTokenForClient($this->getOwnerRecord(), $data);
                        McpClientResource::notifyTokenIssued($generatedToken, $this->getOwnerRecord());
                    }),
            ])
            ->actions([
                \Filament\Actions\Action::make('rotateToken')
                    ->label('Rotate')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Rotate MCP token')
                    ->modalDescription(
                        'A fresh secret will be issued under the same client + name + abilities. '
                        . 'The current token will be revoked immediately. The replacement key is shown '
                        . 'once and cannot be recovered later.'
                    )
                    ->visible(fn (McpClientToken $record): bool => $record->isActive())
                    ->action(function (McpClientToken $record): void {
                        $generated = app(McpClientTokenManager::class)->rotateToken(
                            token: $record,
                            actor: auth()->user(),
                        );
                        McpClientResource::notifyTokenIssued($generated, $record->client);
                    }),
                \Filament\Actions\Action::make('revokeToken')
                    ->label('Revoke')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (McpClientToken $record): bool => $record->isActive())
                    ->action(function (McpClientToken $record): void {
                        app(McpClientTokenManager::class)->revokeToken($record, auth()->user(), 'Revoked from admin panel');
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([]),
            ]);
    }
}
