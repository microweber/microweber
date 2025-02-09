<?php

namespace Modules\AiWizard\Livewire;

use Livewire\Component;
use Modules\Ai\Services\Contracts\AiServiceInterface;
use League\CommonMark\CommonMarkConverter;
use Filament\Notifications\Notification;

class SectionProcessor extends Component
{
    public $record;
    public $sections = [];
    public $currentSection = null;
    public $processingStatus = [];
    public $overallProgress = 0;
    public $showSectionSelector = false;
    public $selectedSections = [];
    public $availableSections = [
        'header' => 'Header Section',
        'content' => 'Main Content',
        'features' => 'Features Section',
        'pricing' => 'Pricing Section',
        'about' => 'About Section',
        'call_to_action' => 'Call to Action Section',
        'faq' => 'FAQ Section',
        'testimonials' => 'Testimonials Section',
        'contact' => 'Contact Section',
    ];

    public function mount($record)
    {
        $this->record = $record;

        // Check if sections are passed via URL parameters
        $urlSections = request()->query('sections', []);
        if (!empty($urlSections)) {
            $this->selectedSections = is_array($urlSections) ? $urlSections : explode(',', $urlSections);
            $this->generateInitialContent();
        } else {
            // Check if we have existing sections
            $this->initializeSections();
            $this->showSectionSelector = true;
            // If no sections, show the selector
            if (empty($this->sections)) {
                $this->showSectionSelector = true;
                $this->selectedSections = ['content']; // Default selections
            }
        }
    }

    public function confirmSectionSelection()
    {
        if (empty($this->selectedSections)) {
            Notification::make()
                ->title('Please select at least one section')
                ->warning()
                ->send();
            return;
        }

        $this->generateInitialContent();
        $this->showSectionSelector = false;
    }

    protected function generateInitialContent()
    {
        $aiService = app(AiServiceInterface::class);

        // Prepare the prompt for AI
        $prompt = "Create website content for a page with the following details:\n";
        $prompt .= "Title: {$this->record->title}\n";
        $prompt .= "Description: {$this->record->description}\n";
        $prompt .= "Sections to include: " . implode(', ', $this->selectedSections) . "\n";

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
            'model' => $this->record->content_data['ai_model'] ?? 'gpt-3.5-turbo',
            'temperature' => 0.7,
        ]);

        $generatedContent = is_string($response) ? $response : $response['content'];

        // Store the generated content
        $this->record->update([
            'content_data' => array_merge($this->record->content_data ?? [], [
                'ai_content' => $generatedContent,
                'sections' => $this->selectedSections,
            ]),
        ]);

        // Initialize sections with the generated content
        $this->initializeSections();
    }

    protected function initializeSections()
    {
        $content = $this->record->content_data['ai_content'] ?? '';
        $rawSections = explode("\n\n", $content);

        foreach ($rawSections as $index => $content) {
            if (empty(trim($content))) continue;

            $uniqueId = 'section_' . uniqid();
            $sectionName = $this->guessSectionName($content);

            $this->sections[] = [
                'id' => $index + 1,
                'unique_id' => $uniqueId,
                'name' => $sectionName,
                'content' => $content,
                'markdown' => '',
                'html' => '',
                'status' => 'pending', // pending, processing, completed, error
                'field_name' => 'layout-skin-1-' . $uniqueId
            ];

            $this->processingStatus[$index] = 0;
        }
    }

    protected function guessSectionName($content)
    {
        $firstLine = strtok($content, "\n");
        $name = preg_match('/^(header|content|features|testimonials|contact)/i', $firstLine, $matches)
            ? ucfirst($matches[1])
            : 'Section ' . (count($this->sections) + 1);
        return $name;
    }

    public function processSection($index)
    {
        $this->currentSection = $index;
        $this->sections[$index]['status'] = 'processing';
        $this->processingStatus[$index] = 25;

        try {
            $aiService = app(AiServiceInterface::class);
            $converter = new CommonMarkConverter();

            // Generate markdown
            $this->processingStatus[$index] = 50;
            $messages = [
                [
                    'role' => 'system',
                    'content' => 'You are a professional content formatter that converts text into well-formatted markdown.'
                ],
                [
                    'role' => 'user',
                    'content' => "Convert this content into well-formatted markdown with proper headings, lists, and formatting:\n\n" .
                        $this->sections[$index]['content']
                ]
            ];

            $response = $aiService->sendToChat($messages, [
                'model' => $this->record->content_data['ai_model'] ?? 'gpt-3.5-turbo',
                'temperature' => 0.7,
            ]);

            $markdown = is_string($response) ? $response : $response['content'];

            $this->sections[$index]['markdown'] = $markdown;
            $this->processingStatus[$index] = 75;

            // Convert to HTML
            $html = $converter->convert($markdown)->getContent();
            $this->sections[$index]['html'] = $html;

            $this->sections[$index]['status'] = 'completed';
            $this->processingStatus[$index] = 100;

            //emit reloadIframePreview

            $this->dispatch('reloadIframePreview');

            // Update overall progress
            $this->updateOverallProgress();

            // Save progress
            $this->saveProgress();

            Notification::make()
                ->title("Section {$this->sections[$index]['name']} processed successfully")
                ->success()
                ->send();

        } catch (\Exception $e) {
            $this->sections[$index]['status'] = 'error';
            Notification::make()
                ->title("Error processing section {$this->sections[$index]['name']}")
                ->danger()
                ->body($e->getMessage())
                ->send();
        }

        $this->currentSection = null;
    }

    protected function updateOverallProgress()
    {
        $total = count($this->sections);
        $completed = count(array_filter($this->sections, fn($s) => $s['status'] === 'completed'));
        $this->overallProgress = $total > 0 ? round(($completed / $total) * 100) : 0;
    }

    protected function saveProgress()
    {
        $processedSections = array_map(function ($section) {
            if ($section['status'] !== 'completed') {
                return null;
            }

            // Create the module wrapper
            $moduleHtml = '<module type="layouts" skin="content/skin-1" id="' . $section['unique_id'] . '" />';

            // Save the section content using ContentManager
            app()->content_manager->save_content_field([
                'field' => $section['field_name'],
                'value' => $section['html'],
                'rel_type' => 'module',
                'rel_id' => 0
            ]);

            return [
                'name' => $section['name'],
                'original' => $section['content'],
                'markdown' => $section['markdown'],
                'html' => $moduleHtml,
                'field_name' => $section['field_name'],
                'unique_id' => $section['unique_id']
            ];
        }, $this->sections);

        // Filter out null values from unprocessed sections
        $processedSections = array_filter($processedSections);

        $this->record->update([
            'content_data' => array_merge($this->record->content_data ?? [], [
                'processed_sections' => $processedSections,
                'processed_at' => now(),
            ]),
            'content' => collect($processedSections)->pluck('html')->join("\n\n"),
        ]);
    }

    public function processAll()
    {
        foreach ($this->sections as $index => $section) {
            if ($section['status'] !== 'completed') {
                $this->processSection($index);
            }
        }
    }

    public function render()
    {
        return view('modules.aiwizard::livewire.section-processor');
    }
}
