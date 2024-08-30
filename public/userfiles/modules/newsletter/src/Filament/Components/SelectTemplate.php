<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Actions\Action;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate;

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
        $model = \MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign::where('id', $this->campaignId)->first();

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

        $templatesPath = modules_path() .'newsletter/src/resources/views/email-templates';
        $templates = glob($templatesPath . '/*.json');

        foreach ($templates as $template) {
            $filename = basename($template, '.json');
            $screenshotUrl = modules_url() . 'newsletter/src/resources/views/email-templates/' . $filename . '.png';
            $emailTemplates[] = [
                'name' => $filename,
                'filename' => $filename,
                'screenshot' => $screenshotUrl
            ];
        }

        return $emailTemplates;

    }
}
