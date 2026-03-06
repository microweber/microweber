<?php

namespace Modules\Ai\Filament\Resources\AgentChatResource\Pages;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Modules\Ai\Filament\Resources\AgentChatResource;
use Modules\Ai\Models\AgentChatMessage;

class CreateAgentChat extends CreateRecord
{
    protected static string $resource = AgentChatResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter chat title'),

                RichEditor::make('initial_prompt')
                    ->label('Initial Prompt')
                    ->placeholder('Enter an initial message to start the conversation...')
                    ->helperText('This will be sent as the first message in the chat')
                    ->columnSpanFull(),
            ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set user_id to current user if not set
        if (!isset($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }

        // Store initial_prompt in metadata for later processing
        if (!empty($data['initial_prompt'])) {
            $data['metadata'] = array_merge($data['metadata'] ?? [], [
                'initial_prompt' => $data['initial_prompt'],
            ]);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        // Create the initial message if provided
        if (!empty($this->data['initial_prompt'])) {
            AgentChatMessage::create([
                'chat_id' => $this->record->id,
                'role' => 'user',
                'content' => $this->data['initial_prompt'],
                'agent_type' => $this->record->agent_type,
                'processed_at' => now(),
            ]);
        }
    }
}
