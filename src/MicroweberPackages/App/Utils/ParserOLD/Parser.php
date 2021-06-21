<?php
namespace MicroweberPackages\App\Parser;

final class Parser
{
    public function process($layout)
    {
        $parserContentFields = new ParserContentFields();
        $layout = $parserContentFields->recursiveParseContentFields($layout);

        $parserModule = new ParserModule();
        $layout = $parserModule->recursiveParseModules($layout);

        $layout = $this->replace_variables($layout);

        return $layout;
    }

    public function replace_variables($layout)
    {
        $layout = str_replace('{rand}', uniqid() . rand(), $layout);
        $layout = str_replace('{SITE_URL}', app()->url_manager->site(), $layout);
        $layout = str_replace('{MW_SITE_URL}', app()->url_manager->site(), $layout);
        $layout = str_replace('%7BSITE_URL%7D', app()->url_manager->site(), $layout);

        return $layout;
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
