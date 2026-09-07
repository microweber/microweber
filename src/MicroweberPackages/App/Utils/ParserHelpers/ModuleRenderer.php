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

        // No-wrap AND as-element both return the content with NO wrapper.
        // task-2026-09-07-aselement — as_element fully unwraps to the legacy
        // parser behavior: the module tag is replaced by its content directly
        // (the module's own root element — e.g. <h1 class="element"> for a
        // title — becomes the editable element), with no surrounding div.
        if ($noWrap || $asElement) {
            return $content;
        }

        $moduleClass = $this->moduleCssClass($moduleName);

        // Build CSS classes
        $cssClass = trim('module ' . $moduleClass . ' ' . $userClass);

        // Build the opening tag
        $html = '<' . $htmlTag . ' class="' . $cssClass . '"';
        $html .= ' id="' . htmlspecialchars($moduleId, ENT_QUOTES) . '"';

        // Add remaining attributes. 'class'/'id' are already emitted above, and
        // the raw 'type' is dropped because the wrapper already conveys the
        // module type via its module-<type> class and the data-type attribute
        // (a bare type="" on a <div> is meaningless / invalid).
        // Control flags consumed by the parser — never emit them as HTML attrs
        // on the wrapper (class/id/type handled above; as_element / no_wrap
        // drive rendering and are meaningless on the output tag).
        $controlFlags = ['class', 'id', 'type', 'as_element', 'no_wrap'];
        foreach ($attrs as $name => $value) {
            if (in_array($name, $controlFlags, true)) {
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
     *
     * task-2026-09-07-aselement — the legacy parser detected as_element ONLY
     * from the `module-as-element` CSS class. The Live-Edit insert flow now
     * supplies the flag as an `as_element="true"` (or `data-as-element`)
     * ATTRIBUTE on the <module> tag, which the class-only check missed — so an
     * inserted title/text element rendered as a plain `module` wrapper instead
     * of unwrapping. Detect the attribute forms too.
     */
    public function isAsElement(array $attrs): bool
    {
        // Legacy form: the `module-as-element` marker class.
        if (isset($attrs['class']) && strpos($attrs['class'], 'module-as-element') !== false) {
            return true;
        }
        // Current insert form: the `as_element` attribute. array_key_exists (not
        // isset) so a bare-flag attribute parsed to null still counts as present.
        if (array_key_exists('as_element', $attrs) && $this->isTruthyFlag($attrs['as_element'])) {
            return true;
        }
        return false;
    }

    /**
     * A tag attribute flag is truthy unless it is an explicit false-ish value.
     * `as_element` (bare, HTML shorthand), `as_element="true"`, `="1"` → true;
     * `="false"`, `="0"`, `=""` → false.
     */
    private function isTruthyFlag($value): bool
    {
        if ($value === true || $value === null) {
            return true;
        }
        $v = strtolower(trim((string) $value));
        return !in_array($v, ['', '0', 'false', 'no', 'off'], true);
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
}
