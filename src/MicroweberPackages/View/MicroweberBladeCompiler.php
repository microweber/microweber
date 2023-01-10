<?php

namespace MicroweberPackages\View;


use Illuminate\Container\Container;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MicroweberBladeCompiler  extends  \Illuminate\View\Compilers\BladeCompiler
{
//    public function __construct($files, $cachePath)
//    {
//        parent::__construct($files, $cachePath);
//    }

//    public function compileString($value)
//    {
//        parent::withoutDoubleEncoding();
//        parent::withoutComponentTags();
//        parent::compileEndSwitch();
//     //   $value =   parent::compileString($value);
//
//        $value = app()->parser->process($value);
//        $value =   parent::compileString($value);
//        return $value;
//    }

//
//    public function compileString($value)
//    {
//      //  $value = parent::compileString($value);
//
//       // $value = app()->parser->process($value);
//       // parent::withoutDoubleEncoding();
//        $value = parent::compileString($value);
//
//        return $value;
//
//    }


}

