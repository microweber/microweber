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
        $templates = glob($templatesPath . '/*.blade.php');

        foreach ($templates as $template) {
            $filename = basename($template, '.blade.php');
            $emailTemplates[] = [
                'name' => $filename,
                'filename' => $filename,
                'demoUrl'=>route('admin.newsletter.preview-email-template-iframe') . '?filename=' . $filename,
            ];
        }

        return $emailTemplates;

    }
}
