<?php

namespace Modules\ContactForm\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\ContactForm\Filament\ContactFormModuleSettings;
use Modules\ContactForm\Models\Form;
use Modules\Teamcard\Filament\TeamcardModuleSettings;
use Modules\Teamcard\Models\Teamcard;

class ContactFormModule extends BaseModule
{
    public static string $name = 'ContactForm';
    public static string $module = 'contact_form';
    public static string $icon = 'heroicon-o-user-group';
    public static string $categories = 'forms';
    public static int $position = 57;
    public static string $settingsComponent = ContactFormModuleSettings::class;
    public static string $templatesNamespace = 'modules.contact_form::templates';

    public function render()
    {
        $viewData = $this->getViewData();

        $findForm = Form::where('module_id', $this->params['id'])->first();
        if (!$findForm) {
            $findForm = new Form();
            $findForm->module_id = $this->params['id'];
            $findForm->name = 'Contact Form (' . $this->params['id'] . ')';
            $findForm->save();
        }
        $default_fields = [
            'name' => 'Name',
            'email' => 'Email',
            'message' => 'Message',
        ];
        $viewData['form'] = $findForm;
        $viewData['form_id'] = 'contact_form_di_' . $findForm->id;
        $viewData['default_fields'] = implode(',', array_keys($default_fields));
        $viewData['button_text'] = 'Send Message';

        $template = $viewData['template'] ?? 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }

}
