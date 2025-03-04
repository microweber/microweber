<?php

namespace MicroweberPackages\Install;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Backup\SessionStepper;
use MicroweberPackages\Option\Models\Option;
use Modules\Content\Models\Content;
use Modules\Menu\Models\Menu;
use Modules\Restore\Restore;

class TemplateInstaller
{
    public $logger = null;
    public $installDefaultContent = null;
    public $selectedTemplate = null;
    public $language = 'en';

    public function setLanguage($abr)
    {
        $this->language = $abr;
    }

    public function setInstallDefaultContent($installDefaultContent)
    {
        $this->installDefaultContent = $installDefaultContent;
    }

    public function setSelectedTemplate($selectedTemplate)
    {
        $this->selectedTemplate = $selectedTemplate;
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;
    }


    public function run()
    {
        $this->log('Performing template install');
        $this->log('Installing template: ' . $this->selectedTemplate);

        $selected_template = $this->selectedTemplate ? $this->selectedTemplate : Config::get('microweber.install_default_template');
        $default_content = $this->installDefaultContent ? $this->installDefaultContent : Config::get('microweber.install_default_content');
        $this->setDefaultTemplate($selected_template);
        $create_default_fallback = false;
        if ($default_content) {

            $this->log('Installing default content for template: ' . $selected_template);
            $have = $this->installTemplateContent($selected_template);
            if ($have == false) {
                $create_default_fallback = true;
            }
        }

        if ($create_default_fallback) {
            $this->createDefaultContent();
        }

        return true;
    }

    public function setDefaultTemplate($template_name)
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

    public function installTemplateContent($template_name)
    {

        $this->log('Installing default content for template: ' . $template_name);

        $default_content_folder = mw_includes_path() . 'install' . DIRECTORY_SEPARATOR;
        $default_content_file = $default_content_folder . 'mw_default_content.zip';

        if (($template_name)) {
            if (function_exists('templates_path')) {
                $template_dir = templates_dir() . DS . $template_name;
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
            // ob_start();
            $sessionId = SessionStepper::generateSessionId(1);
            $manager = new Restore();
            $manager->setSessionId($sessionId);
            $manager->setFile($default_content_file);
         //   $manager->setBatchImporting(false);
            $manager->setToDeleteOldContent(false);
            $manager->setOvewriteById(true);

            if ($this->language) {
                $manager->setLanguage($this->language);
            }
            $manager->start();

            // ob_get_clean();
            return true;
        } else {
            return false;
        }
    }

    public function createDefaultContent()
    {
        if (!Schema::hasTable('content')) {
            return;
        }

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
