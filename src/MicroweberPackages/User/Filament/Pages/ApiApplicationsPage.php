<?php

namespace MicroweberPackages\User\Filament\Pages;

/**
 * Backward-compatibility alias.
 *
 * The real implementation has moved to the microweber-passport package.
 * This class extends it so that any CMS code or tests referencing the
 * old FQCN continue to work.
 *
 * @deprecated Use \MicroweberPackages\Passport\Filament\Pages\ApiApplicationsPage
 */
class ApiApplicationsPage extends \MicroweberPackages\Passport\Filament\Pages\ApiApplicationsPage
{
}
