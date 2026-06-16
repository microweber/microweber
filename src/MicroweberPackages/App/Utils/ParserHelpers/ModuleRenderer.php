<?php

namespace MicroweberPackages\App\Utils\ParserHelpers;

/**
 * Renders a resolved module tag into its HTML wrapper.
 *
 * Responsible for:
 *  - Building the wrapper div (or custom HTML tag)
 *  - Inserting the module content
 *  - Setting CSS classes, IDs, data attributes
 *  - Handling no_wrap modules (content only, no wrapper)
 *  - Ensuring unknown/empty type produces nothing (no placeholder leak)
 */
class ModuleRenderer
{
    /**
     * Render a module into its HTML wrapper.
     *
     * @param string      $moduleName     The module type (e.g. "btn", "layouts")
     * @param string      $moduleId       The allocated module ID
     * @param array       $attrs          All parsed attributes
     * @param string      $content        The rendered module content
     * @param string      $htmlTag        The wrapper element (default "div")
     * @param bool        $noWrap         If true, return content without wrapper
     * @param string      $userClass      User-defined CSS classes from the original tag
     * @param bool        $asElement      If true, render as layout-element instead of module
     * @return string
     */
    public function render(
        string $moduleName,
        string $moduleId,
        array  $attrs,
        string $content,
        string $htmlTag = 'div',
        bool   $noWrap = false,
        string $userClass = '',
        bool   $asElement = false
    ): string {
        // Empty/unknown type → empty output (fixes placeholder leak bug)
        if ($moduleName === '') {
            return '';
        }

        // No-wrap: return just the content
        if ($noWrap) {
            return $content;
        }

        $moduleClass = $this->moduleCssClass($moduleName);
        $moduleNameUrl = $this->moduleNameUrl($moduleName);

        // Build CSS classes
        if ($asElement) {
            $cssClass = trim('element ' . $moduleNameUrl . ' ' . $userClass);
        } else {
            $cssClass = trim('module ' . $moduleClass . ' ' . $userClass);
        }

        // Build the opening tag
        $html = '<' . $htmlTag . ' class="' . $cssClass . '"';
        $html .= ' id="' . htmlspecialchars($moduleId, ENT_QUOTES) . '"';

        // Add remaining attributes. 'class'/'id' are already emitted above, and
        // the raw 'type' is dropped because the wrapper already conveys the
        // module type via its module-<type> class and the data-type attribute
        // (a bare type="" on a <div> is meaningless / invalid).
        foreach ($attrs as $name => $value) {
            if ($name === 'class' || $name === 'id' || $name === 'type') {
                continue;
            }
            if ($value !== null && $value !== false) {
                $html .= ' ' . $name . '="' . htmlspecialchars((string)$value, ENT_QUOTES) . '"';
            }
        }

        $html .= '>' . $content . '</' . $htmlTag . '>';

        return $html;
    }

    /**
     * Check if a module has the no_wrap flag.
     */
    public function isNoWrap(array $attrs): bool
    {
        return isset($attrs['no_wrap'])
            || isset($attrs['data-no-wrap'])
            || isset($attrs['no-wrap']);
    }

    /**
     * Check if a module is an "as element" module.
     */
    public function isAsElement(array $attrs): bool
    {
        if (isset($attrs['class']) && strpos($attrs['class'], 'module-as-element') !== false) {
            return true;
        }
        return false;
    }

    /**
     * Generate the CSS class for a module name.
     */
    public function moduleCssClass(string $moduleName): string
    {
        $class = str_replace('/', '-', $moduleName);
        $class = str_replace('\\', '-', $class);
        $class = str_replace(' ', '-', $class);
        $class = str_replace('%20', '-', $class);
        $class = str_replace('_', '-', $class);
        return 'module-' . strtolower($class);
    }

    /**
     * Generate URL-safe module name.
     */
    private function moduleNameUrl(string $moduleName): string
    {
        $url = str_replace('/', '-', $moduleName);
        $url = str_replace('\\', '-', $url);
        $url = str_replace(' ', '-', $url);
        return strtolower($url);
    }
}
