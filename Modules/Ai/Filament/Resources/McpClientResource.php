<?php

declare(strict_types=1);

namespace Modules\Ai\Filament\Resources;

use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Ai\Filament\Resources\McpClientResource\Pages;
use Modules\Ai\Filament\Resources\McpClientResource\RelationManagers\McpClientTokenEventsRelationManager;
use Modules\Ai\Filament\Resources\McpClientResource\RelationManagers\McpClientTokensRelationManager;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Services\Mcp\GeneratedMcpClientToken;
use Modules\Ai\Services\Mcp\McpAdminInspector;
use Modules\Ai\Services\Mcp\McpClientTokenManager;

class McpClientResource extends Resource
{
    protected static ?string $model = McpClient::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-key';

    protected static string | \UnitEnum | null $navigationGroup = 'System Settings';

    protected static ?int $navigationSort = 3010;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Client Details')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('slug')
                        ->maxLength(255)
                        ->helperText('Optional. Leave blank to auto-generate from the client name.'),
                    Toggle::make('is_active')
                        ->default(true),
                    TextInput::make('rate_limit_per_minute')
                        ->required()
                        ->numeric()
                        ->integer()
                        ->minValue(1)
                        ->default(60),
                    Textarea::make('description')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Access Policy')
                ->description(
                    'Each list applies a least-privilege filter on the calling token. '
                    . 'Selecting nothing in the database (NULL) is treated as "unrestricted"; '
                    . 'an explicit empty array denies everything. The form below requires '
                    . 'at least one selection so admin-created clients always carry an '
                    . 'audit-friendly explicit policy.'
                )
                ->schema([
                    CheckboxList::make('allowed_scopes')
                        ->label('Allowed scopes')
                        ->helperText('mcp:access is required for any call; mcp:admin is required for admin-only tools / modules.')
                        ->options(static::scopeOptions())
                        ->required()
                        ->columns(2)
                        ->bulkToggleable(),
                    CheckboxList::make('allowed_modules')
                        ->label('Allowed modules')
                        ->helperText('Modules the client may dispatch tool calls into (the prefix before the `.` in the tool name).')
                        ->options(static::moduleOptions())
                        ->required()
                        ->columns(2)
                        ->bulkToggleable(),
                    CheckboxList::make('allowed_tools')
                        ->label('Allowed tools')
                        ->helperText('Specific tool names this client may invoke. Bulk-toggle to grant the entire catalog.')
                        ->options(static::toolOptions())
                        ->required()
                        ->columns(2)
                        ->bulkToggleable(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // task-2026-05-26 / AI-1105 — exclude PHPUnit factory-created MCP clients.
            // Faker generates company names and slug patterns; real clients have
            // a non-null created_by_user_id or have been used (last_used_at).
            ->modifyQueryUsing(fn ($query) => $query
                ->withCount('tokens')
                ->where(function ($q) {
                    $q->whereNotNull('created_by_user_id')
                      ->orWhereNotNull('last_used_at');
                })
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('allowed_scopes')
                    ->label('Scopes')
                    ->formatStateUsing(fn (mixed $state): string => is_array($state) ? implode(',', $state) : (string) $state)
                    ->separator(',')
                    ->badge()
                    ->limitList(3)
                    ->expandableLimitedList(),
                Tables\Columns\TextColumn::make('tokens_count')
                    ->label('Keys')
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_used_at')
                    ->label('Last used')
                    ->since()
                    ->placeholder('Never'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active status'),
            ])
            ->actions([
                Tables\Actions\Action::make('testConnection')
                    ->label('Test')
                    ->icon('heroicon-o-signal')
                    ->action(fn (McpClient $record) => static::notifyConnectionHealth($record)),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Client Summary')
                ->schema([
                    TextEntry::make('uuid')
                        ->label('UUID')
                        ->copyable(),
                    TextEntry::make('slug')
                        ->copyable(),
                    TextEntry::make('is_active')
                        ->label('Status')
                        ->state(fn (McpClient $record): string => $record->isAvailable() ? 'Available' : 'Inactive')
                        ->badge()
                        ->color(fn (McpClient $record): string => $record->isAvailable() ? 'success' : 'warning'),
                    TextEntry::make('rate_limit_per_minute')
                        ->label('Rate limit / min'),
                    TextEntry::make('last_used_at')
                        ->label('Last used')
                        ->since()
                        ->placeholder('Never'),
                    TextEntry::make('description')
                        ->placeholder('No description provided.')
                        ->columnSpanFull(),
                ])
                ->columns(3),
            Section::make('Connection Health')
                ->schema([
                    TextEntry::make('connection_health')
                        ->label('Status')
                        ->state(fn (McpClient $record): string => app(McpAdminInspector::class)->connectionHealth($record)['summary'])
                        ->badge()
                        ->color(fn (McpClient $record): string => app(McpAdminInspector::class)->connectionHealth($record)['color'])
                        ->columnSpanFull(),
                    TextEntry::make('health_active_tokens')
                        ->label('Active keys')
                        ->state(fn (McpClient $record): int => app(McpAdminInspector::class)->connectionHealth($record)['active_tokens']),
                    TextEntry::make('health_visible_tools')
                        ->label('Visible tools')
                        ->state(fn (McpClient $record): int => app(McpAdminInspector::class)->connectionHealth($record)['visible_tools']),
                ])
                ->columns(2),
            Section::make('AI Provider Secret References')
                ->description('These checks only display reference and health state; secret values are never shown.')
                ->schema(static::providerSecretEntries())
                ->columns(2),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            McpClientTokensRelationManager::class,
            McpClientTokenEventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMcpClients::route('/'),
            'create' => Pages\CreateMcpClient::route('/create'),
            'view' => Pages\ViewMcpClient::route('/{record}'),
            'edit' => Pages\EditMcpClient::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return (bool) config('modules.ai.enabled', false);
    }

    public static function canAccess(): bool
    {
        return auth()->check() && ((auth()->user()?->can('manage_ai_settings') ?? false) || is_admin());
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::active()->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'success';
    }

    /**
     * @return array<int, \Filament\Forms\Components\Component>
     */
    public static function tokenFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->default('Primary key'),
            CheckboxList::make('abilities')
                ->label('Token scopes')
                ->options(static::scopeOptions())
                ->columns(2)
                ->bulkToggleable(),
            DateTimePicker::make('expires_at')
                ->seconds(false)
                ->native(false),
        ];
    }

    public static function issueTokenForClient(McpClient $client, array $data): GeneratedMcpClientToken
    {
        $manager = app(McpClientTokenManager::class);

        return $manager->issueToken(
            client: $client,
            name: (string) $data['name'],
            abilities: array_values(array_filter((array) ($data['abilities'] ?? $client->allowed_scopes ?? []))),
            expiresAt: filled($data['expires_at'] ?? null) ? Carbon::parse((string) $data['expires_at']) : null,
            actor: auth()->user(),
        );
    }

    public static function notifyTokenIssued(GeneratedMcpClientToken $generatedToken, McpClient $client): void
    {
        Notification::make()
            ->title("Issued MCP key for {$client->name}")
            ->body("Copy this key now. It will not be shown again:\n{$generatedToken->plainTextToken}")
            ->success()
            ->persistent()
            ->send();
    }

    public static function notifyConnectionHealth(McpClient $client): void
    {
        $report = app(McpAdminInspector::class)->connectionHealth($client);
        $notification = Notification::make()
            ->title("MCP health check: {$client->name}")
            ->body($report['summary']);

        if ($report['color'] === 'success') {
            $notification->success();
        } elseif ($report['color'] === 'danger') {
            $notification->danger();
        } else {
            $notification->warning();
        }

        $notification->send();
    }

    /**
     * @return array<string, string>
     */
    public static function scopeOptions(): array
    {
        return app(McpAdminInspector::class)->scopeOptions();
    }

    /**
     * @return array<string, string>
     */
    public static function moduleOptions(): array
    {
        return app(McpAdminInspector::class)->moduleOptions();
    }

    /**
     * @return array<string, string>
     */
    public static function toolOptions(): array
    {
        return app(McpAdminInspector::class)->toolOptions();
    }

    /**
     * @return array<int, TextEntry>
     */
    private static function providerSecretEntries(): array
    {
        return collect(app(McpAdminInspector::class)->providerSecretStatuses())
            ->map(function (array $status): TextEntry {
                return TextEntry::make('secret_' . $status['option_key'])
                    ->label($status['label'])
                    ->state(fn (): string => app(McpAdminInspector::class)->providerSecretSummary($status['option_key']))
                    ->badge()
                    ->color(fn (): string => app(McpAdminInspector::class)->providerSecretColor($status['option_key']));
            })
            ->values()
            ->all();
    }
}
