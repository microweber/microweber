<?php

namespace MicroweberPackages\Form;

use MicroweberPackages\Form\Binding\BoundData;
use MicroweberPackages\Form\Elements\Button;
use MicroweberPackages\Form\Elements\Checkbox;
use MicroweberPackages\Form\Elements\Date;
use MicroweberPackages\Form\Elements\DateTimeLocal;
use MicroweberPackages\Form\Elements\Email;
use MicroweberPackages\Form\Elements\File;
use MicroweberPackages\Form\Elements\Hidden;
use MicroweberPackages\Form\Elements\Label;
use MicroweberPackages\Form\Elements\MwEditor;
use MicroweberPackages\Form\Elements\RadioButton;
use MicroweberPackages\Form\Elements\Select;
use MicroweberPackages\Form\Elements\Text;
use MicroweberPackages\Form\Elements\TextArea;
use MicroweberPackages\Form\Elements\TextAreaOption;
use MicroweberPackages\Form\Elements\TextOption;
use MicroweberPackages\Form\OldInput\OldInputInterface;

class FormElementBuilder
{
    protected $oldInput;
    protected $boundData;
    protected $formElementsClasses = [
        'Text'=>Text::class,
        'MwEditor'=>MwEditor::class,
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
