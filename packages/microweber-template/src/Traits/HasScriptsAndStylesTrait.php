<?php

namespace MicroweberPackages\Template\Traits;

trait HasScriptsAndStylesTrait
{
    public array $scripts = [];
    public array $styles = [];
    public array $customHeadTags = [];
    public array $customFooterTags = [];

    /**
     * Generates the head tags for the page.
     *
     * @return string The concatenated head tags.
     * @example
     * echo $this->headTags();
     */
    public function headTags(): string
    {
        $tags = [];

        $tags[] = $this->styles();
        $tags[] = $this->scripts();
        $tags[] = $this->customHeadTags();

        return implode("\r\n", $tags);
    }

    /**
     * Generates the footer tags for the page.
     *
     * @return string The concatenated footer tags.
     * @example
     * echo $this->footerTags();
     */
    public function footerTags(): string
    {
        $tags = [];
        $tags[] = $this->styles('footer');
        $tags[] = $this->scripts('footer');
        $tags[] = $this->customFooterTags();

        return implode("\r\n", $tags);
    }

    /**
     * Adds a CSS stylesheet url to the page.
     *
     * @param string $id The id of the style.
     * @param string $src The url of the stylesheet.
     * @param array $attributes An array of attributes to add to the script tag.
     * @param string $placement The placement of the style, either 'head' or 'footer'.
     * @example
     * $this->addStyle('main-style', '/css/main.css', ['media' => 'all'], 'head');
     */
    public function addStyle(string $id, string $src, array $attributes = [], string $placement = 'head'): void
    {
        $this->styles[] = [
            'id' => $id,
            'src' => $src,
            'attributes' => $attributes,
            'placement' => $placement
        ];
    }

    /**
     * Removes a style from the styles array by id.
     *
     * @param string $id The id of the style you want to remove.
     * @example
     * $this->removeStyle('main-style');
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
     * Adds a script to the scripts array.
     *
     * @param string $id The id of the script.
     * @param string $src The source of the script.
     * @param array $attributes An array of attributes to add to the script tag.
     * @param string $placement The placement of the script, either 'head' or 'footer'.
     * @example
     * $this->addScript('main-script', '/js/main.js', ['async' => true], 'footer');
     */
    public function addScript(string $id, string $src, array $attributes = [], string $placement = 'head'): void
    {
        $this->scripts[] = [
            'id' => $id,
            'src' => $src,
            'attributes' => $attributes,
            'placement' => $placement
        ];
    }

    /**
     * Removes a script from the scripts array.
     *
     * @param string $id The id of the script to remove.
     * @example
     * $this->removeScript('main-script');
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
     * Adds a custom HTML tag to the head of the page.
     *
     * @param string $html The HTML to add to the head tag.
     * @example
     * $this->addCustomHeadTag('<meta name="description" content="Example">');
     */
    public function addCustomHeadTag(string $html): void
    {
        $this->customHeadTags[] = $html;
    }

    /**
     * Adds a custom HTML tag to the footer of the page.
     *
     * @param string $html The HTML to add to the footer tag.
     * @example
     * $this->addCustomFooterTag('<script src="/js/footer.js"></script>');
     */
    public function addCustomFooterTag(string $html): void
    {
        $this->customFooterTags[] = $html;
    }

    /**
     * It takes the array of stylesheets and builds the HTML for them.
     *
     * @param string $placement The placement of the styles, either 'head' or 'footer'.
     * @return string A string of HTML code.
     * @example
     * echo $this->styles('head');
     */
    public function styles(string $placement = 'head'): string
    {
        $ready = [];

        if ($this->styles) {
            foreach ($this->styles as $script) {
                if ($script['placement'] != $placement) {
                    continue;
                }

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
     * It takes an array of scripts and returns a string of HTML script tags.
     *
     * @param string $placement The placement of the scripts, either 'head' or 'footer'.
     * @return string A string of HTML script tags.
     * @example
     * echo $this->scripts('footer');
     */
    public function scripts($placement = 'head'): string
    {
        $ready = [];
        if ($this->scripts) {
            foreach ($this->scripts as $script) {
                if ($script['placement'] != $placement) {
                    continue;
                }
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
     *
     * @return string The custom head tags.
     * @example
     * echo $this->customHeadTags();
     */
    public function customHeadTags(): string
    {
        if ($this->customHeadTags) {
            return implode("\n", $this->customHeadTags);
        }

        return '';
    }

    /**
     * It returns the custom footer tags string.
     *
     * @return string The custom footer tags.
     * @example
     * echo $this->customFooterTags();
     */
    public function customFooterTags(): string
    {
        if ($this->customFooterTags) {
            return implode("\n", $this->customFooterTags);
        }

        return '';
    }

    /**
     * It takes an array of attributes and returns a string of HTML attributes.
     *
     * @param array $attributes An array of attributes to add to the tag.
     * @return string A string of HTML attributes.
     * @example
     * echo $this->buildAttributes(['class' => 'btn', 'id' => 'submit']);
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
            if (is_int($key)) {
                $attributePairs[] = $val;
            } else {
                $val = htmlspecialchars($val, ENT_QUOTES);
                $attributePairs[] = "{$key}=\"{$val}\"";
            }
        }

        return join(' ', $attributePairs);
    }
}
