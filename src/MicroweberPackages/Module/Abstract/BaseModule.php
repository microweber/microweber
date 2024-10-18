<?php

namespace MicroweberPackages\Module\Abstract;

abstract class BaseModule
{














    public string $title = 'Base module';
    public string $type = 'base';











    public function getIcon()
    {
        return '<i class="mdi mdi-cube-outline"></i>';
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getType()
    {
        return $this->type;
    }

    public function render()
    {
        return '';
    }

    public function settings()
    {
        return '';
    }


}
