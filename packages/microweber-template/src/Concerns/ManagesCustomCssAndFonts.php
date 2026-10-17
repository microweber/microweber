<?php

namespace MicroweberPackages\Template\Concerns;

/**
 * Extracted from ModuleManager to decompose the god class. Composed back via
 * `use`, so $this state and inter-method calls are unchanged.
 */
trait ManagesCustomCssAndFonts
{
    public function get_custom_fonts_css_content()
    {
        return $this->fontsAdapter->getFontsStylesheetCss();
    }

    public function getFonts()
    {
        return $this->fontsAdapter->getFonts();
    }

    public function get_custom_fonts_css_url()
    {
        return $this->fontsAdapter->getFontsStylesheetCssUrl();
    }

    public function get_custom_css_content()
    {
        return $this->customCssAdapter->getCustomCssContent();
    }

    public function get_custom_css()
    {
        return $this->customCssAdapter->getCustomCss();

    }

    public function get_custom_css_url()
    {
        return $this->customCssAdapter->getCustomCssUrl();
    }

    public function get_default_system_ui_css_url()
    {
        // $url = mw_includes_url() . 'default.css';
        $url = asset('vendor/microweber-packages/frontend-assets/build/default.css');
        return $url;
    }

    public function get_admin_supported_themes()
    {
        return $this->admin->getAdminTemplates();
    }

    public function get_admin_supported_theme_scss_vars($theme)
    {
        if (!$theme) {
            return;
        }
        return $this->admin->getAdminTemplateVars($theme);
    }

    public function get_admin_system_ui_css_url()
    {
        return $this->admin->getAdminCssUrl();
    }

    public function get_icon_fonts_stylesheet_url()
    {
        return $this->iconFontsAdapter->getIconFontsStylesheetUrl();
    }

    public function clear_cached_custom_css()
    {
        if ($this->customCssAdapter && method_exists($this->customCssAdapter, 'clearCache')) {
            $this->customCssAdapter->clearCache();

            return;
        }

        $userfiles_dir = userfiles_path();
        $userfiles_cache_dir = normalize_path($userfiles_dir . 'cache' . DS);
        if (!is_dir($userfiles_cache_dir)) {
            return;
        }
        $files = glob($userfiles_cache_dir . "custom_css*.css");
        if ($files) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }
    }
}
