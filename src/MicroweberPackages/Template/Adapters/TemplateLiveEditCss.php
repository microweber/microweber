<?php

namespace MicroweberPackages\Template\Adapters;

use Illuminate\Support\Facades\App;

class TemplateLiveEditCss
{
    public function getLiveEditCssSaveFolder($the_active_site_template)
    {
        $live_edit_css_folder = userfiles_path() . 'css' . DS . $the_active_site_template . DS;
        $live_edit_css_folder = normalize_path($live_edit_css_folder, true);
        return $live_edit_css_folder;
    }

    public function getLiveEditCssPath($the_active_site_template, $check_exists = true)
    {
        $live_edit_css_folder = $this->getLiveEditCssSaveFolder($the_active_site_template);
        $custom_live_edit = $live_edit_css_folder . $this->getLiveEditCssFilename();
        $custom_live_edit_multisite = false;
        if (mw_is_multisite()) {
            $custom_live_edit_multisite = $live_edit_css_folder . $this->getLiveEditCssFilenameMultisite();
            $custom_live_edit_multisite = normalize_path($custom_live_edit_multisite, false);
        }

        $custom_live_edit = normalize_path($custom_live_edit, false);
        if ($check_exists) {
            if ($custom_live_edit_multisite and is_file($custom_live_edit_multisite)) {
                return $custom_live_edit_multisite;
            } else if ($custom_live_edit and is_file($custom_live_edit)) {
                return $custom_live_edit;
            }
        } else {
            if ($custom_live_edit_multisite) {
                return $custom_live_edit_multisite;
            } else if ($custom_live_edit) {
                return $custom_live_edit;
            }
        }
        return false;
    }

    public function getLiveEditCssFilename()
    {

        $live_edit_filename = 'live_edit.css';

        return $live_edit_filename;
    }

    public function getLiveEditCssFilenameMultisite()
    {
        $environment = App::environment();
        $checkMultisite = mw_is_multisite();

        $live_edit_filename = 'live_edit.css';
        if ($checkMultisite) {
            $live_edit_filename = 'live_edit_' . trim($environment) . '.css';
        }
        return $live_edit_filename;
    }

    public function getLiveEditCssUrl($the_active_site_template = null)
    {

        if (!$the_active_site_template) {
            $the_active_site_template = app()->template->templateAdapter->getTemplateFolderName();
        }

        $custom_live_edit_css_path = $this->getLiveEditCssPath($the_active_site_template);
        $liv_ed_css = '';
        if (is_file($custom_live_edit_css_path)) {
            $custom_live_editmtime = filemtime($custom_live_edit_css_path);
            $custom_live_edit_link = app()->url_manager->link_to_file($custom_live_edit_css_path);
            $live_edit_css_url = $custom_live_edit_link . '?version=' . $custom_live_editmtime;

            return $live_edit_css_url;
        }
    }


    public function saveLiveEditCssContent($css_cont, $template = null)
    {
        if (!$template) {
            $template = app()->template->templateAdapter->getTemplateFolderName();
        }
        $css_cont_new = $css_cont;
        $custom_live_edit_css_path = $this->getLiveEditCssPath($template,false);

        if (mw_is_multisite()) {
            $live_edit_css_folder = $this->getLiveEditCssSaveFolder($template);
            $custom_live_edit_multisite = $live_edit_css_folder . $this->getLiveEditCssFilenameMultisite();
            $custom_live_edit_css_path = normalize_path($custom_live_edit_multisite, false);
        }


        if ($css_cont_new != '') {
            $css_cont_new = str_ireplace('././media/', userfiles_url() . 'media/', $css_cont_new);
            $css_cont_new = str_ireplace(userfiles_url(), '../../', $css_cont_new);
        }

        $option = array();
        $option['option_value'] = $css_cont_new;
        $option['option_key'] = 'template_css';
        $option['option_group'] = 'template_' . $template;
        save_option($option);

        if ($css_cont_new != '' and $css_cont_new) {
            file_put_contents($custom_live_edit_css_path, $css_cont_new);
        } else if ($css_cont_new == '' and is_file($custom_live_edit_css_path)) {
            file_put_contents($custom_live_edit_css_path, '');
        }

    }


}
