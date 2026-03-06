<?php

namespace Modules\Ai\Filament\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
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
                Section::make('Chat Information')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter chat title'),

                        Textarea::make('description')
                            ->maxLength(1000)
                            ->placeholder('Optional description for this chat')
                            ->rows(3),

                        Select::make('agent_type')
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

                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name ?: 'Unnamed User (#' . $record->id . ')')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Assign this chat to a specific user (optional)'),

                        Toggle::make('is_active')
                            ->default(true)
                            ->helperText('Active chats can receive new messages'),
                    ]),

                Section::make('Metadata')
                    ->schema([
                        KeyValue::make('metadata')
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
            ->modifyQueryUsing(fn ($query) => $query->with(['user', 'messages']))
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

            Filter::make('search')
                ->form([
                    TextInput::make('search')
                        ->label('Search')
                        ->placeholder('Search by title or description...'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when(
                            $data['search'] ?? null,
                            fn ($query, $search) => $query
                                ->where('title', 'like', "%{$search}%")
                                ->orWhere('description', 'like', "%{$search}%")
                        );
                }),

            Filter::make('created_at')
                ->form([
                    DatePicker::make('created_from')
                        ->label('Created From'),
                    DatePicker::make('created_until')
                        ->label('Created Until'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when(
                            $data['created_from'] ?? null,
                            fn ($query, $date) => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'] ?? null,
                            fn ($query, $date) => $query->whereDate('created_at', '<=', $date),
                        );
                }),
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
