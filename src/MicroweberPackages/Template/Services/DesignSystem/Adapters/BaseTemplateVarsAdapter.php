<?php

namespace MicroweberPackages\Template\Services\DesignSystem\Adapters;

/**
 * BaseTemplateVarsAdapter
 *
 * The Base template is built directly on the Microweber CSS framework and uses
 * the canonical --mw-* properties natively, so it inherits everything from
 * DefaultTemplateVarsAdapter — only the template name differs. Registered from
 * the Base template's own service provider (BaseTemplateServiceProvider).
 */
class BaseTemplateVarsAdapter extends DefaultTemplateVarsAdapter
{
    public function templateName(): string
    {
        return 'base';
    }
}
