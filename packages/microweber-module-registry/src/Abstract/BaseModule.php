<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Abstract;

use MicroweberPackages\ModuleRegistry\Contracts\ModuleContract;
use MicroweberPackages\ModuleRegistry\Livewire\NoSettings;
use MicroweberPackages\ModuleRegistry\Support\CmsHelpers;
use MicroweberPackages\ModuleRegistry\Traits\HasMicroweberModule;
use MicroweberPackages\ModuleRegistry\Traits\HasMicroweberModuleOptions;
use MicroweberPackages\ModuleRegistry\Traits\HasMicroweberModuleParams;
use MicroweberPackages\ModuleRegistry\Traits\HasMicroweberModuleTemplates;

abstract class BaseModule implements ModuleContract
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

    /** Blade view namespace, e.g. modules.my_module::templates */
    public static string $templatesNamespace = '';

    /** @var array<string, mixed> */
    public array $params = [];

    /** @var list<string> */
    public static array $translatableOptions = [];

    protected static bool $isStaticElement = false;

    /**
     * @param  array<string, mixed>  $params
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    /**
     * @return array{id: mixed, params: array<string, mixed>, template: string, options: array<string, mixed>}
     */
    public function getViewData(): array
    {
        $options = $this->getOptions();
        $params = $this->getParams();
        $template = $this->getTemplate();

        return [
            'id' => $params['id'] ?? null,
            'params' => $params,
            'template' => $template,
            'options' => $options,
        ];
    }

    /**
     * @return \Illuminate\Contracts\View\View|string|mixed
     */
    public function render()
    {
        if (static::$templatesNamespace === '') {
            return '';
        }

        $viewData = $this->getViewData();
        $template = $viewData['template'] !== '' ? $viewData['template'] : 'default';

        // Dynamic module view names are not statically known view-strings
        return view(static::$templatesNamespace . '.' . $template, $viewData); // @phpstan-ignore argument.type
    }

    /**
     * Resolve the best blade view name for a skin/template, checking the active
     * site template and other installed templates when CMS services exist.
     *
     * @throws \Exception
     */
    public function getViewName(?string $template): string
    {
        if (static::$templatesNamespace === '') {
            throw new \Exception('No templates namespace provided');
        }

        $moduleTemplatesNamespace = static::$templatesNamespace;
        $viewName = $moduleTemplatesNamespace . '.default';
        $templateNormalized = 'default';

        if ($template !== null && $template !== '') {
            $templateNormalized = str_replace('.php', '.blade.php', $template);
            $templateNormalized = str_replace('.blade.blade.php', '.blade.php', $templateNormalized);
            $templateNormalized = str_replace('.blade.php', '', $templateNormalized);
            $templateNormalized = str_replace(['/', '\\'], '.', $templateNormalized);
            $viewSettings = static::$templatesNamespace . '.' . $templateNormalized;
            if (view()->exists($viewSettings)) {
                $viewName = $viewSettings;
            }
        }

        $activeTemplate = CmsHelpers::templateName();
        if ($activeTemplate === 'default') {
            $fromOption = CmsHelpers::optionCurrentTemplate();
            if ($fromOption !== null) {
                $activeTemplate = $fromOption;
            }
        }

        $templateParent = CmsHelpers::templateParent($activeTemplate);
        if ($templateParent !== '' && $templateParent !== $activeTemplate) {
            $activeTemplate = $templateParent;
        }

        $checkIfActiveSiteTemplateLowerName = null;

        if ($activeTemplate !== '') {
            $activeTemplateKey = str_replace([' ', '-', '.', '/', '\\'], '_', $activeTemplate);
            $checkIfActiveSiteTemplate = CmsHelpers::findTemplate($activeTemplateKey);
            if ($checkIfActiveSiteTemplate !== null) {
                $checkIfActiveSiteTemplateLowerName = CmsHelpers::templateLowerName($checkIfActiveSiteTemplate);
                $templatesNamespaceInActiveSiteTemplate = str_replace('::', '.', $moduleTemplatesNamespace);
                $templatesNamespaceInActiveSiteTemplate = 'templates.' . $checkIfActiveSiteTemplateLowerName
                    . '::' . $templatesNamespaceInActiveSiteTemplate . '.' . $templateNormalized;
                if (view()->exists($templatesNamespaceInActiveSiteTemplate)) {
                    $viewName = $templatesNamespaceInActiveSiteTemplate;
                }
            }
        }

        // When the view was not found in the active template, check other installed templates
        $defaultViewName = $moduleTemplatesNamespace . '.default';
        if ($viewName === $defaultViewName && $templateNormalized !== '' && $templateNormalized !== 'default') {
            foreach (CmsHelpers::allTemplates() as $otherTemplate) {
                $otherLowerName = CmsHelpers::templateLowerName($otherTemplate);
                if ($checkIfActiveSiteTemplateLowerName !== null && $otherLowerName === $checkIfActiveSiteTemplateLowerName) {
                    continue;
                }
                $otherNs = str_replace('::', '.', $moduleTemplatesNamespace);
                $otherViewName = 'templates.' . $otherLowerName . '::' . $otherNs . '.' . $templateNormalized;
                if (view()->exists($otherViewName)) {
                    $viewName = $otherViewName;
                    break;
                }
            }
        }

        return $viewName;
    }
}
