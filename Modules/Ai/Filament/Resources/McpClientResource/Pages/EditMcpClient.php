<?php

declare(strict_types=1);

namespace Modules\Ai\Filament\Resources\McpClientResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Modules\Ai\Filament\Resources\McpClientResource;

class EditMcpClient extends EditRecord
{
    protected static string $resource = McpClientResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (blank($data['slug'] ?? null)) {
            unset($data['slug']);
        }

        $data['updated_by_user_id'] = auth()->id();

        $record->fill($data);
        $record->save();

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
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
            Actions\DeleteAction::make(),
        ];
    }
}
