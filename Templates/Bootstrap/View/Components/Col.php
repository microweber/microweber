<?php

namespace Templates\Bootstrap\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Col extends Component
{
    public $col = 12;
    public $colLg = 12;
    public $colSm = 12;
    public $colXxl = 12;
    public $colXl = 12;
    public $class = '';

    /**
     * Create the component instance.
     */
    public function __construct($col = 12, $colLg = null, $colSm = null, $colXxl = null, $colXl = null, $class = '')
    {
        if (!$colLg) {
            $colLg = $col;
        }
        if (!$colSm) {
            $colSm = $col;
        }
        if (!$colXxl) {
            $colXxl = $col;
        }
        if (!$colXl) {
            $colXl = $col;
        }
        $this->col = $col;
        $this->colLg = $colLg;
        $this->colSm = $colSm;
        $this->colXxl = $colXxl;
        $this->colXl = $colXl;
        $this->class = $class;
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('templates.bootstrap::components.col');
    }
}
