<?php


function meta_tags_head(): string
{
    return \MicroweberPackages\MetaTags\Facades\FrontendMetaTags::getHeadMetaTags();
}

function meta_tags_head_add($src): string
{
    return template_head($src);
}


function meta_tags_footer(): string
{
    return \MicroweberPackages\MetaTags\Facades\FrontendMetaTags::getFooterMetaTags();
}
function meta_tags_footer_add($src): string
{
    return template_foot($src);
}



/**
 * @deprecated Use meta_tags_head() instead.
 * @see meta_tags_head()
 */
function mw_header_scripts(): string
{
    return meta_tags_head();
}

/**
 * @deprecated Use footer_meta_tags() instead.
 * @see footer_meta_tags()
 */
function mw_footer_scripts(): string
{
    return meta_tags_footer();
}

/**
 * @deprecated
 */
function mw_admin_header_scripts(): string
{
    return \MicroweberPackages\MetaTags\Facades\AdminMetaTags::getHeadMetaTags();
}
/**
 * @deprecated
 */
function mw_admin_footer_scripts(): string
{
    return \MicroweberPackages\MetaTags\Facades\AdminMetaTags::getFooterMetaTags();
}

/**
 * @deprecated
 */
function admin_head($script_src)
{
    return app()->template_manager->admin_head($script_src);
}



/**
 * @deprecated
 */
function template_head($script_src)
{
    return app()->template_manager->head($script_src);
}
/**
 * @deprecated
 */
function template_foot($script_src)
{
    return app()->template_manager->foot($script_src);
}


/**
 * @deprecated
 */
function template_headers_src()
{
    return app()->template_manager->head(true);
}

/**
 * @deprecated
 */
function template_stack_add($src, $group = 'default')
{
    return app()->template_manager->stack_add($src, $group);
}

/**
 * @deprecated
 */
function template_stack_display($group = 'default')
{
    return app()->template_manager->stack_display($group);
}
