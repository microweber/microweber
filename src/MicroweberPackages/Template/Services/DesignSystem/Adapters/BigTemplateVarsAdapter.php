<?php

namespace MicroweberPackages\Template\Services\DesignSystem\Adapters;

/**
 * BigTemplateVarsAdapter
 *
 * The Big template uses the canonical --mw-* properties natively, so it inherits
 * the identity style-pack map and legacy palette mapping from
 * DefaultTemplateVarsAdapter — only the template name differs. Registered from
 * the Big template's own service provider.
 */
class BigTemplateVarsAdapter extends DefaultTemplateVarsAdapter
{
    public function templateName(): string
    {
        return 'big';
    }
}
