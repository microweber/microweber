<?php

namespace MicroweberPackages\Template\Traits;

trait HasScriptsAndStylesTrait
{
    public array $scripts = [];
    public array $styles = [];
    public array $customHeadTags = [];

    public function addStyle($id, $src, $attributes = []): void
    {
        $this->styles[] = [
            'id' => $id,
            'src' => $src,
            'attributes' => $attributes
        ];
    }

    public function removeStyle($id): void
    {
        if ($this->styles) {
            foreach ($this->styles as $key => $item) {
                if ($item['id'] == $id) {
                    unset($this->styles[$key]);
                }
            }
        }
    }

    public function addScript($id, $src, $attributes = []): void
    {
        $this->scripts[] = [
            'id' => $id,
            'src' => $src,
            'attributes' => $attributes
        ];
    }

    public function removeScript($id): void
    {
        if ($this->scripts) {
            foreach ($this->scripts as $key => $item) {
                if ($item['id'] == $id) {
                    unset($this->scripts[$key]);
                }
            }
        }
    }

    public function addCustomHeadTag($html): void
    {
        $this->customHeadTags[] = $html;
    }

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


    public function customHeadTags(): string
    {
        if ($this->customHeadTags) {
            return implode("\n", $this->customHeadTags);
        }

        return '';
    }


    private function buildAttributes($attributes)
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


}
