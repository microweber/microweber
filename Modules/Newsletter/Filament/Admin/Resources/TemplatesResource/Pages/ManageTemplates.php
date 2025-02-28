<?php

namespace Modules\Newsletter\Filament\Admin\Resources\TemplatesResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Modules\Newsletter\Filament\Admin\Resources\SubscribersResource;
use Modules\Newsletter\Filament\Admin\Resources\TemplatesResource;
use Modules\Newsletter\Filament\Components\SelectTemplate;
use Modules\Newsletter\Models\NewsletterTemplate;

class ManageTemplates extends ManageRecords
{
    protected static string $resource = TemplatesResource::class;

    public function startWithTemplate($template)
    {
        $templateJson = file_get_contents(module_path('newsletter'). '/resources/views/email-templates/' . $template. '.json');
        if (!$templateJson) {
            return;
        }

        $findLastTemplate = NewsletterTemplate::orderBy('id', 'desc')->first();

        $newTemplate = new NewsletterTemplate();
        if ($findLastTemplate) {
            $newTemplate->title = ucfirst($template) . ' (' . ($findLastTemplate->id + 1) . ')';
        } else {
            $newTemplate->title = ucfirst($template);
        }
        $newTemplate->json = $templateJson;
        $newTemplate->save();

        return redirect(route('filament.admin.pages.newsletter.template-editor') . '?id=' . $newTemplate->id);
    }

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
            Actions\Action::make('New design')
                ->modalHeading('Select a template')
                ->modalDescription('Choose a template to start with')
                ->slideOver()
                ->modalSubmitAction(false)
                ->form([
                    SelectTemplate::make('template')
                        ->label('Template')
                        ->required()
                        ->default('default'),
                ])
        ];
    }
}
