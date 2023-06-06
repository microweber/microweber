<?php

namespace MicroweberPackages\Form\Elements;

abstract class FormControl extends Element
{
    protected $readValueFromField = false;
    protected $model = [];

    public function __construct($name)
    {
        $this->setName($name);
    }

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setReadValueFromModelField($field)
    {
        $this->readValueFromField = $field;

        return $this;
    }

    public function getReadValueFromField()
    {
        return $this->readValueFromField;
    }

    protected function setName($name)
    {
        $this->setAttribute('name', $name);
    }

    public function required($conditional = true)
    {
        $this->setBooleanAttribute('required', $conditional);

        return $this;
    }

    public function optional()
    {
        $this->removeAttribute('required');

        return $this;
    }

    public function disable($conditional = true)
    {
        $this->setBooleanAttribute('disabled', $conditional);

        return $this;
    }

    public function readonly($conditional = true)
    {
        $this->setBooleanAttribute('readonly', $conditional);

        return $this;
    }

    public function enable()
    {
        $this->removeAttribute('disabled');
        $this->removeAttribute('readonly');

        return $this;
    }

    public function autofocus()
    {
        $this->setAttribute('autofocus', 'autofocus');

        return $this;
    }

    public function unfocus()
    {
        $this->removeAttribute('autofocus');

        return $this;
    }
}
