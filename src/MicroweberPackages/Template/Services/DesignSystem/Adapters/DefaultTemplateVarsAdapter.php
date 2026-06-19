<?php

namespace MicroweberPackages\Template\Services\DesignSystem\Adapters;

/**
 * DefaultTemplateVarsAdapter
 *
 * The canonical, template-agnostic adapter. Templates built on the Microweber
 * CSS framework use the --mw-* custom properties natively, so the style-pack
 * property map is identity. A full mapping is provided for the legacy
 * color-palettes.json (which uses short names like --primaryColor).
 *
 * DesignSystemService uses an instance of this as the fallback when a template
 * has not registered its own adapter, so the core service is not coupled to any
 * specific template. Concrete templates (Big, Base, …) extend this and register
 * themselves from their own service providers.
 */
class DefaultTemplateVarsAdapter extends TemplateVarsAdapter
{
    public function templateName(): string
    {
        return 'default';
    }

    public function varPrefix(): string
    {
        return '--mw-';
    }

    /**
     * Style-pack --mw-* → template --mw-* mapping. Identity for framework templates.
     */
    public function propertyMap(): array
    {
        return [];
    }

    /**
     * Map legacy palette property names (--primaryColor, etc.) from
     * color-palettes.json into the canonical --mw-* CSS vars.
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
