<?php

namespace Modules\ContactForm\Microweber;

use MicroweberPackages\ModuleRegistry\Abstract\BaseModule;
use Modules\ContactForm\Filament\ContactFormModuleSettings;
use Modules\ContactForm\Models\Form;
use Modules\Teamcard\Filament\TeamcardModuleSettings;
use Modules\Teamcard\Models\Teamcard;

class ContactFormModule extends BaseModule
{
    public static string $name = 'Contact Form';
    public static string $module = 'contact_form';
    public static string $icon = 'heroicon-o-user-group';
    public static string $categories = 'forms';
    public static int $position = 57;
    public static string $settingsComponent = ContactFormModuleSettings::class;
    public static string $templatesNamespace = 'modules.contact_form::templates';
    public static array $translatableOptions = ['button_text'];

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
        $default_fields = $this->params['default_fields'] ?? [
            'Name' ,
            'Email',
            'Message',
        ];

        $viewData['form'] = $findForm;
        $viewData['form_id'] = 'contact_form_id' . $findForm->id;
        $viewData['default_fields'] = implode(',', $default_fields);
        $viewData['button_text'] = $viewData['options']['button_text'] ?? 'Send Message';

        // task-2026-05-22-759a88 / AI-921 — wire newsletter_subscription toggle.
        // ContactFormModuleSettings defines options.newsletter_subscription but
        // render() never forwarded it — template @if(isset($show_newsletter_subscription))
        // always got nothing so the subscription field was permanently invisible.
        $viewData['show_newsletter_subscription'] = (bool) (get_option('newsletter_subscription', $this->params['id']) ?: false);
        // Also wire enable_captcha and email_redirect_after_submit which have the
        // same settings-not-wired gap (designer note in AI-921).
        $viewData['enable_captcha'] = (bool) (get_option('enable_captcha', $this->params['id']) ?: false);
        $viewData['email_redirect_after_submit'] = get_option('email_redirect_after_submit', $this->params['id']) ?: '';

        $template = $viewData['template'] ?? 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }

}
