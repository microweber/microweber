<?php

namespace Templates\Bootstrap\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Col extends Component
{
    public $size = 12;
    public $sizeLg = 12;
    public $sizeSm = 12;
    public $sizeXxl = 12;
    public $sizeXl = 12;
    public $sizeMd = 12; 
    public $class = '';

    public function __construct($size = 12, $sizeLg = null, $sizeSm = null, $sizeXxl = null, $sizeXl = null, $sizeMd = null, $class = '')
    {
        if (!$sizeLg) {
            $sizeLg = $size;
        }
        if (!$sizeSm) {
            $sizeSm = $size;
        }
        if (!$sizeXxl) {
            $sizeXxl = $size;
        }
        if (!$sizeXl) {
            $sizeXl = $size;
        }
        if (!$sizeMd) {
            $sizeMd = $size; 
        }
        $this->size = $size;
        $this->sizeLg = $sizeLg;
        $this->sizeSm = $sizeSm;
        $this->sizeXxl = $sizeXxl;
        $this->sizeXl = $sizeXl;
        $this->sizeMd = $sizeMd; 
        $this->class = $class;
    }

    public function render(): View
    {
        return view('templates.bootstrap::components.col');
    }
}
