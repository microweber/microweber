<?php

namespace MicroweberPackages\Install;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\Backup\BackupManager;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Export\SessionStepper;
use MicroweberPackages\Import\Import;
use MicroweberPackages\Menu\Menu;
use MicroweberPackages\Option\Models\Option;

class TemplateInstaller
{
    public $logger = null;
    public $language = 'en';

    public function setLanguage($abr) {
        $this->language = $abr;
    }

    public function run()
    {
        $this->log('Performing template install');

        $selected_template = Config::get('microweber.install_default_template');
        $default_content = Config::get('microweber.install_default_template_content');
        $this->setDefaultTemplate($selected_template);
        $create_default = true;
        if ($default_content) {
            $have = $this->installTemplateContent($selected_template);
            if ($have == false) {
                $create_default = true;
            }
        }

        if ($create_default) {
            $this->createDefaultContent();
        }

        return true;
    }

    private function setDefaultTemplate($template_name)
    {
        $this->log('Setting default template: ' . $template_name);

        $existing = DB::table('options')->where('option_key', 'current_template')
            ->where('option_group', 'template')->first();

        if ($existing != false) {
            $option = Option::find($existing->id);
        } else {
            $option = new Option();
        }
        $option->option_key = 'current_template';
        $option->option_group = 'template';
        $option->option_value = $template_name;
        $option->is_system = 1;
        $option->save();


        app()->option_manager->clear_memory();
    }

    private function installTemplateContent($template_name)
    {

        $this->log('Installing default content for template: ' . $template_name);

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

            $sessionId = SessionStepper::generateSessionId(0);
            $manager = new Import();
            $manager->setSessionId($sessionId);
            $manager->setFile($default_content_file);
            $manager->setBatchImporting(false);
            $manager->setToDeleteOldContent(false);
            $manager->setOvewriteById(true);
            if ($this->logger) {
                $manager->setLogger($this->logger);
            }
            if ($this->language) {
                $manager->setLanguage($this->language);
            }
            $manager->start();

            ob_get_clean();
        	return true;
        } else {
        	return false;
        }
    }

    public function createDefaultContent()
    {
        $existing = Content::where('is_home', 1)->first();

        if ($existing == false) {
            $content = new Content();
            $content->title = 'Home';
            $content->url = 'home';
            $content->parent = 0;
            $content->is_home = 1;
            $content->is_active = 1;
            $content->content_type = 'page';
            $content->subtype = 'static';
            $content->layout_file = 'index.php';
            $content->save();
        }
        try {

            $existing = Menu::where('title', 'header_menu')->first();
            if (!$existing) {
                $menu = new Menu();
                $menu->title = 'header_menu';
                $menu->item_type = 'menu';
                $menu->is_active = 1;
                $menu->save();

                $menu = new Menu();
                $menu->parent_id = 1;
                $menu->content_id = 1;
                $menu->item_type = 'menu_item';
                $menu->is_active = 1;
                $menu->save();

                $menu = new Menu();
                $menu->title = 'footer_menu';
                $menu->item_type = 'menu';
                $menu->is_active = 1;
                $menu->save();

                $menu = new Menu();
                $menu->parent_id = 2;
                $menu->content_id = 1;
                $menu->item_type = 'menu_item';
                $menu->is_active = 1;
                $menu->save();
            }

        } catch (\PDOException $e) {
            return false;
        }

    }

    public function log($text)
    {
        if (is_object($this->logger) and method_exists($this->logger, 'log')) {
            $this->logger->log($text);
        }
    }

}
