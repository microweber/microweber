<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\TemplatesResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SubscribersResource;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\TemplatesResource;
use MicroweberPackages\Modules\Newsletter\Filament\Components\SelectTemplate;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate;

class ManageTemplates extends ManageRecords
{
    protected static string $resource = TemplatesResource::class;

    public function startWithTemplate($template)
    {
        $templateJson = file_get_contents(modules_path() . 'newsletter/src/resources/views/email-templates/' . $template. '.json');
        if (!$templateJson) {
            return;
        }

        $newTemplate = new NewsletterTemplate();
        $newTemplate->title = 'New template';
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
