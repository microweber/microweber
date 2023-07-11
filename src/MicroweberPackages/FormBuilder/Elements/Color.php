<?php

namespace MicroweberPackages\FormBuilder\Elements;

class Color extends Input
{
    public function getType()
    {
        return 'color';
    }
    protected $attributes = [
        'type' => 'color',
        'class'=>'form-control'
    ];


    public function render() {
        return 'qkooo';
    }
}
