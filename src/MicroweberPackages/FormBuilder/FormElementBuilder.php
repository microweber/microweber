<?php

namespace MicroweberPackages\FormBuilder;

use Illuminate\Support\Manager;
use MicroweberPackages\FormBuilder\Binding\BoundData;
use MicroweberPackages\FormBuilder\Elements\Checkbox;
use MicroweberPackages\FormBuilder\Elements\Color;
use MicroweberPackages\FormBuilder\Elements\Date;
use MicroweberPackages\FormBuilder\Elements\DateTimeLocal;
use MicroweberPackages\FormBuilder\Elements\Email;
use MicroweberPackages\FormBuilder\Elements\File;
use MicroweberPackages\FormBuilder\Elements\Label;
use MicroweberPackages\FormBuilder\Elements\MwEditor;
use MicroweberPackages\FormBuilder\Elements\MwModuleSettings;
use MicroweberPackages\FormBuilder\Elements\Number;
use MicroweberPackages\FormBuilder\Elements\RadioButton;
use MicroweberPackages\FormBuilder\Elements\Range;
use MicroweberPackages\FormBuilder\Elements\RangeSlider;
use MicroweberPackages\FormBuilder\Elements\Select;
use MicroweberPackages\FormBuilder\Elements\Text;
use MicroweberPackages\FormBuilder\Elements\TextArea;
use MicroweberPackages\FormBuilder\Elements\TextAreaOption;
use MicroweberPackages\FormBuilder\Elements\TextOption;
use MicroweberPackages\FormBuilder\OldInput\OldInputInterface;

class FormElementBuilder extends Manager
{
    protected $oldInput;
    protected $boundData;
    /**
     * @deprecated This property is deprecated and should not be used anymore.
     */
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
        'number' => Number::class,
        'textarea' => TextArea::class,
        'label' => Label::class,
        'select' => Select::class,
        'radio' => RadioButton::class,
        'range' => Range::class,
        'range-slider' => RangeSlider::class,
        'checkbox' => Checkbox::class,
        'color' => Color::class,
        'date' => Date::class,
        'email' => Email::class,
        'file' => File::class,
        'mw-editor' => MwEditor::class,
        'mw-module-settings' => MwModuleSettings::class,
        'textarea-option' => TextAreaOption::class,
        'text-option' => TextOption::class,
    ];


    /**
     * Create a form element of the specified type and name.
     *
     * @param string $type The type of the form element.
     * @param string $key The name of the form element.
     * @return \MicroweberPackages\FormBuilder\Elements\ElementInterface The created form element.
     */
    public function make($type, $key, ...$params )
    {
        $driver = $this->driver($type);
        /**
         * @var \MicroweberPackages\FormBuilder\Elements\ElementInterface $element
         */
        $element = new $driver($key, ...$params);

        if (!is_null($value = $this->getValueFor($key))) {
            $element->value($value);
        }

        return $element;

    }

    public function getDefaultDriver()
    {
        return 'text';
    }


    public function setOldInputProvider(OldInputInterface $oldInputProvider)
    {
        $this->oldInput = $oldInputProvider;
    }

    /**
     * @deprecated This property is deprecated and should not be used anymore.
     */
    public function mwEditor($name)
    {
        $text = new $this->formElementsClasses['MwEditor']($name);

        if (!is_null($value = $this->getValueFor($name))) {
            $text->value($value);
        }

        return $text;
    }

    /**
     * @deprecated This property is deprecated and should not be used anymore.
     */
    public function mwModuleSettings($name)
    {
        $text = new $this->formElementsClasses['MwModuleSettings']($name);

        if (!is_null($value = $this->getValueFor($name))) {
            $text->value($value);
        }

        return $text;
    }

    /**
     * @deprecated This property is deprecated and should not be used anymore.
     */
    public function text($name)
    {
        $text = new $this->formElementsClasses['Text']($name);

        if (!is_null($value = $this->getValueFor($name))) {
            $text->value($value);
        }

        return $text;
    }

    /**
     * @deprecated This property is deprecated and should not be used anymore.
     */
    public function textOption($optionKey, $optionGroup)
    {
        $textOption = new $this->formElementsClasses['TextOption']($optionKey, $optionGroup);

        return $textOption;
    }

    /**
     * @deprecated This property is deprecated and should not be used anymore.
     */
    public function textarea($name)
    {
        $textarea = new $this->formElementsClasses['TextArea']($name);

        if (!is_null($value = $this->getValueFor($name))) {
            $textarea->value($value);
        }

        return $textarea;
    }

    /**
     * @deprecated This property is deprecated and should not be used anymore.
     */
    public function textareaOption($optionKey, $optionGroup)
    {
        $textOption = new $this->formElementsClasses['TextAreaOption']($optionKey, $optionGroup);

        return $textOption;
    }

    /**
     * @deprecated This property is deprecated and should not be used anymore.
     */
    public function date($name)
    {
        $date = new Date($name);

        if (!is_null($value = $this->getValueFor($name))) {
            $date->value($value);
        }

        return $date;
    }

    /**
     * @deprecated This property is deprecated and should not be used anymore.
     */
    public function dateTimeLocal($name)
    {
        $date = new DateTimeLocal($name);

        if (!is_null($value = $this->getValueFor($name))) {
            $date->value($value);
        }

        return $date;
    }

    /**
     * @deprecated This property is deprecated and should not be used anymore.
     */
    public function email($name)
    {
        $email = new Email($name);

        if (!is_null($value = $this->getValueFor($name))) {
            $email->value($value);
        }

        return $email;
    }

    /**
     * @deprecated This property is deprecated and should not be used anymore.
     */
    public function checkbox($name, $value = 1)
    {
        $checkbox = new Checkbox($name, $value);

        $oldValue = $this->getValueFor($name);
        $checkbox->setOldValue($oldValue);

        return $checkbox;
    }

    /**
     * @deprecated This property is deprecated and should not be used anymore.
     */
    public function radio($name, $value = null)
    {
        $radio = new RadioButton($name, $value);

        $oldValue = $this->getValueFor($name);
        $radio->setOldValue($oldValue);

        return $radio;
    }

    /**
     * @deprecated This property is deprecated and should not be used anymore.
     */
    public function select($name, $options = [])
    {
        $select = new Select($name, $options);

        $selected = $this->getValueFor($name);
        $select->select($selected);

        return $select;
    }

    /**
     * @deprecated This property is deprecated and should not be used anymore.
     */
    public function label($label)
    {
        return new Label($label);
    }

    /**
     * @deprecated This property is deprecated and should not be used anymore.
     */
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
        if (!isset($this->oldInput)) {
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

    /**
     * @deprecated This property is deprecated and should not be used anymore.
     */
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
