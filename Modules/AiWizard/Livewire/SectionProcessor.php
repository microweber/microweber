<?php

namespace Modules\AiWizard\Livewire;

use Livewire\Component;
use Modules\Ai\Services\Contracts\AiServiceInterface;
use League\CommonMark\CommonMarkConverter;
use Filament\Notifications\Notification;

class SectionProcessor extends Component
{
    public $record;
    public $layouts = [];
    public $currentLayout = null;
    public $processingStatus = [];
    public $overallProgress = 0;
    public $selectedLayouts = [];

    public function mount($record)
    {
        $this->record = $record;

        // Initialize layouts from content_data if available
        if (!empty($this->record->content_data['layouts'])) {
            $this->selectedLayouts = $this->record->content_data['layouts'];
        }

        $this->initializeLayouts();
    }

    protected function initializeLayouts()
    {
        // Get all available layouts
        $allLayouts = module_templates('layouts');

        foreach ($this->selectedLayouts as $index => $layoutFile) {
            $layout = collect($allLayouts)->first(function ($item) use ($layoutFile) {
                return $item['layout_file'] === $layoutFile;
            });

            if (!$layout) continue;

            $uniqueId = 'layout_' . uniqid();

            $this->layouts[] = [
                'id' => $index + 1,
                'unique_id' => $uniqueId,
                'name' => $layout['name'],
                'layout_file' => $layout['layout_file'],
                'category' => $layout['category'] ?? 'Other',
                'content' => '',
                'markdown' => '',
                'html' => '',
                'status' => 'pending',
                'field_name' => 'layout-' . $layout['layout_file'] . '-' . $uniqueId
            ];

            $this->processingStatus[$index] = 0;
        }
    }

    public function processLayout($index)
    {
        $this->currentLayout = $index;
        $this->layouts[$index]['status'] = 'processing';
        $this->processingStatus[$index] = 25;

        try {
            $aiService = app(AiServiceInterface::class);
            $converter = new CommonMarkConverter();

            // Generate content for the layout
            $this->processingStatus[$index] = 50;
            $messages = [
                [
                    'role' => 'system',
                    'content' => 'You are a professional content creator that generates content suitable for website layouts.'
                ],
                [
                    'role' => 'user',
                    'content' => "Create content for a {$this->layouts[$index]['name']} layout section for a page with the following details:\n" .
                        "Title: {$this->record->title}\n" .
                        "Description: {$this->record->description}\n" .
                        "Layout Category: {$this->layouts[$index]['category']}"
                ]
            ];

            $response = $aiService->sendToChat($messages, [
                'model' => $this->record->content_data['ai_model'] ?? 'gpt-3.5-turbo',
                'temperature' => 0.7,
            ]);

            $generatedContent = is_string($response) ? $response : $response['content'];
            $this->layouts[$index]['content'] = $generatedContent;

            // Convert to markdown
            $messages = [
                [
                    'role' => 'system',
                    'content' => 'You are a professional content formatter that converts text into well-formatted markdown.'
                ],
                [
                    'role' => 'user',
                    'content' => "Convert this content into well-formatted markdown:\n\n" . $generatedContent
                ]
            ];

            $response = $aiService->sendToChat($messages, [
                'model' => $this->record->content_data['ai_model'] ?? 'gpt-3.5-turbo',
                'temperature' => 0.7,
            ]);

            $markdown = is_string($response) ? $response : $response['content'];
            $this->layouts[$index]['markdown'] = $markdown;
            $this->processingStatus[$index] = 75;

            // Convert to HTML
            $html = $converter->convert($markdown)->getContent();
            $this->layouts[$index]['html'] = $html;

            $this->layouts[$index]['status'] = 'completed';
            $this->processingStatus[$index] = 100;

            //emit reloadIframePreview

            $this->dispatch('reloadIframePreview');

            // Update overall progress
            $this->updateOverallProgress();

            // Save progress
            $this->saveProgress();

            Notification::make()
                ->title("Layout {$this->layouts[$index]['name']} processed successfully")
                ->success()
                ->send();

        } catch (\Exception $e) {
            $this->layouts[$index]['status'] = 'error';
            Notification::make()
                ->title("Error processing layout {$this->layouts[$index]['name']}")
                ->danger()
                ->body($e->getMessage())
                ->send();
        }

        $this->currentLayout = null;
    }

    protected function updateOverallProgress()
    {
        $total = count($this->layouts);
        $completed = count(array_filter($this->layouts, fn($l) => $l['status'] === 'completed'));
        $this->overallProgress = $total > 0 ? round(($completed / $total) * 100) : 0;
    }

    protected function saveProgress()
    {
        $processedLayouts = array_map(function ($layout) {
            if ($layout['status'] !== 'completed') {
                return null;
            }

            // Create the module wrapper
            $moduleHtml = '<module type="layouts" template="' . $layout['layout_file'] . '" id="' . $layout['unique_id'] . '" />';

            // Save the layout content using ContentManager
            app()->content_manager->save_content_field([
                'field' => $layout['field_name'],
                'value' => $layout['html'],
                'rel_type' => 'module',
                'rel_id' => 0
            ]);

            return [
                'name' => $layout['name'],
                'layout_file' => $layout['layout_file'],
                'category' => $layout['category'],
                'original' => $layout['content'],
                'markdown' => $layout['markdown'],
                'html' => $moduleHtml,
                'field_name' => $layout['field_name'],
                'unique_id' => $layout['unique_id']
            ];
        }, $this->layouts);

        // Filter out null values from unprocessed layouts
        $processedLayouts = array_filter($processedLayouts);

        $this->record->update([
            'content_data' => array_merge($this->record->content_data ?? [], [
                'processed_layouts' => $processedLayouts,
                'processed_at' => now(),
            ]),
            'content' => collect($processedLayouts)->pluck('html')->join("\n\n"),
        ]);
    }

    public function processAll()
    {
        foreach ($this->layouts as $index => $layout) {
            if ($layout['status'] !== 'completed') {
                $this->processLayout($index);
            }
        }
    }

    public function render()
    {
        return view('modules.aiwizard::livewire.section-processor');
    }
}
