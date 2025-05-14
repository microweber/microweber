<?php

namespace Modules\AiWizard\Filament\Admin\AiWizardResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\AiWizard\Filament\Admin\AiWizardResource;

class CreateAiWizardPage extends CreateRecord
{
    protected static string $resource = AiWizardResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Process selected layouts
        $selectedLayouts = collect($data['layouts'] ?? [])->flatMap(function ($layouts) {
            return $layouts;
        })->filter()->values();

        // Generate content with selected layouts
        $content = '';
        $i = 0;
        foreach ($selectedLayouts as $layout) {
            $uniqueId = 'layout_section_' . uniqid() . '_' . ++$i;
            $content .= '<module type="layouts" template="' . $layout . '" id="' . $uniqueId . '" />' . PHP_EOL;
        }

        return [
            'title' => $data['title'],
            'content_type' => 'page',
            'description' => $data['description'],
            'is_active' => $data['is_active'] ?? 1,
            'content' => $content,
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('design', ['record' => $this->record]);
    }
}
