<?php

namespace Templates\Base\DesignSystem;

use MicroweberPackages\Template\Services\DesignSystem\Adapters\DefaultTemplateVarsAdapter;

/**
 * BaseTemplateVarsAdapter
 *
 * Lives in the Base template (template-owned). Base is built on the Microweber
 * CSS framework and uses the canonical --mw-* properties natively, so it
 * inherits everything from the core DefaultTemplateVarsAdapter — only the
 * template name differs. Registered from Base's service provider.
 */
class BaseTemplateVarsAdapter extends DefaultTemplateVarsAdapter
{
    public function templateName(): string
    {
        return 'base';
    }
}
