<?php

namespace MicroweberPackages\FormBuilder\Elements;

class RangeSlider extends Input
{
    public function getType()
    {
        return 'text';
    }

    public function render() {

        return view('microweber-form-builder::form-inputs.range-slider',[
            'input' => $this,
            'renderAttributes' => $this->renderAttributes(),
        ]);
    }

}
