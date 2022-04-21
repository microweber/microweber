<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/26/2021
 * Time: 11:47 AM
 */

namespace MicroweberPackages\CustomField\Fields\Traits;


trait TemplateLoader
{

    public function getTemplateFilesByType($data, $type)
    {
        $preview_file = false;

        $template_name = $this->getTemplateName($data);
        $default_template_name = $this->getDefaultTemplateName();

        $ovewrite_templates_path = app()->template->dir() . 'modules' . DS . 'custom_fields' . DS . 'templates';
        $original_tempaltes_path = modules_path() . 'custom_fields' . DS . 'templates';

        // Try to open overwrite template files
        $overwrite_template_file_preview = $ovewrite_templates_path . DS . $template_name . DS . $type . '.php';

        // Try to open original template files
        $original_template_file_preview = $original_tempaltes_path . DS . $template_name . DS . $type . '.php';

        // Get default tempalte files
        $default_template_file_preview = $original_tempaltes_path . DS . $default_template_name . DS . $type . '.php';

        // Try to get overwrite template file
        if (is_file($overwrite_template_file_preview)) {
            $preview_file = $overwrite_template_file_preview;
        }

        // Try to get template file for current theme
        if (!$preview_file) {
            if (is_file($original_template_file_preview)) {
                $preview_file = $original_template_file_preview;
            }
        }

        // Get default template file
        if (!$preview_file) {
            if (is_file($default_template_file_preview)) {
                $preview_file = $default_template_file_preview;
            }
        }

        return $preview_file;
    }

    public function getTemplateFiles($data)
    {
        $preview_file = $this->getTemplateFilesByType($data, $data['type']);
        if (!$preview_file) {
            $preview_file = $this->getTemplateFilesByType($data, 'text');
        }

        $settings_file = modules_path() . DS . 'microweber' . DS . 'custom_fields' . DS . $data['type'] . '_settings.php';
        if (!is_file($settings_file)) {
            $settings_file = modules_path() . DS . 'microweber' . DS . 'custom_fields' . DS . 'text_settings.php';
        }

        $settings_file = normalize_path($settings_file, FALSE);
        $preview_file = normalize_path($preview_file, FALSE);

        return array('preview_file' => $preview_file, 'settings_file' => $settings_file);
    }

    public function getTemplateName($data)
    {
        $template_name = false;
        $template_from_option = false;
        $template_from_html_option = false;

        if (isset($data['params']['id'])) {
            if (get_option('data-template', $data['params']['id'])) {
                $template_from_option = get_option('data-template', $data['params']['id']);
            }
        }

        if (isset($data['id'])) {
            if (get_option('data-template', $data['id'])) {
                $template_from_option = get_option('data-template', $data['id']);
            }
        }

        if (isset($data['params']['template']) && $data['params']['template']) {
            $template_from_html_option = $data['params']['template'];
        }

        // Get from html option
        if (!$template_from_option && $template_from_html_option) {
            $template_name = $template_from_html_option;
        }

        if ($template_from_option) {
            $template_name = $template_from_option;
        }

        if ($template_name) {
            $template_name_exp = explode('/', $template_name);
            if (!empty($template_name_exp[0])) {
                $template_name = $template_name_exp[0];
            }
        }

        if (!$template_name) {
            return $this->getDefaultTemplateName();
        }

        return $template_name;
    }

    public function getDefaultTemplateName()
    {
        $template_name = false;

        if (!$template_name) {
            $template_name = template_framework();
        }

        if (!$template_name) {
            $template_name = 'mw-ui';
        }

        return $template_name;
    }
}
