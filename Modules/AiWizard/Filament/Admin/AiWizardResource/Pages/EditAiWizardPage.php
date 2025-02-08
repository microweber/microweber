<?php

namespace Modules\AiWizard\Filament\Admin\AiWizardResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Modules\AiWizard\Filament\Admin\AiWizardResource;
use Modules\Ai\Services\Contracts\AiServiceInterface;

class EditAiWizardPage extends EditRecord
{
    protected static string $resource = AiWizardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('design')
                ->label('Page Design')
                ->url(fn() => $this->getResource()::getUrl('design', ['record' => $this->record]))
                ->icon('heroicon-o-pencil-square')
                ->color('success'),

            Actions\Action::make('view')
                ->url(fn() => $this->record->link())
                ->openUrlInNewTab()
                ->icon('heroicon-o-eye'),

            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return [
            'title' => $data['title'],
            'description' => $data['description'],
            'is_active' => $data['is_active'] ?? 1,
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
