<?php

declare(strict_types=1);

namespace MicroweberPackages\View;

use MicroweberPackages\View\Contracts\ModuleProcessorInterface;
use MicroweberPackages\View\Support\HtmlAttributes;

/**
 * Blade directives registered by ViewServiceProvider.
 *
 * The @module directive is CMS-agnostic: it resolves ModuleProcessorInterface
 * from the container and uses HtmlAttributes (or the format package if bound).
 */
class MicroweberBladeDirectives
{
    /**
     * Compile @module([...]) into PHP that processes a <module /> tag.
     *
     * @param  string  $expression  Blade expression, typically an array literal
     */
    public static function module(string $expression): string
    {
        $processor = ModuleProcessorInterface::class;
        $htmlAttributes = HtmlAttributes::class;
        $formatService = \MicroweberPackages\Format\FormatService::class;
        $formatFacade = \MicroweberPackages\Format\Facades\Format::class;

        return <<<PHP
<?php
\$__mwModuleAttrs = {$expression};
if (!is_array(\$__mwModuleAttrs)) {
    \$__mwModuleAttrs = [];
}
\$__mwAttrString = '';
if (app()->bound(\\{$formatService}) && class_exists(\\{$formatFacade})) {
    \$__mwAttrString = \\{$formatFacade}::arrayToHtmlAttributes(\$__mwModuleAttrs);
} else {
    \$__mwAttrString = \\{$htmlAttributes}::toString(\$__mwModuleAttrs);
}
echo app(\\{$processor}::class)->process('<module ' . \$__mwAttrString . ' />');
?>
PHP;
    }
}
