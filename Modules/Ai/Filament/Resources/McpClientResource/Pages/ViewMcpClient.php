<?php

declare(strict_types=1);

namespace Modules\Ai\Filament\Resources\McpClientResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Modules\Ai\Filament\Resources\McpClientResource;

class ViewMcpClient extends ViewRecord
{
    protected static string $resource = McpClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('issueToken')
                ->label('Issue key')
                ->icon('heroicon-o-key')
                ->schema(McpClientResource::tokenFormSchema())
                ->action(function (array $data): void {
                    $generatedToken = McpClientResource::issueTokenForClient($this->record, $data);
                    McpClientResource::notifyTokenIssued($generatedToken, $this->record);
                }),
            Actions\Action::make('testConnection')
                ->label('Test connection')
                ->icon('heroicon-o-signal')
                ->action(function (): void {
                    McpClientResource::notifyConnectionHealth($this->record);
                }),
        ];
    }
}
