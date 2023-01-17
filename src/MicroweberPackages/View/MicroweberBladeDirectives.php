<?php

namespace MicroweberPackages\View;

class MicroweberBladeDirectives
{
    public static function module($expression)
    {
        return <<<EOT
<?php
echo app()->parser->process("<module ".app()->format->arrayToHtmlAttributes($expression)." />");
?>
EOT;
    }
}
