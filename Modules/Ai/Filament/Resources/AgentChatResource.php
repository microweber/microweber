<?php

namespace Modules\Ai\Filament\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Ai\Filament\Resources\AgentChatResource\Pages;
use Modules\Ai\Models\AgentChat;

class AgentChatResource extends Resource
{
    protected static ?string $model = AgentChat::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
      protected static string | \UnitEnum | null $navigationGroup = 'System Settings';

    protected static ?int $navigationSort = 1100;
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make('Chat Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter chat title'),

                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000)
                            ->placeholder('Optional description for this chat')
                            ->rows(3),

                        Forms\Components\Select::make('agent_type')
                            ->required()
                            ->options([
                                'general' => '🤖 General Assistant',
                                'customer' => '👥 Customer Service',
                                'shop' => '🛒 Shop Assistant',
                                'content' => '📝 Content Manager',
                                'media' => '🖼️ Media Manager',
                            ])
                            ->default('general')
                            ->helperText('Select the type of AI assistant for this chat'),

                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name ?: 'Unnamed User (#' . $record->id . ')')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Assign this chat to a specific user (optional)'),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->helperText('Active chats can receive new messages'),
                    ]),

                Forms\Components\Section::make('Metadata')
                    ->schema([
                        Forms\Components\KeyValue::make('metadata')
                            ->keyLabel('Setting')
                            ->valueLabel('Value')
                            ->helperText('Additional settings for this chat'),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->with(['user', 'messages'])
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\BadgeColumn::make('agent_type')
                    ->label('Agent Type')
                    ->colors([
                        'primary' => 'general',
                        'success' => 'customer',
                        'warning' => 'shop',
                        'info' => 'content',
                        'secondary' => 'media',
                    ])
                    ->formatStateUsing(function (string $state): string {
                        return match ($state) {
                            'general' => '🤖 General',
                            'customer' => '👥 Customer',
                            'shop' => '🛒 Shop',
                            'content' => '📝 Content',
                            'media' => '🖼️ Media',
                            default => $state,
                        };
                    }),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Assigned User')
                    ->getStateUsing(fn (AgentChat $record) => $record->user?->name ?: ($record->user ? 'Unnamed User (#' . $record->user->id . ')' : 'Not assigned'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('messages_count')
                    ->label('Messages')
                    ->counts('messages')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('last_message_at')
                    ->label('Last Activity')
                    ->getStateUsing(function (AgentChat $record): ?string {
                        $lastMessage = $record->getLastMessage();
                        return $lastMessage?->created_at?->diffForHumans();
                    })
                    ->sortable(query: function ($query, string $direction) {
                        return $query->withAggregate('messages as last_message_at', 'created_at', 'max')
                                    ->orderBy('last_message_at', $direction);
                    }),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('agent_type')
                    ->options([
                        'general' => 'General Assistant',
                        'customer' => 'Customer Service',
                        'shop' => 'Shop Assistant',
                        'content' => 'Content Manager',
                        'media' => 'Media Manager',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),

                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name ?: 'Unnamed User (#' . $record->id . ')')
                    ->searchable()
                    ->preload(),
            ])
->actions([
            EditAction::make(),
        ])
->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgentChats::route('/'),
            'create' => Pages\CreateAgentChat::route('/create'),
            'edit' => Pages\EditAgentChat::route('/{record}/edit'),
            'view' => Pages\ViewAgentChat::route('/{record}'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return config('modules.ai.enabled', false);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)
            ->whereHas('messages', function ($query) {
                $query->where('role', 'user')
                    ->whereNull('processed_at');
            })
            ->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'warning';
    }
}
