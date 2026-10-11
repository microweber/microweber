<?php

namespace MicroweberPackages\LiveEdit\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\Admin\Http\Controllers\AdminController;
use MicroweberPackages\Install\TemplateInstaller;

class SetupWizardController extends AdminController
{
    public function index(Request $request)
    {
        $filterCategory = $request->get('category', false);
        $getCategories = [];
        $siteTemplates = [];
        $getTemplates = site_templates();
        $remove = ['cms', 'template', 'templates', 'default', 'website', 'default-template'];
        $templateNamesOnTop=[


            'OnlineLearning2',

            'ProteinStore2',
            'Fitpower2',
            'Yoga2',

            'Freelancer2',

            'CarServices',
            'Burger',

            'Art2',
            'Photographer2',
            'Barbershop2',
            'Biolinks',

            'Coffeeshop2',
            'Homerestoration2',

            'Yummy2',
            'Conference2',
            'Events2' ,

            'Resume2',
            'Simple2',
            'HumanResources',
            'Tattoo2',
            'Office2',
            'School',
            'Nomad2',

            'Pricing',
            'NotaryServices2',


            'Big',
            'MobileApp2'

        ];



        //getFallbackTemplateDir
        //$getFallbackTemplateDir = app()->template_manager->templateAdapter->getFallbackTemplateFolderName();

        $getFallbackTemplateDir = 'bootstrap';

        foreach ($getTemplates as $template) {
            if (!isset($template['screenshot'])) {
                continue;
            }

            $templateCategories = [];
            $templateColors = [];
            $templateDescription  = '';
            $templateJson = templates_path() . $template['dir_name'] . '/composer.json';


            if(strtolower($template['dir_name']) == strtolower($getFallbackTemplateDir)) {
                continue;
            }


            if (is_file($templateJson)) {
                $templateJson = @file_get_contents($templateJson);
                $templateJson = @json_decode($templateJson, true);
                if (!empty($templateJson)) {

                    if( isset($templateJson['description']) and is_string($templateJson['description'])) {
                        $templateDescription = $templateJson['description'];
                    } else {
                        $templateDescription = '';
                    }

                    if (isset($templateJson['extra']['colors'])) {
                        $templateColors = $templateJson['extra']['colors'];
                    }
                    if (isset($templateJson['extra']['categories']) and is_array($templateJson['extra']['categories'])) {
                        $templateCategories = array_merge($templateCategories, $templateJson['extra']['categories']);
                    }


                    if (isset($templateJson['keywords']) and is_array($templateJson['keywords'])) {
                        $templateCategories = array_merge($templateCategories, $templateJson['keywords']);
                    }
                }
            }

            // Normalize template categories to title case
            foreach ($templateCategories as $key => $templateCategory) {
                $templateCategories[$key] = ucwords(strtolower($templateCategory));
            }

            foreach ($templateCategories as $templateCategory) {
                $getCategories[strtolower($templateCategory)] = $templateCategory;
            }


            $template['categories'] = $templateCategories;
            $template['colors'] = $templateColors;
            $template['description'] = $templateDescription;
            $siteTemplates[] = $template;
        }

        foreach ($remove as $key => $removeCategory) {
            $remove[$key] = strtolower($removeCategory);
        }        // Filter out unwanted categories
        $uniqueCategories = [];
        foreach ($getCategories as $lowerKey => $titleCasedCategory) {
            if (!in_array($lowerKey, $remove)) {
                $uniqueCategories[$lowerKey] = $titleCasedCategory;
            }
        }

        // Sort templates by putting top ones first
        usort($siteTemplates, function($a, $b) use ($templateNamesOnTop) {
            $aIsTop = in_array($a['name'], $templateNamesOnTop);
            $bIsTop = in_array($b['name'], $templateNamesOnTop);

            if ($aIsTop && !$bIsTop) {
                return -1; // a comes before b
            }
            if (!$aIsTop && $bIsTop) {
                return 1; // b comes before a
            }

            // If both are top templates, maintain the order defined in $templateNamesOnTop
            if ($aIsTop && $bIsTop) {
                $aPos = array_search($a['name'], $templateNamesOnTop);
                $bPos = array_search($b['name'], $templateNamesOnTop);
                return $aPos - $bPos;
            }

            // If neither is a top template, sort alphabetically
            return strcmp($a['name'], $b['name']);
        });

        return view('microweber-live-edit::setup-wizard', [
            'templates' => $siteTemplates,
            'categories' => $uniqueCategories
        ]);
    }

    public function installTemplate(Request $request)
    {
        $template = $request->get('template', false);
        if (!$template) {
            return ['error' => 'Please, select template for installation.'];
        }

        $installer = new TemplateInstaller();
        $installer->setSelectedTemplate($template);
        $installer->setInstallDefaultContent(true);

        $installer->run();

        save_option('current_template', $template,'template');



        return ['success' => 'Template is installed successfully.'];
    }
}
