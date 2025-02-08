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
            Actions\Action::make('regenerate')
                ->label('Regenerate with AI')
                ->action(function () {
                    $data = $this->data;

                    // Get the AI service
                    $aiService = app(AiServiceInterface::class);

                    // Prepare the prompt for AI
                    $prompt = "Create website content for a page with the following details:\n";
                    $prompt .= "Title: {$data['title']}\n";
                    $prompt .= "Description: {$data['description']}\n";
                    $prompt .= "Sections to include: " . implode(', ', $data['sections']) . "\n";
                    $prompt .= "Tone: {$data['tone']}\n";

                    // Generate new content using AI
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

                    // Update the record with new content
                    $this->record->update([
                        'content' => $generatedContent,
                        'content_data' => [
                            'ai_generated' => true,
                            'ai_model' => $data['ai_model'],
                            'tone' => $data['tone'],
                            'ai_content' => $generatedContent,
                            'sections' => $data['sections'],
                            'regenerated_at' => now(),
                        ],
                    ]);
                    Notification::make()
                        ->title('Content regenerated successfully')
                        ->success()
                        ->send();
                })
                ->icon('heroicon-o-sparkles')
                ->color('primary'),

            Actions\DeleteAction::make(),

            Actions\Action::make('design')
                ->label('Page Design')
                ->url(fn() => $this->getResource()::getUrl('design', ['record' => $this->record]))
                ->icon('heroicon-o-pencil-square')
                ->color('success'),

            Actions\Action::make('view')
                ->url(fn() => $this->record->link())
                ->openUrlInNewTab()
                ->icon('heroicon-o-eye'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Keep track of AI generation details in content_data
        $data['content_data'] = array_merge(
            $this->record->content_data ?? [],
            [
                'ai_generated' => true,
                'ai_model' => $data['ai_model'],
                'tone' => $data['tone'],
                'sections' => $data['sections'],
                'updated_at' => now(),
            ]
        );

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
