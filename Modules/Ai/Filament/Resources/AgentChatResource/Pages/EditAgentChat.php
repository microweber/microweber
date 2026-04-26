<?php

namespace Modules\Ai\Filament\Resources\AgentChatResource\Pages;

use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Modules\Ai\Filament\Resources\AgentChatResource;

class EditAgentChat extends EditRecord
{
    protected static string $resource = AgentChatResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Chat updated successfully';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                TagsInput::make('tags')
                    ->label('Tags')
                    ->placeholder('Add tags...')
                    // No `->separator(',')` — the AgentChat model casts
                    // `tags` to `array`, so the picker should store a
                    // real PHP/JSON array (the cast's wire format).
                    // Calling separator(',') would make TagsInput
                    // persist a comma-joined string, which the
                    // `array` cast can't round-trip cleanly (it would
                    // come back as `['tag1,tag3']` or as the raw
                    // string depending on the JSON decode path).
                    ->helperText('Add tags to categorize this chat'),

                Select::make('status')
                    ->required()
                    ->options([
                        'active' => 'Active',
                        'archived' => 'Archived',
                        'paused' => 'Paused',
                    ])
                    ->default('active'),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label('View Chat'),
            Actions\DeleteAction::make(),
        ];
    }
}
