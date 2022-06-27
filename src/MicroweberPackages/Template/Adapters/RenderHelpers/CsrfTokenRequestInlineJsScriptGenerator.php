<?php

namespace MicroweberPackages\Template\Adapters\RenderHelpers;

class CsrfTokenRequestInlineJsScriptGenerator
{
    public function generate()
    {
        $content =  file_get_contents(__DIR__.'/CsrfTokenRequestInlineJsScriptGenerator.js');
        return <<<HTML
       $content
HTML;
    }
}
