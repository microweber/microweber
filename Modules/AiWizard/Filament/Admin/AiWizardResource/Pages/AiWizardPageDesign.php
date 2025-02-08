<?php

namespace Modules\AiWizard\Filament\Admin\AiWizardResource\Pages;

use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Modules\AiWizard\Filament\Admin\AiWizardResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use League\CommonMark\CommonMarkConverter;
use Modules\Content\Models\Content;

class AiWizardPageDesign extends Page
{
    use InteractsWithRecord;

    protected static string $resource = AiWizardResource::class;

    protected static string $view = 'modules.aiwizard::filament.pages.ai-wizard-page-design';

    public ?array $data = [];


    public function mount(int|string $record): void
    {

        /** @var $record Content */
        $this->record = $this->resolveRecord($record);
        $this->data = $this->record->content_data ?? [];
        if(isset($this->record->content_data['processed_sections'])){
            $this->record->content_data['processed_sections'] = json_decode($this->record->content_data['processed_sections'], true);
        }

    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('content')
                    ->label('Generated Sections Content')
                    ->default(function () {
                        return $this->record->content ?? '';
                    })
                    ->disabled()
                    ->rows(10),
            ])
            ->statePath('data');
    }

    public function generateMarkdownAndHtml(): void
    {
        $sections = json_decode($this->data['sections'] ?? '[]', true);
        $aiService = app(\Modules\AiWizard\Services\Contracts\AiServiceInterface::class);
        $converter = new CommonMarkConverter();

        $processedSections = [];

        foreach ($sections as $section) {
            if (empty(trim($section))) continue;

            // Generate markdown for each section using AI
            $prompt = '';
            $prompt .= "Page id: {$this->record->id}\n";
            $prompt .= "Page title: {$this->record->title}\n";
            $prompt .= "Section type:\n\n" . $section;
            $prompt .= "Write text in markdown format with proper headings, lists, and formatting.\n";
            $prompt .= "Convert this content into well-formatted markdown with proper headings, lists, and formatting:\n\n" . $section;
            $markdown = $aiService->generateContent($prompt, [
                'model' => $this->record->content_data['ai_model'] ?? 'gpt-3.5-turbo',
                'temperature' => 0.7,
            ]);

            // Convert markdown to HTML
            $html = $converter->convert($markdown)->getContent();

            $processedSections[] = [
                'original' => $section,
                'markdown' => $markdown,
                'html' => $html,
            ];
        }

        // Update the record with the processed content
        $this->record->update([
            'content_data' => array_merge($this->record->content_data ?? [], [
                'processed_sections' => $processedSections,
                'processed_at' => now(),
            ]),
            'content' => collect($processedSections)->pluck('html')->join("\n\n"), // Store combined HTML
        ]);

        Notification::make()
            ->title('Content processed successfully')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('process')
                ->label('Process Content')
                ->action('generateMarkdownAndHtml')
                ->color('primary'),

            Action::make('back')
                ->label('Back to Edit')
                ->url(fn() => $this->getResource()::getUrl('edit', ['record' => $this->record]))
                ->color('gray'),
        ];
    }
}
