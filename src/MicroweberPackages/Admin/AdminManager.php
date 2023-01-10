<?php

namespace MicroweberPackages\Admin;

class AdminManager
{
    public $scripts = [];
    public $styles = [];
    public $customTags = [];

    public function __construct()
    {
        $this->addDefaultScripts();
        $this->addDefaultStyles();
        $this->addDefaultCustomTags();
    }

    public function headTags()
    {
        $tags = [];

        $tags[] = $this->styles();
        $tags[] = $this->scripts();
        $tags[] = $this->customTags();

        return implode("\r\n", $tags);
    }


    public function addDefaultScripts(): void
    {
        $apijs_combined_loaded = app()->template->get_apijs_combined_url();
        $this->addScript('mw-api-js', $apijs_combined_loaded);
    }

    public function addDefaultStyles(): void
    {
        $default_css_url = app()->template->get_default_system_ui_css_url();
        $this->addStyle('mw-default-css', $default_css_url);

        $main_css_url = app()->template->get_admin_system_ui_css_url();
        $this->addStyle('mw-ui-css', $main_css_url);


        $favicon_image = get_option('favicon_image', 'website');

        if (!$favicon_image) {
            $ui_favicon = mw()->ui->brand_favicon();
            if ($ui_favicon and trim($ui_favicon) != '') {
                $favicon_image = trim($ui_favicon);
            }
        }

        if ($favicon_image) {
            $this->addStyle('favicon', $favicon_image, ['rel' => 'shortcut icon']);
        }

    }

    public function addCustomTags($html): void
    {
        $this->customTags[] = $html;
    }

    public function addScript($id, $src, $attributes = []): void
    {
        $this->scripts[$id] = [
            'id' => $id,
            'src' => $src,
            'attributes' => $attributes
        ];
    }


    public function addStyle($id, $src, $attributes = []): void
    {
        $this->styles[$id] = [
            'id' => $id,
            'src' => $src,
            'attributes' => $attributes
        ];
    }

    public function customTags()
    {
        if ($this->customTags) {
            return implode("\n", $this->customTags);
        }

        return '';
    }

    public function styles()
    {
        $ready = [];

        if ($this->styles) {
            foreach ($this->styles as $script) {
                $attrs = [
                    'rel' => 'stylesheet',
                    'id' => $script['id'],
                    'href' => $script['src'],
                    'type' => 'text/css'
                ];
                if (isset($script['attributes']) and $script['attributes']) {
                    $attrs = array_merge($attrs, $script['attributes']);
                }
                $attrsString = $this->buildAttributes($attrs);

                $ready[] = '<link ' . $attrsString . ' />';
            }
        }

        return implode("\r\n", $ready);

    }

    function buildAttributes($attributes)
    {
        if (empty($attributes))
            return '';
        if (!is_array($attributes))
            return $attributes;

        $attributePairs = [];
        foreach ($attributes as $key => $val) {
            if (is_int($key))
                $attributePairs[] = $val;
            else {
                $val = htmlspecialchars($val, ENT_QUOTES);
                $attributePairs[] = "{$key}=\"{$val}\"";
            }
        }

        return join(' ', $attributePairs);
    }

    public function scripts()
    {
        $ready = [];
        if ($this->scripts) {
            foreach ($this->scripts as $script) {
                $attrs = [
                    'id' => $script['id'],
                    'src' => $script['src'],
                ];
                if (isset($script['attributes']) and $script['attributes']) {
                    $attrs = array_merge($attrs, $script['attributes']);
                }
                $attrsString = $this->buildAttributes($attrs);
                $ready[] = '<script ' . $attrsString . '></script>';
            }
        }


        return implode("\r\n", $ready);
    }


    public function addDefaultCustomTags(): void
    {
        $template_headers_src = mw()->template->admin_head(true);
        if ($template_headers_src != false and $template_headers_src != '') {
            $this->addCustomTags($template_headers_src);
        }
    }

}
