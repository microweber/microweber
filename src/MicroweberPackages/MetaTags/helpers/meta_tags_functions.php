<?php

if (!function_exists('meta_tags_head')) {
    function meta_tags_head(): string
    {
        return \MicroweberPackages\MetaTags\Facades\FrontendMetaTags::getHeadMetaTags();
    }
}

if (!function_exists('meta_tags_head_add')) {
    function meta_tags_head_add($src): string
    {
        return template_head($src);
    }
}

if (!function_exists('meta_tags_footer')) {
    function meta_tags_footer(): string
    {
        return \MicroweberPackages\MetaTags\Facades\FrontendMetaTags::getFooterMetaTags();
    }
}

if (!function_exists('meta_tags_footer_add')) {
    function meta_tags_footer_add($src): string
    {
        return template_foot($src);
    }
}

/**
 * @deprecated Use meta_tags_head() instead.
 * @see meta_tags_head()
 */
if (!function_exists('mw_header_scripts')) {
    function mw_header_scripts(): string
    {
        return meta_tags_head();
    }
}

/**
 * @deprecated Use footer_meta_tags() instead.
 * @see footer_meta_tags()
 */
if (!function_exists('mw_footer_scripts')) {
    function mw_footer_scripts(): string
    {
        return meta_tags_footer();
    }
}

/**
 * @deprecated
 */
if (!function_exists('mw_admin_header_scripts')) {
    function mw_admin_header_scripts(): string
    {
        return \MicroweberPackages\MetaTags\Facades\AdminMetaTags::getHeadMetaTags();
    }
}

/**
 * @deprecated
 */
if (!function_exists('mw_admin_footer_scripts')) {
    function mw_admin_footer_scripts(): string
    {
        return \MicroweberPackages\MetaTags\Facades\AdminMetaTags::getFooterMetaTags();
    }
}

/**
 * @deprecated
 */
if (!function_exists('admin_head')) {
    function admin_head($script_src)
    {
        return app()->template_manager->admin_head($script_src);
    }
}

if (!function_exists('template_head')) {
    function template_head($script_src)
    {
        return app()->template_manager->head($script_src);
    }
}

if (!function_exists('template_foot')) {
    function template_foot($script_src)
    {
        return app()->template_manager->foot($script_src);
    }
}

/**
 * @internal
 */
if (!function_exists('template_headers_src')) {
    function template_headers_src()
    {
        return app()->template_manager->head(true);
    }
}

/**
 * @deprecated
 */
if (!function_exists('template_stack_add')) {
    function template_stack_add($src, $group = 'default')
    {
        return app()->template_manager->stack_add($src, $group);
    }
}

/**
 * @deprecated
 */
if (!function_exists('template_stack_display')) {
    function template_stack_display($group = 'default')
    {
        return app()->template_manager->stack_display($group);
    }
}
