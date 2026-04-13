<?php

declare(strict_types=1);

namespace Modules\Ai\Filament\Resources\McpClientResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Modules\Ai\Filament\Resources\McpClientResource;
use Modules\Ai\Services\Mcp\McpClientTokenManager;

class CreateMcpClient extends CreateRecord
{
    protected static string $resource = McpClientResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        unset($data['uuid']);

        if (blank($data['slug'] ?? null)) {
            unset($data['slug']);
        }

        return app(McpClientTokenManager::class)->createClient($data, auth()->user());
    }
}
