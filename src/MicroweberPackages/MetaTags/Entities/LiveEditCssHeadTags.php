<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

class LiveEditCssHeadTags implements TagInterface, \Stringable
{
    public function toHtml(): string
    {

//        // @todo this is not working correctly
//        // it appends in header and adds div , must be in after body
//        return '';


        $the_active_site_template = app()->template->templateAdapter->getTemplateFolderName();

        $live_edit_css_folder = userfiles_path() . 'css' . DS . $the_active_site_template . DS;
        $live_edit_url_folder = userfiles_url() . 'css/' . $the_active_site_template . '/';
        $custom_live_edit = $live_edit_css_folder . 'live_edit.css';

        $custom_live_edit = normalize_path($custom_live_edit, false);

        $liv_ed_css = '';
        if (is_file($custom_live_edit)) {
            $custom_live_editmtime = filemtime($custom_live_edit);
            $liv_ed_css = '<link rel="stylesheet" href="' . $live_edit_url_folder . 'live_edit.css?version=' . $custom_live_editmtime . '" id="mw-template-settings"  crossorigin="anonymous" referrerpolicy="no-referrer" type="text/css" />';
        }

        return $liv_ed_css;
    }

    public function getPlacement(): string
    {
        return Meta::PLACEMENT_FOOTER;
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }


    public function toArray(): array
    {
        return [
            'type' => 'live_edit_css_head_tags',
        ];
    }
}
