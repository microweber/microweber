<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Components;

use Filament\Forms\Components\Field;

class SelectTemplate extends Field
{

    protected string $view = 'microweber-module-newsletter::livewire.filament.components.select-template';


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
                'screenshot' => $screenshotUrl,
            ];
        }

        return $emailTemplates;

    }
}
