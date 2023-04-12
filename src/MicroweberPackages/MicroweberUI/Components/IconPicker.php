<?php

namespace MicroweberPackages\MicroweberUI\Components;

use Illuminate\View\Component;


class IconPicker extends Component
{

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        return view('mw-ui.components.icon-picker');
    }
}
