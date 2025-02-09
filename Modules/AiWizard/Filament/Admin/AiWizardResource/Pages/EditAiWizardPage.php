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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Extract existing layouts from content
        $layouts = [];
        if (!empty($data['content'])) {
            preg_match_all('/<module type="layouts" template="([^"]+)"/', $data['content'], $matches);
            if (!empty($matches[1])) {
                // Group layouts by their categories using the module_templates function
                $allLayouts = module_templates('layouts');
                foreach ($matches[1] as $skin) {
                    foreach ($allLayouts as $layout) {
                        if ($layout['layout_file'] === $skin) {
                            $category = $layout['category'] ?? 'All';
                            $layouts[$category][] = $skin;
                        }
                    }
                }
            }
        }

        $data['layouts'] = $layouts;
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Process selected layouts
        $selectedLayouts = collect($data['layouts'] ?? [])->flatMap(function ($layouts) {
            return $layouts;
        })->filter()->values();

        // Generate content with selected layouts
        $content = '';
        foreach ($selectedLayouts as $layout) {
            $uniqueId = 'layout_' . uniqid();
            $content .= '<module type="layouts" template="' . $layout . '" id="' . $uniqueId . '" />' . PHP_EOL;
        }

        return [
            'title' => $data['title'],
            'description' => $data['description'],
            'is_active' => $data['is_active'] ?? 1,
            'content' => $content,
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
