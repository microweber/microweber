<?php

namespace MicroweberPackages\View;

#[\Attribute]
class  ViewComponentName
{
    public string  $name;
    public string $package;

    public function __construct(string  $name, string $package)
    {
        $this->name = $name;
        $this->package = $package;
    }
}
