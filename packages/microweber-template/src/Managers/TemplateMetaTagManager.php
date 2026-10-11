<?php

namespace MicroweberPackages\Template\Managers;

/**
 * Manages meta tags and HTML opening tag attributes for templates.
 *
 * This class handles the storage and rendering of meta tags and HTML attributes,
 * extracted from TemplateManager to follow Single Responsibility Principle.
 */
class TemplateMetaTagManager
{
    /**
     * @var array Stored meta tags
     */
    protected array $metaTags = [];

    /**
     * @var array Stored HTML opening tag attributes
     */
    protected array $htmlOpeningTag = [];

    /**
     * Set a meta tag value.
     *
     * @deprecated Use setMetaTag() instead
     * @param string $name The meta tag name
     * @param string|false $value The meta tag value
     * @return void
     */
    public function meta(string $name, $value = false): void
    {
        $this->setMetaTag($name, $value);
    }

    /**
     * Set a meta tag value.
     *
     * @param string $name The meta tag name
     * @param string|false $value The meta tag value
     * @return void
     */
    public function setMetaTag(string $name, $value = false): void
    {
        $this->metaTags[$name] = $value;
    }

    /**
     * Get a meta tag value.
     *
     * @param string $name The meta tag name
     * @return string|false The meta tag value or false if not set
     */
    public function getMetaTag(string $name)
    {
        return $this->metaTags[$name] ?? false;
    }

    /**
     * Get all meta tags.
     *
     * @return array All stored meta tags
     */
    public function getMetaTags(): array
    {
        return $this->metaTags;
    }

    /**
     * Clear all meta tags.
     *
     * @return void
     */
    public function clearMetaTags(): void
    {
        $this->metaTags = [];
    }

    /**
     * Set an HTML opening tag attribute.
     *
     * @deprecated Use setHtmlAttribute() instead
     * @param string $name The attribute name
     * @param string|false $value The attribute value
     * @return void
     */
    public function html_opening_tag(string $name, $value = false): void
    {
        $this->setHtmlAttribute($name, $value);
    }

    /**
     * Set an HTML opening tag attribute.
     *
     * @param string $name The attribute name
     * @param string|false $value The attribute value
     * @return void
     */
    public function setHtmlAttribute(string $name, $value = false): void
    {
        $this->htmlOpeningTag[$name] = $value;
    }

    /**
     * Get an HTML opening tag attribute value.
     *
     * @param string $name The attribute name
     * @return string|false The attribute value or false if not set
     */
    public function getHtmlAttribute(string $name)
    {
        return $this->htmlOpeningTag[$name] ?? false;
    }

    /**
     * Get all HTML opening tag attributes.
     *
     * @return array All stored HTML attributes
     */
    public function getHtmlAttributes(): array
    {
        return $this->htmlOpeningTag;
    }

    /**
     * Clear all HTML opening tag attributes.
     *
     * @return void
     */
    public function clearHtmlAttributes(): void
    {
        $this->htmlOpeningTag = [];
    }

    /**
     * Render meta tags and HTML attributes into the layout.
     *
     * @param string $layout The HTML layout content
     * @return string The modified layout with meta tags and attributes inserted
     */
    public function render(string $layout): string
    {
        $layout = $this->renderHtmlAttributes($layout);
        $layout = $this->renderMetaTags($layout);

        return $layout;
    }

    /**
     * Render HTML opening tag attributes into the layout.
     *
     * @param string $layout The HTML layout content
     * @return string The modified layout with HTML attributes inserted
     */
    protected function renderHtmlAttributes(string $layout): string
    {
        if (empty($this->htmlOpeningTag)) {
            return $layout;
        }

        $replace = '';
        foreach ($this->htmlOpeningTag as $key => $item) {
            if (is_string($item)) {
                $replace .= $key . '="' . $item . '" ';
            }
        }

        if ($replace !== '') {
            $layout = str_replace('<html ', '<html ' . $replace, $layout, $count);
        }

        return $layout;
    }

    /**
     * Render meta tags into the layout.
     *
     * @param string $layout The HTML layout content
     * @return string The modified layout with meta tags inserted
     */
    protected function renderMetaTags(string $layout): string
    {
        if (empty($this->metaTags)) {
            return $layout;
        }

        $replace = '';
        foreach ($this->metaTags as $key => $item) {
            if (is_string($item)) {
                $replace .= '<meta name="' . $key . '" content="' . $item . '">' . "\n";
            }
        }

        if ($replace !== '') {
            $layout = str_replace('<head>', '<head>' . $replace, $layout, $count);
        }

        return $layout;
    }
}
