<?php

namespace MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate;

class NewsletterChooseTemplateModal extends AdminModalComponent
{
    public $emailTemplates = [];

    public $modalSettings = [
        'width'=>'900px',
        'overlay' => true,
        'overlayClose' => true,
    ];

    public function selectTemplate($name, $filename)
    {

        $templateFile = modules_path() . '/newsletter/src/resources/views/email-templates/'.$filename.'.blade.php';
        if (!is_file($templateFile)) {
            return;
        }

        $templateContentHtml = file_get_contents($templateFile);

        $newsletterTemplateName = 'Newsletter Template - ' . $name;
        $findNewsletterTemplateCount = NewsletterTemplate::where('title', 'like', '%' . $newsletterTemplateName . '%')->count();
        if ($findNewsletterTemplateCount) {
            $newsletterTemplateName = 'Newsletter Template - ' . $name . ' ('.($findNewsletterTemplateCount+1).')';
        }

        $newsletterTemplate = new NewsletterTemplate();
        $newsletterTemplate->title = $newsletterTemplateName;
        $newsletterTemplate->text = $templateContentHtml;
        $newsletterTemplate->save();

        $this->emit('newsletter.templateSelected', $name, $filename);
        $this->closeModal();
    }

    public function render()
    {

        $this->emailTemplates[] = [
            'name' => 'Default',
            'filename' => 'template6',
            'screenshot'=>modules_url() . '/newsletter/src/resources/views/email-templates/template6.jpg',
        ];

        $this->emailTemplates[] = [
            'name' => 'Explore',
            'filename' => 'template4',
            'screenshot'=>modules_url() . '/newsletter/src/resources/views/email-templates/template4.jpg',
        ];

        $this->emailTemplates[] = [
            'name' => 'Explore White',
            'filename' => 'template5',
            'screenshot'=>modules_url() . '/newsletter/src/resources/views/email-templates/template5.jpg',
        ];


        $this->emailTemplates[] = [
            'name' => 'Modern',
            'filename' => 'template2',
            'screenshot'=>modules_url() . '/newsletter/src/resources/views/email-templates/template2.jpg',
        ];


        return view('microweber-module-newsletter::livewire.admin.choose-template-modal');
    }

}
