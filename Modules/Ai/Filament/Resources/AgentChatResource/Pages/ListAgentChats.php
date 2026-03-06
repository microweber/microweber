<?php

namespace Modules\Ai\Filament\Resources\AgentChatResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Ai\Filament\Resources\AgentChatResource;

class ListAgentChats extends ListRecords
{
    protected static string $resource = AgentChatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Chat')
                ->icon('heroicon-o-plus'),
        ];
    }
}
