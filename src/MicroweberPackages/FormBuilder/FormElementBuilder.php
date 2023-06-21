<?php

namespace MicroweberPackages\FormBuilder;

use MicroweberPackages\FormBuilder\Elements\Button;
use MicroweberPackages\FormBuilder\Elements\Hidden;
use MicroweberPackages\FormBuilder\Binding\BoundData;
use MicroweberPackages\FormBuilder\Elements\Checkbox;
use MicroweberPackages\FormBuilder\Elements\Date;
use MicroweberPackages\FormBuilder\Elements\DateTimeLocal;
use MicroweberPackages\FormBuilder\Elements\Email;
use MicroweberPackages\FormBuilder\Elements\File;
use MicroweberPackages\FormBuilder\Elements\Label;
use MicroweberPackages\FormBuilder\Elements\MwEditor;
use MicroweberPackages\FormBuilder\Elements\MwModuleSettings;
use MicroweberPackages\FormBuilder\Elements\RadioButton;
use MicroweberPackages\FormBuilder\Elements\Select;
use MicroweberPackages\FormBuilder\Elements\Text;
use MicroweberPackages\FormBuilder\Elements\TextArea;
use MicroweberPackages\FormBuilder\Elements\TextAreaOption;
use MicroweberPackages\FormBuilder\Elements\TextOption;
use MicroweberPackages\FormBuilder\OldInput\OldInputInterface;

class FormElementBuilder
{
    protected $oldInput;
    protected $boundData;
    protected $formElementsClasses = [
        'Text'=>Text::class,
        'MwEditor'=>MwEditor::class,
        'MwModuleSettings'=>MwModuleSettings::class,
        'TextOption'=>TextOption::class,
        'TextArea'=>TextArea::class,
        'TextAreaOption'=>TextAreaOption::class,
    ];

    public function setOldInputProvider(OldInputInterface $oldInputProvider)
    {
        $this->oldInput = $oldInputProvider;
    }

    public function mwEditor($name)
    {
        $text = new $this->formElementsClasses['MwEditor']($name);

        if (!is_null($value = $this->getValueFor($name))) {
            $text->value($value);
        }

        return $text;
    }


    public function mwModuleSettings($name)
    {
        $text = new $this->formElementsClasses['MwModuleSettings']($name);

        if (!is_null($value = $this->getValueFor($name))) {
            $text->value($value);
        }

        return $text;
    }

    public function text($name)
    {
        $text = new $this->formElementsClasses['Text']($name);

        if (!is_null($value = $this->getValueFor($name))) {
            $text->value($value);
        }

        return $text;
    }

    public function textOption($optionKey, $optionGroup)
    {
        $textOption = new $this->formElementsClasses['TextOption']($optionKey, $optionGroup);

        return $textOption;
    }

    public function textarea($name)
    {
        $textarea = new $this->formElementsClasses['TextArea']($name);

        if (!is_null($value = $this->getValueFor($name))) {
            $textarea->value($value);
        }

        return $textarea;
    }

    public function textareaOption($optionKey, $optionGroup)
    {
        $textOption = new $this->formElementsClasses['TextAreaOption']($optionKey, $optionGroup);

        return $textOption;
    }

    public function date($name)
    {
        $date = new Date($name);

        if (!is_null($value = $this->getValueFor($name))) {
            $date->value($value);
        }

        return $date;
    }

    public function dateTimeLocal($name)
    {
        $date = new DateTimeLocal($name);

        if (!is_null($value = $this->getValueFor($name))) {
            $date->value($value);
        }

        return $date;
    }

    public function email($name)
    {
        $email = new Email($name);

        if (!is_null($value = $this->getValueFor($name))) {
            $email->value($value);
        }

        return $email;
    }

    public function checkbox($name, $value = 1)
    {
        $checkbox = new Checkbox($name, $value);

        $oldValue = $this->getValueFor($name);
        $checkbox->setOldValue($oldValue);

        return $checkbox;
    }

    public function radio($name, $value = null)
    {
        $radio = new RadioButton($name, $value);

        $oldValue = $this->getValueFor($name);
        $radio->setOldValue($oldValue);

        return $radio;
    }

    public function select($name, $options = [])
    {
        $select = new Select($name, $options);

        $selected = $this->getValueFor($name);
        $select->select($selected);

        return $select;
    }

    public function label($label)
    {
        return new Label($label);
    }

    public function file($name)
    {
        return new File($name);
    }

    public function bind($data)
    {
        $this->boundData = new BoundData($data);
    }

    public function getValueFor($name)
    {
        if ($this->hasOldInput()) {
            return $this->getOldInput($name);
        }

        if ($this->hasBoundData()) {
            return $this->getBoundValue($name, null);
        }

        return null;
    }

    protected function hasOldInput()
    {
        if (! isset($this->oldInput)) {
            return false;
        }

        return $this->oldInput->hasOldInput();
    }

    protected function getOldInput($name)
    {
        return $this->oldInput->getOldInput($name);
    }

    protected function hasBoundData()
    {
        return isset($this->boundData);
    }

    protected function getBoundValue($name, $default)
    {
        return $this->boundData->get($name, $default);
    }

    protected function unbindData()
    {
        $this->boundData = null;
    }

    public function selectMonth($name)
    {
        $options = [
            "1" => "January",
            "2" => "February",
            "3" => "March",
            "4" => "April",
            "5" => "May",
            "6" => "June",
            "7" => "July",
            "8" => "August",
            "9" => "September",
            "10" => "October",
            "11" => "November",
            "12" => "December",
        ];

        return $this->select($name, $options);
    }
}
