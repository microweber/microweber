<?php

namespace Templates\Big\DesignSystem;

use MicroweberPackages\Template\Services\DesignSystem\Adapters\DefaultTemplateVarsAdapter;

/**
 * BigTemplateVarsAdapter
 *
 * Lives in the Big template (template-owned, not in the core design-system
 * module). Big uses the canonical --mw-* properties natively, so it inherits
 * the identity style-pack map + legacy palette mapping from the core
 * DefaultTemplateVarsAdapter — only the template name differs. Registered from
 * Big's own service provider.
 */
class BigTemplateVarsAdapter extends DefaultTemplateVarsAdapter
{
    public function templateName(): string
    {
        return 'big';
    }
}
