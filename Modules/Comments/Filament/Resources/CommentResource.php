<?php

namespace Modules\Comments\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Comments\Models\Comment;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\Action;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Other';
    protected static ?string $recordTitleAttribute = 'comment_subject';
    protected static bool $shouldRegisterNavigation = true;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('comment_name')
                    ->label('Name')
                    ->required(),
                Forms\Components\TextInput::make('comment_email')
                    ->label('Email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('comment_website')
                    ->label('Website')
                    ->url(),
                Forms\Components\TextInput::make('comment_subject')
                    ->label('Subject'),
                Forms\Components\Textarea::make('comment_body')
                    ->label('Comment')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_moderated')
                    ->label('Approved')
                    ->default(false),
                Forms\Components\Toggle::make('is_spam')
                    ->label('Mark as Spam')
                    ->default(false),

                Forms\Components\Select::make('rel_type')
                    ->label('Related To')
                    ->options([
                        morph_name(\Modules\Content\Models\Content::class) => 'Content',
                    ])
                    ->searchable()
                    ->reactive()
                    ->dehydrated(true)
                    ->afterStateUpdated(function (callable $set, $state) {
                        if ($state === morph_name(\Modules\Content\Models\Content::class)) {
                            $set('rel_id', null);
                        }
                    }),
                Forms\Components\Select::make('rel_id')
                    ->label('Related ID')
                    ->relationship('content', 'title')
                    ->searchable()
                    ->preload()
                    ->dehydrated(true)
                    ->visible(fn($get) => $get('rel_type') === morph_name(\Modules\Content\Models\Content::class))
                    ->required(fn($get) => $get('rel_type') === morph_name(\Modules\Content\Models\Content::class)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('comment_name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('comment_email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('comment_body')
                    ->label('Comment')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('content.title')
                    ->label('Content')
                    ->searchable()
                    ->default('No post title'),
                Tables\Columns\IconColumn::make('is_moderated')
                    ->label('Approved')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_spam')
                    ->label('Spam')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_moderated')
                    ->label('Approved'),
                TernaryFilter::make('is_spam')
                    ->label('Spam'),
                SelectFilter::make('rel_type')
                    ->label('Related To')
                    ->options([
                        morph_name(\Modules\Content\Models\Content::class) => 'Content',

                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (Comment $record) {
                        $record->update([
                            'is_moderated' => 1,
                            'is_new' => 0,
                            'is_spam' => 0
                        ]);
                    })
                    ->visible(fn(Comment $record) => !$record->is_moderated),
                Tables\Actions\Action::make('spam')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('danger')
                    ->action(function (Comment $record) {
                        $record->update([
                            'is_spam' => 1,
                            'is_moderated' => 0
                        ]);
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update([
                                    'is_moderated' => 1,
                                    'is_new' => 0,
                                    'is_spam' => 0
                                ]);
                            });
                        }),
                    Tables\Actions\BulkAction::make('mark_as_spam')
                        ->label('Mark as Spam')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update([
                                    'is_spam' => 1,
                                    'is_moderated' => 0
                                ]);
                            });
                        })
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => \Modules\Comments\Filament\Resources\CommentResource\Pages\ListComments::route('/'),
            'create' => \Modules\Comments\Filament\Resources\CommentResource\Pages\CreateComment::route('/create'),
            'edit' => \Modules\Comments\Filament\Resources\CommentResource\Pages\EditComment::route('/{record}/edit'),
        ];
    }

    /**
     * Get the attributes that should be searchable globally.
     *
     * @return array
     */
    public static function getGloballySearchableAttributes(): array
    {
        return ['comment_name', 'comment_email', 'comment_subject', 'comment_body', 'content.title'];
    }

    /**
     * Get the title for the global search result.
     *
     * @param Model $record
     * @return string
     */
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->comment_subject ?: 'Comment by ' . $record->comment_name;
    }

    /**
     * Get the details for the global search result.
     *
     * @param Model $record
     * @return array
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Author' => $record->comment_name,
            'Email' => $record->comment_email,
            'Content' => $record->content?->title ?? 'N/A',
            'Comment' => \Illuminate\Support\Str::limit($record->comment_body, 50),
            'Status' => $record->is_moderated ? 'Approved' : ($record->is_spam ? 'Spam' : 'Pending'),
            'Date' => $record->created_at?->format('Y-m-d H:i'),
        ];
    }

    /**
     * Get the actions for the global search result.
     *
     * @param Model $record
     * @return array
     */
    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('edit')
                ->url(static::getUrl('edit', ['record' => $record])),
            Action::make('approve')
                ->visible(fn() => !$record->is_moderated && !$record->is_spam)
                ->url(static::getUrl('edit', ['record' => $record])),
        ];
    }
}
