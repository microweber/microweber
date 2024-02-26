<?php


function mw_header_scripts(): string
{
    return \MicroweberPackages\MetaTags\Facades\FrontendMetaTags::getHeadMetaTags();
}

function mw_footer_scripts(): string
{
    return \MicroweberPackages\MetaTags\Facades\FrontendMetaTags::getFooterMetaTags();
}


function mw_admin_header_scripts(): string
{
    return \MicroweberPackages\MetaTags\Facades\AdminMetaTags::getHeadMetaTags();
}

function mw_admin_footer_scripts(): string
{
    return \MicroweberPackages\MetaTags\Facades\AdminMetaTags::getFooterMetaTags();
}
