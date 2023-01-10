<?php

namespace MicroweberPackages\Template\Traits;

trait HasScriptsAndStylesTrait
{
    public array $scripts = [];
    public array $styles = [];
    public array $customHeadTags = [];


    /**
     * Adds a CSS stylesheet url to the page
     *
     * @param string id The id of the style.
     * @param string src The url of the stylesheet.
     * @param array attributes An array of attributes to add to the script tag.
     */
    public function addStyle(string $id, string $src, array $attributes = []): void
    {
        $this->styles[] = [
            'id' => $id,
            'src' => $src,
            'attributes' => $attributes
        ];
    }

    /**
     * Removes a style from the styles array by id
     *
     * @param string id The id of the style you want to remove.
     */
    public function removeStyle(string $id): void
    {
        if ($this->styles) {
            foreach ($this->styles as $key => $item) {
                if ($item['id'] == $id) {
                    unset($this->styles[$key]);
                }
            }
        }
    }

    /**
     * Adds a script to the scripts array
     *
     * @param string $id The id of the script.
     * @param string $src The source of the script.
     * @param array $attributes An array of attributes to add to the script tag.
     */
    public function addScript(string $id, string $src, array $attributes = []): void
    {
        $this->scripts[] = [
            'id' => $id,
            'src' => $src,
            'attributes' => $attributes
        ];
    }

    /**
     * Removes a script from the scripts array
     *
     * @param string id The id of the script to remove.
     */
    public function removeScript(string $id): void
    {
        if ($this->scripts) {
            foreach ($this->scripts as $key => $item) {
                if ($item['id'] == $id) {
                    unset($this->scripts[$key]);
                }
            }
        }
    }

    /**
     * Adds a custom HTML tag to the head of the page
     *
     * @param string html The HTML to add to the head tag.
     */
    public function addCustomHeadTag(string $html): void
    {
        $this->customHeadTags[] = $html;
    }


    /**
     * It takes the array of stylesheets and builds the HTML for them
     *
     * @return string A string of HTML code.
     */
    public function styles(): string
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
                if (isset($script['attributes']) and is_array($script['attributes'])) {
                    $attrs = array_merge($attrs, $script['attributes']);
                }
                $attrsString = $this->buildAttributes($attrs);

                $ready[] = '<link ' . $attrsString . ' />';
            }
        }
        if ($ready) {
            return implode("\r\n", $ready);
        }
        return '';
    }

    /**
     * It takes an array of scripts and returns a string of HTML script tags
     */
    public function scripts(): string
    {
        $ready = [];
        if ($this->scripts) {
            foreach ($this->scripts as $script) {
                $attrs = [
                    'id' => $script['id'],
                    'src' => $script['src'],
                ];
                if (isset($script['attributes']) and is_array($script['attributes'])) {
                    $attrs = array_merge($attrs, $script['attributes']);
                }
                $attrsString = $this->buildAttributes($attrs);
                $ready[] = '<script ' . $attrsString . '></script>';
            }
        }


        if ($ready) {
            return implode("\r\n", $ready);
        }
        return '';
    }


    /**
     * It returns the custom head tags string.
     */
    public function customHeadTags(): string
    {
        if ($this->customHeadTags) {
            return implode("\n", $this->customHeadTags);
        }

        return '';
    }


    /**
     * It takes an array of attributes and returns a string of HTML attributes
     *
     * @param array attributes An array of attributes to add to the tag.
     *
     * @return string A string of HTML attributes.
     *
     * @from  https://stackoverflow.com/a/48733854/731166
     */
    private function buildAttributes(array $attributes): string
    {
        if (empty($attributes)) {
            return '';
        }
        if (!is_array($attributes)) {
            return $attributes;
        }

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

}
