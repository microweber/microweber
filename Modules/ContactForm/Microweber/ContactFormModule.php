<?php

namespace Modules\ContactForm\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\ContactForm\Filament\ContactFormModuleSettings;
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


        return view('modules.contact_form::templates.default', $viewData);
    }

}
