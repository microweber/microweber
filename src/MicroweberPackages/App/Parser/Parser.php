<?php
namespace MicroweberPackages\App\Parser;

final class Parser
{
    public function process($layout)
    {
        $parserModule = new ParserModule();
        echo $parserModule->recursive_parse_modules($layout);
    }

    public function replace_url_placeholders($layout)
    {
        if (defined('TEMPLATE_URL')) {
            $replaces = array(
                '{TEMPLATE_URL}',
                '{THIS_TEMPLATE_URL}',
                '{DEFAULT_TEMPLATE_URL}',
                '%7BTEMPLATE_URL%7D',
                '%7BTHIS_TEMPLATE_URL%7D',
                '%7BDEFAULT_TEMPLATE_URL%7D',
            );

            $replaces_vals = array(
                TEMPLATE_URL,
                THIS_TEMPLATE_URL,
                DEFAULT_TEMPLATE_URL,
                TEMPLATE_URL,
                THIS_TEMPLATE_URL,
                DEFAULT_TEMPLATE_URL
            );

            $layout = str_replace_bulk($replaces, $replaces_vals, $layout);
        }

        return $layout;
    }
}
