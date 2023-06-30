<?php

namespace MicroweberPackages\Multilanguage;

use MicroweberPackages\CustomField\Fields\Checkbox;
use MicroweberPackages\FormBuilder\Elements\Color;
use MicroweberPackages\FormBuilder\Elements\Date;
use MicroweberPackages\FormBuilder\Elements\Email;
use MicroweberPackages\FormBuilder\Elements\File;
use MicroweberPackages\FormBuilder\Elements\Label;
use MicroweberPackages\FormBuilder\Elements\Number;
use MicroweberPackages\FormBuilder\Elements\RadioButton;
use MicroweberPackages\FormBuilder\Elements\Range;
use MicroweberPackages\FormBuilder\Elements\Select;
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
//    protected $drivers = [
//        'text' => Text::class,
//        'textarea' => TextArea::class,
//        'mw-editor' => MwEditor::class,
//        'mw-module-settings' => MwModuleSettings::class,
//        'textarea-option' => TextAreaOption::class,
//        'text-option' => TextOption::class,
//    ];
//    protected $drivers = [
//        'text'=>Text::class,
//        'number'=>Number::class,
//        'textarea'=>TextArea::class,
//        'label'=>Label::class,
//        'select'=>Select::class,
//        'radio'=>RadioButton::class,
//        'range'=>Range::class,
//        'checkbox'=>Checkbox::class,
//        'color'=>Color::class,
//        'date'=>Date::class,
//        'email'=>Email::class,
//        'file'=>File::class,
//        'mw-editor'=>MwEditor::class,
//        'mw-module-settings'=>MwModuleSettings::class,
//        'textarea-option'=>TextAreaOption::class,
//        'text-option'=>TextOption::class,
//    ];
}
