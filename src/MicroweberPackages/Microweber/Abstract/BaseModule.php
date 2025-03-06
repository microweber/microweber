<?php

namespace MicroweberPackages\Microweber\Abstract;

use MicroweberPackages\Microweber\Liveweire\NoSettings;
use MicroweberPackages\Microweber\Traits\HasMicroweberModule;
use MicroweberPackages\Microweber\Traits\HasMicroweberModuleOptions;
use MicroweberPackages\Microweber\Traits\HasMicroweberModuleParams;
use MicroweberPackages\Microweber\Traits\HasMicroweberModuleTemplates;

abstract class BaseModule
{
    use HasMicroweberModule;
    use HasMicroweberModuleParams;
    use HasMicroweberModuleOptions;
    use HasMicroweberModuleTemplates;

    public static string $name = 'Base module';
    public static string $module = '';
    public static string $icon = '';
    public static string $categories = 'other';
    public static string $settingsComponent = NoSettings::class;
    public static int $position = 0;

    protected static bool $shouldRegisterNavigation = true;

    public static string $templatesNamespace = ''; //modules.my_module


    public array $params = []; // set in the constructor

    public static array $translatableOptions = [];


    protected static bool $isStaticElement = false;

    public function __construct($params = [])
    {
        $this->params = $params;


    }

    public function getViewData(): array
    {
        $options = $this->getOptions();
        $params = $this->getParams();
        $template = $this->getTemplate();
        $viewData = [
            'id' => $params['id'],
            'params' => $params,
            'template' => $template,
            'options' => $options,
        ];
        return $viewData;
    }


    public function render()
    {
        if (!isset(static::$templatesNamespace) || empty(static::$templatesNamespace)) {
            return '';
        }
        $viewData = $this->getViewData();
        $template = $viewData['template'] ?? 'default';
        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }


    public function getViewName($template)
    {
        if (!static::$templatesNamespace) {
            return throw new \Exception('No templates namespace provided');
        }
        $moduleTemplatesNamespace = static::$templatesNamespace;
        $viewName = $moduleTemplatesNamespace . '.' . 'default';
        if ($template) {
            $template = str_replace('.blade.php', '', $template);
            $template = str_replace('.php', '', $template);
            $template = str_replace('/', '.', $template);
            $template = str_replace('\\', '.', $template);
            $viewSettings = static::$templatesNamespace . '.' . $template;
            if (view()->exists($viewSettings)) {
                $viewName = $viewSettings;
            }
        }

        $activeTemplate = template_name();

        $templateParent = template_parent($activeTemplate);
        if ($templateParent and $templateParent != $activeTemplate) {
            $activeTemplate = $templateParent;
        }

        if ($activeTemplate) {
            $activeTemplate = str_replace(' ', '_', $activeTemplate);
            $activeTemplate = str_replace('-', '_', $activeTemplate);
            $activeTemplate = str_replace('.', '_', $activeTemplate);
            $activeTemplate = str_replace('/', '_', $activeTemplate);
            $activeTemplate = str_replace('\\', '_', $activeTemplate);

            $checkIfActiveSiteTemplate = app()->templates->find($activeTemplate);
            if ($checkIfActiveSiteTemplate) {
                $checkIfActiveSiteTemplateLowerName = $checkIfActiveSiteTemplate->getLowerName();
                $templatesNamespaceInActiveSiteTemplate = str_replace('::', '.', $moduleTemplatesNamespace);
                $templatesNamespaceInActiveSiteTemplate = 'templates.' . $checkIfActiveSiteTemplateLowerName . '::' . $templatesNamespaceInActiveSiteTemplate . '.' . $template;

                if (view()->exists($templatesNamespaceInActiveSiteTemplate)) {
                    $viewName = $templatesNamespaceInActiveSiteTemplate;
                }
            }

        }


        return $viewName;
    }


}
