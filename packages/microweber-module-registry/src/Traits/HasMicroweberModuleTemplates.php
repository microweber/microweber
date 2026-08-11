<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Traits;

use MicroweberPackages\ModuleRegistry\Support\CmsHelpers;

/**
 * Template / skin selection for a module instance.
 *
 * @phpstan-require-extends \MicroweberPackages\ModuleRegistry\Abstract\BaseModule
 */
trait HasMicroweberModuleTemplates
{
    /** @var array<int|string, mixed> */
    public array $templates = [];

    public string $template = 'default';

    public static function getTemplatesNamespace(): string
    {
        return static::$templatesNamespace;
    }

    public function getTemplate(): string
    {
        $moduleId = '';
        if (isset($this->params['id'])) {
            $id = $this->params['id'];
            if (is_string($id)) {
                $moduleId = $id;
            } elseif (is_int($id) || is_float($id)) {
                $moduleId = (string) $id;
            }
        }

        $moduleTemplate = CmsHelpers::getOption('template', $moduleId !== '' ? $moduleId : null);
        if (! is_string($moduleTemplate) || $moduleTemplate === '') {
            $moduleTemplate = CmsHelpers::getOption('data-template', $moduleId !== '' ? $moduleId : null);
        }
        if ((! is_string($moduleTemplate) || $moduleTemplate === '') && isset($this->params['template'])) {
            $paramTemplate = $this->params['template'];
            $moduleTemplate = is_string($paramTemplate) ? $paramTemplate : null;
        }

        return is_string($moduleTemplate) && $moduleTemplate !== '' ? $moduleTemplate : 'default';
    }
}
