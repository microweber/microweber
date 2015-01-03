<?php

namespace Microweber\Install;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;


use \Option;

class TemplateInstaller
{

    public function run()
    {


        $selected_template = Config::get('microweber.install_default_template');
        $default_content = Config::get('microweber.install_default_template_content');


        $this->setDefaultTemplate($selected_template);
        $this->installTemplateContent($selected_template);
        return true;


    }

    private function setDefaultTemplate($template_name)
    {

        $existing = DB::table('options')->where('option_key', 'current_template')
            ->where('option_group', 'template')->first();

        if ($existing != false) {
            $option = Option::find($existing->id);
        } else {
            $option = new Option;
        }
        $option->option_key = 'current_template';
        $option->option_group = 'template';
        $option->option_value = $template_name;
        $option->is_system = 1;
        $option->save();

    }

    private function installTemplateContent($template_name)
    {


        $default_content_folder = mw_includes_path() . 'install' . DIRECTORY_SEPARATOR;
        $default_content_file = $default_content_folder . 'mw_default_content.zip';

        if (($template_name)) {
            if (function_exists('templates_path')) {
                $template_dir = templates_path() . DS . $template_name;
                $template_dir = normalize_path($template_dir, true);
                if (is_dir($template_dir)) {
                    $template_default_content = $template_dir . 'mw_default_content.zip';


                    if (is_file($template_default_content) and is_readable($template_default_content)) {
                        $default_content_file = $template_default_content;
                        $default_content_folder = $template_dir;
                    }
                }
            }

        }


        if (is_file($default_content_file)) {
            $restore = new \Microweber\Utils\Backup();
            $restore->backups_folder = $default_content_folder;
            $restore->backup_file = 'mw_default_content.zip';
            ob_start();
            $rest = $restore->exec_restore();
            ob_get_clean();
        }
    }


}