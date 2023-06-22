<?php

namespace MicroweberPackages\Multilanguage;

use MicroweberPackages\FormBuilder\FormElementBuilder;
use MicroweberPackages\Multilanguage\FormElements\MwEditor;
use MicroweberPackages\Multilanguage\FormElements\MwModuleSettings;
use MicroweberPackages\Multilanguage\FormElements\Text;
use MicroweberPackages\Multilanguage\FormElements\TextArea;
use MicroweberPackages\Multilanguage\FormElements\TextAreaOption;
use MicroweberPackages\Multilanguage\FormElements\TextOption;

class MultilanguageFormElementBuilder extends FormElementBuilder
{
    protected $formElementsClasses = [
        'Text' => Text::class,
        'MwEditor' => MwEditor::class,
        'MwModuleSettings' => MwModuleSettings::class,
        'TextOption' => TextOption::class,
        'TextArea' => TextArea::class,
        'TextAreaOption' => TextAreaOption::class,
    ];
    protected $drivers = [
        'text' => Text::class,
        'textarea' => TextArea::class,
        'mw-editor' => MwEditor::class,
        'mw-module-settings' => MwModuleSettings::class,
        'textarea-option' => TextAreaOption::class,
        'text-option' => TextOption::class,
    ];
}
