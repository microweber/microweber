<?php

namespace MicroweberPackages\View;


use  Illuminate\View\Compilers\ComponentTagCompiler;

class  MicroweberModuleTagCompiler extends ComponentTagCompiler
{
    public function compile(string $value)
    {

         $value = parent::compile($value);
     //  $value = app()->parser->process($value);

        return $value;
    }

}
