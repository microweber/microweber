<?php

namespace MicroweberPackages\Console\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\LaravelModules\LaravelModule;
use MicroweberPackages\LaravelTemplates\LaravelTemplate;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class VendorAssetsSymlinkCommand extends Command
{
    protected $name = 'microweber:vendor-assets-symlink';
    protected $description = 'Symlinks the system assets on your website to their vendor locations';

    public function __construct()
    {
        parent::__construct();
        $this->addOption('restore', 'r', InputOption::VALUE_NONE, 'Remove all vendor asset symlinks');
    }

    public function handle()
    {
        $isRestore = $this->option('restore');

        if ($isRestore) {
            $this->info('Removing vendor assets symlinks...');
        } else {
            $this->info('Creating symlinks for vendor assets...');
        }

        $publicPath = public_path();
        $basePath = base_path();

        $allSystemLinks = [
            //livewire system assets
            'vendor/livewire' => 'vendor/livewire/livewire/dist',


            // filament system assets
            'js/filament/filament/echo.js' => 'vendor/filament/filament/dist/echo.js',
            'js/filament/filament/app.js' => 'vendor/filament/filament/dist/index.js',
            'js/filament/filament/forms/components' => 'vendor/filament/forms/dist/components',
            'js/filament/forms' => 'vendor/filament/forms/dist',
            'js/filament/notifications/notifications.js' => 'vendor/filament/notifications/dist/index.js',
            'js/filament/support/support.js' => 'vendor/filament/support/dist/index.js',
            'js/filament/support/async-alpine.js' => 'vendor/filament/support/dist/async-alpine.js',
            'js/filament/tables/components' => 'vendor/filament/tables/dist/components',
            'js/filament/widgets' => 'vendor/filament/widgets/dist',


            //filament-language-switch
            'css/bezhansalleh/filament-language-switch/filament-language-switch.css' => 'vendor/bezhansalleh/filament-language-switch/resources/dist/filament-language-switch.css',


            // Flatpickr assets
            'js/coolsam/flatpickr/flatpickr-range-plugin.js' => 'vendor/bobimicroweber/filament-flatpickr/resources/assets/flatpickr/dist/plugins/rangePlugin.js',
            'js/coolsam/flatpickr/flatpickr-confirm-date.js' => 'vendor/bobimicroweber/filament-flatpickr/resources/assets/flatpickr/dist/plugins/confirmDate/confirmDate.js',
            'js/coolsam/flatpickr/flatpickr-month-select-plugin.js' => 'vendor/bobimicroweber/filament-flatpickr/resources/assets/flatpickr/dist/plugins/monthSelect/index.js',
            'js/coolsam/flatpickr/flatpickr-week-select-plugin.js' => 'vendor/bobimicroweber/filament-flatpickr/resources/assets/flatpickr/dist/plugins/weekSelect/weekSelect.js',
            'css/coolsam/flatpickr/flatpickr-css.css' => 'vendor/bobimicroweber/filament-flatpickr/resources/assets/flatpickr/dist/flatpickr.css',
            'css/coolsam/flatpickr/month-select-style.css' => 'vendor/bobimicroweber/filament-flatpickr/resources/assets/flatpickr/dist/plugins/monthSelect/style.css',
            'css/coolsam/flatpickr/flatpickr-confirm-date-style.css' => 'vendor/bobimicroweber/filament-flatpickr/resources/assets/flatpickr/dist/plugins/confirmDate/confirmDate.css',
            'css/coolsam/flatpickr/flatpickr-airbnb-theme.css' => 'vendor/bobimicroweber/filament-flatpickr/resources/assets/flatpickr/dist/themes/airbnb.css',
            'css/coolsam/flatpickr/flatpickr-confetti-theme.css' => 'vendor/bobimicroweber/filament-flatpickr/resources/assets/flatpickr/dist/themes/confetti.css',
            'css/coolsam/flatpickr/flatpickr-dark-theme.css' => 'vendor/bobimicroweber/filament-flatpickr/resources/assets/flatpickr/dist/themes/dark.css',
            'css/coolsam/flatpickr/flatpickr-light-theme.css' => 'vendor/bobimicroweber/filament-flatpickr/resources/assets/flatpickr/dist/themes/light.css',
            'css/coolsam/flatpickr/flatpickr-default-theme.css' => 'vendor/bobimicroweber/filament-flatpickr/resources/assets/flatpickr/dist/themes/light.css',
            'css/coolsam/flatpickr/flatpickr-material_blue-theme.css' => 'vendor/bobimicroweber/filament-flatpickr/resources/assets/flatpickr/dist/themes/material_blue.css',
            'css/coolsam/flatpickr/flatpickr-material_green-theme.css' => 'vendor/bobimicroweber/filament-flatpickr/resources/assets/flatpickr/dist/themes/material_green.css',
            'css/coolsam/flatpickr/flatpickr-material_red-theme.css' => 'vendor/bobimicroweber/filament-flatpickr/resources/assets/flatpickr/dist/themes/material_red.css',
            'css/coolsam/flatpickr/flatpickr-material_orange-theme.css' => 'vendor/bobimicroweber/filament-flatpickr/resources/assets/flatpickr/dist/themes/material_orange.css',
            'js/coolsam/flatpickr/flatpickr-component.js' => 'vendor/bobimicroweber/filament-flatpickr/resources/js/dist/components/flatpickr-component.js',


            // microweber-packages

            'vendor/microweber-packages/microweber-filament-theme/build' => 'vendor/microweber-packages/microweber-filament-theme/resources/dist/build',
            'vendor/microweber-packages/frontend-assets-libs' => 'vendor/microweber-packages/frontend-assets-libs/resources/dist',
            'vendor/microweber-packages/frontend-assets/build' => 'vendor/microweber-packages/frontend-assets/resources/dist/build',

        ];

        // Handle system assets
        foreach ($allSystemLinks as $link => $target) {
            $tagetPath = normalize_path($basePath . DIRECTORY_SEPARATOR . $target, false);
            $linkPath = normalize_path($publicPath . DIRECTORY_SEPARATOR . $link, false);

            if ($isRestore) {
                $this->removeSymlink($linkPath);
            } else {
                $this->createSymlink($linkPath, $tagetPath);
            }
        }

        // Handle module assets
        $allModules = app()->modules->all();
        foreach ($allModules as $module) {
            if ($isRestore) {
                $this->removeModuleSymlink($module);
            } else {
                $this->symlinkModuleAssets($module);
            }
        }

        // Handle template assets
        $allTemplates = app()->templates->all();
        foreach ($allTemplates as $template) {
            if ($isRestore) {
                $this->removeTemplateSymlink($template);
            } else {
                $this->symlinkTemplateAssets($template);
            }
        }

        if ($isRestore) {
            $this->info('Vendor assets symlinks removed successfully!');
            $this->info('You must run `composer publish-assets` to republish the assets after removing the symlinks.');
        } else {
            $this->info('Vendor assets symlinks created successfully!');
        }
    }

    protected function removeSymlink($link)
    {
        if (is_link($link) || file_exists($link)) {
            $this->_unlink($link);
            $this->line("Removed symlink: {$link}");
            return true;
        }
        return false;
    }

    protected function removeModuleSymlink(LaravelModule $module)
    {
        $moduleName = $module->getLowerName();
        $targetDir = public_path('modules/' . $moduleName);

        if (is_link($targetDir) || file_exists($targetDir)) {
            $this->info("Removing symlink for module: {$moduleName}");
            $this->removeSymlink($targetDir);
        }
    }

    protected function removeTemplateSymlink(LaravelTemplate $template)
    {
        $templateName = $template->getLowerName();
        $targetDir = public_path('templates/' . $templateName);

        if (is_link($targetDir) || file_exists($targetDir)) {
            $this->info("Removing symlink for template: {$templateName}");
            $this->removeSymlink($targetDir);
        }
    }

    protected function _symlink($target, $link)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $target = str_replace('/', '\\', $target);
            $link = str_replace('/', '\\', $link);
            if (is_dir($target)) {
                exec('cmd /c mklink /D "' . $link . '" "' . $target . '"');
            } else {
                exec('cmd /c mklink "' . $link . '" "' . $target . '"');
            }
        } else {
            symlink($target, $link);
        }
    }

    protected function _unlink($link)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $link = str_replace('/', '\\', $link);
            if (is_dir($link)) {
                exec('cmd /c rmdir /S /Q "' . $link . '"');
            } else {
                exec('cmd /c del /F /Q "' . $link . '"');
            }
        } else {
            if (is_dir($link)) {
                $this->recursiveRemoveDirectory($link);
            } else {
                unlink($link);
            }
        }
    }

    protected function recursiveRemoveDirectory($directory)
    {
        if (!is_dir($directory)) {
            return;
        }

        $items = scandir($directory);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $directory . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                $this->recursiveRemoveDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($directory);
    }

    /**
     * Create a symlink from link to target
     */
    protected function createSymlink($link, $target)
    {
        // Ensure target exists
        if (!file_exists($target)) {

            $this->warn("Target does not exist: {$target}");
            return false;
        }

        if (is_link($link)) {
            $this->info("Link already exists: {$link}");
            return true;
        }


        // Create directory for link if it doesn't exist
        $linkDir = dirname($link);
        if (!is_dir($linkDir)) {
            mkdir($linkDir, 0755, true);
        }

        // Remove existing link/file if it exists
        if (file_exists($link) || is_link($link)) {
            $this->_unlink($link);
        }

        // Create the symlink
        $this->_symlink($target, $link);
        $this->line("Created symlink: {$link} -> {$target}");
        return true;
    }

    /**
     * Create symlinks for module assets
     */
    protected function symlinkModuleAssets(LaravelModule $module)
    {


        $moduleName = $module->getLowerName();
        $modulePath = $module->getPath() . '/resources/assets';
        $modulePath = normalize_path($modulePath, true);
        $targetDir = public_path('modules/' . $moduleName);


        if (!is_dir($modulePath)) {
            return;
        }
        if (is_link($modulePath)) {
            $this->info("Module assets symlink already exists: {$modulePath}");
            return;
        }
        $this->info("Creating symlink for module: {$moduleName}");
        // Create the symlink for the module assets
        $this->createSymlink($targetDir, $modulePath);

    }

    /**
     * Create symlinks for template assets
     */
    protected function symlinkTemplateAssets(LaravelTemplate $template)
    {
        $templatePath = $template->getPath() . '/resources/assets';

        $templatePath = normalize_path($templatePath, true);
        $templateName = $template->getLowerName();
        $targetDir = public_path('templates/' . $templateName);

        if (!is_dir($templatePath)) {
            return;
        }

        if (is_link($templatePath)) {
            $this->info("Template assets symlink already exists: {$templatePath}");
            return;
        }

        $this->info("Creating symlink for template: {$templateName}");
        // Create the symlink for the template assets
        $this->createSymlink($targetDir, $templatePath);
    }

}
