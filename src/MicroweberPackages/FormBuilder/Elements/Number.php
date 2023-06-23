<?php

namespace MicroweberPackages\FormBuilder\Elements;

class Number extends Input
{
    public function getType()
    {
        return 'number';
    }
    protected $attributes = [
        'type' => 'number',
        'class'=>'form-control'
    ];

}
