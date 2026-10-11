<?php

namespace MicroweberPackages\Template\Services\DesignSystem\Adapters;

/**
 * TemplateVarsAdapter
 *
 * Abstract bridge that maps canonical --mw-* CSS custom properties
 * to whatever namespace a concrete template actually uses in its
 * compiled CSS.
 *
 * Each template (Big, Bootstrap, ...) provides its own subclass that
 * defines the var-mapping table.
 */
abstract class TemplateVarsAdapter
{
    /**
     * Return the template identifier this adapter handles (e.g. "big", "bootstrap").
     */
    abstract public function templateName(): string;

    /**
     * Return the CSS var prefix this template uses (e.g. "--mw-" or "--bs-").
     */
    abstract public function varPrefix(): string;

    /**
     * Return the mapping from canonical --mw-* property names to
     * this template's CSS custom-property names.
     *
     * Example: ['--mw-primary-color' => '--bs-primary']
     *
     * If a canonical property maps to the same name (identity), it
     * does NOT need to be listed — the default passthrough applies.
     *
     * @return array<string, string>
     */
    abstract public function propertyMap(): array;

    /**
     * Convert canonical palette properties (--primaryColor, etc. from
     * the legacy color-palettes.json) into this template's CSS vars.
     *
     * @param array<string, string> $paletteProperties
     * @return array<string, string>
     */
    abstract public function mapPaletteToVars(array $paletteProperties): array;

    /**
     * Convert a style pack's --mw-* properties to this template's vars.
     *
     * @param array<string, string> $mwProperties  key=--mw-*, value=color/size
     * @return array<string, string>               key=template var, value=color/size
     */
    public function mapStylePackToVars(array $mwProperties): array
    {
        $map = $this->propertyMap();
        $result = [];

        foreach ($mwProperties as $canonicalVar => $value) {
            if (isset($map[$canonicalVar])) {
                $result[$map[$canonicalVar]] = $value;
            } else {
                // passthrough — the template uses the same var name
                $result[$canonicalVar] = $value;
            }
        }

        return $result;
    }

    /**
     * Generate a CSS :root block from the mapped properties.
     */
    public function renderCssVars(array $properties): string
    {
        if (empty($properties)) {
            return '';
        }

        $lines = [':root {'];
        foreach ($properties as $var => $value) {
            $var = $this->escapeCssProperty($var);
            $value = $this->escapeCssValue($value);
            $lines[] = "    {$var}: {$value};";
        }
        $lines[] = '}';

        return implode("\n", $lines);
    }

    /**
     * Convenience: take a palette's properties, map them, and render CSS.
     */
    public function renderPaletteCss(array $paletteProperties): string
    {
        return $this->renderCssVars($this->mapPaletteToVars($paletteProperties));
    }

    /**
     * Convenience: take a style-pack's --mw-* properties, map, render CSS.
     */
    public function renderStylePackCss(array $mwProperties): string
    {
        return $this->renderCssVars($this->mapStylePackToVars($mwProperties));
    }

    /**
     * Sanitise a CSS custom-property name.
     */
    protected function escapeCssProperty(string $prop): string
    {
        return preg_replace('/[^a-zA-Z0-9\-]/', '', $prop);
    }

    /**
     * Basic sanitisation for a CSS value (prevent injection).
     */
    protected function escapeCssValue(string $value): string
    {
        // Strip control chars and semicolons to prevent injection
        return preg_replace('/[;\{\}]/', '', $value);
    }
}