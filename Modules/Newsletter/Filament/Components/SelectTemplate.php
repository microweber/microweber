<?php

namespace Modules\Newsletter\Filament\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Actions\Action;
use Modules\Newsletter\Models\NewsletterTemplate;

class SelectTemplate extends Field
{

    protected string $view = 'microweber-module-newsletter::livewire.filament.components.select-template';

    public $campaignId;

    public function setCampaignId($id)
    {
        $this->campaignId = $id;
        return $this;
    }

    public function getCampaignId()
    {
        return $this->campaignId;
    }

    public function getCampaign()
    {
        $model = \Modules\Newsletter\Models\NewsletterCampaign::where('id', $this->campaignId)->first();

        if (!$model) {
            return [];
        }
        $emailTemplate = NewsletterTemplate::where('id', $model->email_template_id)->first();

        return [
            'campaignId' => $model->id,
            'campaign' => $model,
            'emailTemplate'=> $emailTemplate
        ];
    }

    public function getEmailTemplates()
    {
        $emailTemplates = [];

        $templatesPath = normalize_path(module_path('newsletter'). '/resources/views/email-templates',true);
        $templates = glob($templatesPath . '*.json');

        foreach ($templates as $template) {
            $filename = basename($template, '.json');
            $screenshotUrl = asset('modules/newsletter/img/' . $filename . '.png');
            $emailTemplates[] = [
                'name' => $filename,
                'filename' => $filename,
                'screenshot' => $screenshotUrl
            ];
        }

        return $emailTemplates;

    }
}
