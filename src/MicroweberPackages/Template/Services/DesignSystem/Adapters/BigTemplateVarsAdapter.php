<?php

namespace MicroweberPackages\Template\Services\DesignSystem\Adapters;

/**
 * BigTemplateVarsAdapter
 *
 * Maps canonical --mw-* variables to the Big template's CSS namespace.
 * The Big template already uses --mw-* natively, so the style-pack
 * property map is mostly identity.
 *
 * For the legacy color-palettes.json (which uses short names like
 * --primaryColor), a full mapping is provided.
 */
class BigTemplateVarsAdapter extends TemplateVarsAdapter
{
    public function templateName(): string
    {
        return 'big';
    }

    public function varPrefix(): string
    {
        return '--mw-';
    }

    /**
     * Style-pack --mw-* → Big template --mw-* mapping.
     * Big template already uses --mw-* natively, so the map is identity.
     */
    public function propertyMap(): array
    {
        // Identity — Big template uses --mw-* properties natively
        return [];
    }

    /**
     * Map legacy palette property names (--primaryColor, etc.) from
     * color-palettes.json into the Big template's --mw-* CSS vars.
     */
    public function mapPaletteToVars(array $paletteProperties): array
    {
        $map = $this->paletteToMwMap();
        $result = [];

        foreach ($paletteProperties as $paletteProp => $value) {
            if (isset($map[$paletteProp])) {
                $result[$map[$paletteProp]] = $value;
            }
        }

        return $result;
    }

    /**
     * Mapping from legacy palette property names to --mw-* vars.
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