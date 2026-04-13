<?php

declare(strict_types=1);

namespace Modules\Ai\Filament\Resources\McpClientResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Ai\Filament\Resources\McpClientResource;

class ListMcpClients extends ListRecords
{
    protected static string $resource = McpClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New MCP Client'),
        ];
    }
}
