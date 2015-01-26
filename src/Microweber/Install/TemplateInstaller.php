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
        if ($default_content) {
            $have = $this->installTemplateContent($selected_template);
            if ($have == false) {
                $create_default = true;
            }
        } else {
            $create_default = true;
        }
        if ($create_default) {
            $this->createDefaultContent();
        }
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
            try {
                $rest = $restore->exec_restore();
            } catch (Exception $e) {
                return false;
            }
            ob_get_clean();
            return true;
        } else {
            return false;
        }
    }

    public function createDefaultContent()
    {
        $content = new \Content();
        $content->title = "Home";
        $content->parent = 0;
        $content->is_home = 1;
        $content->is_active = 1;
        $content->content_type = "page";
        $content->subtype = "static";
        $content->layout_file = "index.php";
        $content->save();


        $menu = new \Menu();
        $menu->title = "header_menu";
        $menu->item_type = "menu";
        $menu->is_active = 1;
        $menu->save();


        $menu = new \Menu();
        $menu->parent_id = 1;
        $menu->content_id = 1;
        $menu->item_type = "menu_item";
        $menu->is_active = 1;
        $menu->save();

        $menu = new \Menu();
        $menu->title = "footer_menu";
        $menu->item_type = "menu";
        $menu->is_active = 1;
        $menu->save();


        $menu = new \Menu();
        $menu->parent_id = 2;
        $menu->content_id = 1;
        $menu->item_type = "menu_item";
        $menu->is_active = 1;
        $menu->save();

    }


}