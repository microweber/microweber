<?php

namespace MicroweberPackages\Template\Adapters\RenderHelpers;

class CsrfTokenRequestInlineJsScriptGenerator
{
    public function generate()
    {
        if(!mw_is_installed()){
            return;
        }


        $content =  file_get_contents(__DIR__.'/CsrfTokenRequestInlineJsScriptGenerator.js');
        return <<<HTML
       $content
HTML;
    }
}
