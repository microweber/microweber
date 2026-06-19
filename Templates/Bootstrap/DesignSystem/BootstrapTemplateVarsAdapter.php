<?php

namespace Templates\Bootstrap\DesignSystem;

use MicroweberPackages\Template\Services\DesignSystem\Adapters\TemplateVarsAdapter;

/**
 * BootstrapTemplateVarsAdapter
 *
 * Maps canonical --mw-* CSS custom properties to the Bootstrap template's
 * variable namespace.  The Bootstrap template uses a mix of --mw-* vars
 * (shared convention) and native Bootstrap --bs-* vars.
 *
 * When a style pack is applied, this adapter produces both the --mw-*
 * vars (consumed by the template's design-styles.scss) AND the --bs-*
 * equivalents so that native Bootstrap components automatically pick up
 * the palette.
 */
class BootstrapTemplateVarsAdapter extends TemplateVarsAdapter
{
    public function templateName(): string
    {
        return 'bootstrap';
    }

    public function varPrefix(): string
    {
        return '--bs-';
    }

    /**
     * Style-pack --mw-* → Bootstrap --bs-* mapping.
     *
     * Only the vars that have a Bootstrap equivalent are listed.
     * The adapter will also pass through the original --mw-* vars
     * unchanged (identity mapping in the parent class).
     */
    public function propertyMap(): array
    {
        return [
            // We map to --bs-* equivalents for native Bootstrap component styling
            '--mw-primary-color'             => '--bs-primary',
            '--mw-secondary'                 => '--bs-secondary',
            '--mw-background-color'          => '--bs-body-bg',
            '--mw-body-color'                => '--bs-body-color',
            '--mw-link-color'                => '--bs-link-color',
            '--mw-link-hover-color'          => '--bs-link-hover-color',
            '--mw-btn-background-color'      => '--bs-btn-bg',
            '--mw-btn-text-color'            => '--bs-btn-color',
            '--mw-btn-border-color'          => '--bs-btn-border-color',
            '--mw-form-control-background'   => '--bs-form-control-bg',
            '--mw-form-control-border-color' => '--bs-border-color',
            '--mw-heading-color'             => '--bs-heading-color',
        ];
    }

    /**
     * Override: for Bootstrap we emit BOTH the --bs-* mapped vars AND
     * the original --mw-* vars (the template's SCSS depends on both).
     */
    public function mapStylePackToVars(array $mwProperties): array
    {
        $bsMap = $this->propertyMap();
        $result = [];

        foreach ($mwProperties as $canonicalVar => $value) {
            // Always keep the --mw-* version
            $result[$canonicalVar] = $value;

            // Also add the --bs-* alias if one exists
            if (isset($bsMap[$canonicalVar])) {
                $result[$bsMap[$canonicalVar]] = $value;
            }
        }

        return $result;
    }

    /**
     * Map legacy palette property names (--primaryColor, etc.) from
     * color-palettes.json into the Bootstrap template's CSS vars.
     *
     * We first convert to --mw-*, then to --bs-* (plus keep --mw-*).
     */
    public function mapPaletteToVars(array $paletteProperties): array
    {
        $paletteToMw = $this->paletteToMwMap();
        $mwProperties = [];

        foreach ($paletteProperties as $paletteProp => $value) {
            if (isset($paletteToMw[$paletteProp])) {
                $mwProperties[$paletteToMw[$paletteProp]] = $value;
            }
        }

        // Now map through the style pack mapper (adds --bs-* aliases)
        return $this->mapStylePackToVars($mwProperties);
    }

    /**
     * Mapping from legacy palette property names to --mw-* vars.
     * (Same as Big — the canonical intermediate representation.)
     *
     * @return array<string, string>
     */
    protected function paletteToMwMap(): array
    {
        return [
            '--primaryColor'                 => '--mw-primary-color',
            '--links'                        => '--mw-link-color',
            '--background'                   => '--mw-background-color',
            '--secondary'                    => '--mw-secondary',
            '--textDark'                     => '--mw-textDark',
            '--textLight'                    => '--mw-textLight',
            '--section'                      => '--mw-section-background-color',
            '--headerBg'                     => '--mw-header-background-color',
            '--topHeaderBg'                  => '--mw-top-header-background-color',
            '--topHeaderElements'            => '--mw-top-header-primary-color',
            '--menuColor'                    => '--mw-header-link-color',
            '--menuHoverColor'               => '--mw-header-link-hover-color',
            '--btnTextColor'                 => '--mw-btn-text-color',
            '--btnBackground'                => '--mw-btn-background-color',
            '--btnBackgroundHover'            => '--mw-btn-background-hover-color',
            '--btnTextHoverColor'             => '--mw-btn-text-hover-color',
            '--btnBorderColor'               => '--mw-btn-border-color',
            '--btnSecondaryBackground'        => '--mw-btn-secondary-background-color',
            '--btnSecondaryBackgroundHover'   => '--mw-btn-secondary-background-hover-color',
            '--btnSecondaryText'             => '--mw-btn-secondary-text-color',
            '--btnSecondaryTextHoverColor'    => '--mw-btn-secondary-text-hover-color',
            '--btnOutlineBackground'          => '--mw-btn-outline-background-color',
            '--btnOutlineBackgroundHover'     => '--mw-btn-outline-background-hover-color',
            '--btnOutlineText'               => '--mw-btn-outline-text-color',
            '--btnOutlineTextHoverColor'      => '--mw-btn-outline-text-hover-color',
            '--formControlBorderColor'       => '--mw-form-control-border-color',
            '--formControlPlaceholderColor'   => '--mw-form-control-placeholder-color',
            '--formControlBackground'        => '--mw-form-control-background',
            '--formControlTextColor'          => '--mw-form-control-text-color',
            '--formControlTextHoverColor'     => '--mw-form-control-text-hover-color',
            '--formLabel'                    => '--mw-form-label-color',
            '--footerBg'                     => '--mw-footer-background-color',
            '--footerTextColor'              => '--mw-footer-text-color',
            '--footerLinkColor'              => '--mw-footer-link-color',
            '--footerHoverLinkColor'         => '--mw-footer-hover-link-color',
            '--headerBtnColor'               => '--mw-top-header-button-text-color',
            '--navFontColor'                 => '--mw-body-color',
            '--navLinkFontColor'             => '--mw-header-link-color',
            '--navLinkHoverColor'            => '--mw-header-link-hover-color',
            '--navLinkActiveColor'           => '--mw-header-link-hover-color',
            '--paragraphColor'               => '--mw-paragraph-color',
            '--headingColor'                 => '--mw-heading-color',
            '--textTransform'                => '--mw-text-transform',
            '--textOnDarkBackground'         => '--text-on-dark-background',
        ];
    }
}