<?php

namespace MicroweberPackages\FormBuilder\Elements;

interface  ElementInterface
{
    public function getType();
    public function render();

    public function __toString();
    public function __call($method, $params);

}
