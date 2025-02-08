<?php

namespace Modules\AiWizard\Filament\Admin\AiWizardResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\AiWizard\Filament\Admin\AiWizardResource;
use Modules\Ai\Services\Contracts\AiServiceInterface;

class CreateAiWizardPage extends CreateRecord
{
    protected static string $resource = AiWizardResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return [
            'title' => $data['title'],
            'content_type' => 'page',
            'description' => $data['description'],
            'is_active' => $data['is_active'] ?? 1,
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('design', ['record' => $this->record]);
    }
}
