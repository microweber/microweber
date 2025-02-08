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
        // Get the AI service
        $aiService = app(AiServiceInterface::class);

        // Prepare the prompt for AI
        $prompt = "Create website content for a page with the following details:\n";
        $prompt .= "Title: {$data['title']}\n";
        $prompt .= "Description: {$data['description']}\n";
        $prompt .= "Sections to include: " . implode(', ', $data['sections']) . "\n";
        $prompt .= "Tone: {$data['tone']}\n";

        // Generate content using AI
        $messages = [
            [
                'role' => 'system',
                'content' => 'You are a professional website content creator that generates well-structured content for web pages.'
            ],
            [
                'role' => 'user',
                'content' => $prompt
            ]
        ];

        $response = $aiService->sendToChat($messages, [
            'model' => $data['ai_model'] ?? 'gpt-3.5-turbo',
            'temperature' => 0.7,
        ]);

        $generatedContent = is_string($response) ? $response : $response['content'];

        // Prepare the data for saving
        return [
            'title' => $data['title'],
            'content_type' => 'page',
            'description' => $data['description'],
            'content' => $generatedContent,
            'is_active' => $data['is_active'] ?? 1,
            // Store AI generation details in content_data
            'content_data' => [
                'ai_generated' => true,
                'ai_model' => $data['ai_model'],
                'sections' => $data['sections'],

                'ai_content' => $generatedContent,
                'tone' => $data['tone'],
            ],
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('design', ['record' => $this->record]);
    }
}
