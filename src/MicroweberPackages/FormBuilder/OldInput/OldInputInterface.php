<?php

namespace MicroweberPackages\FormBuilder\OldInput;

interface OldInputInterface
{
    public function hasOldInput();

    public function getOldInput($key);
}
