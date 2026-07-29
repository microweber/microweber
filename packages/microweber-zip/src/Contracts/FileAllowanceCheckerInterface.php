<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip\Contracts;

/**
 * Optional callback interface for filtering which archive entries may be extracted.
 *
 * Host applications (e.g. Microweber CMS) can implement this to reuse their
 * own allowed-extension lists without coupling this package to CMS helpers.
 */
interface FileAllowanceCheckerInterface
{
    /**
     * Return true when the archive entry name is allowed to be extracted.
     */
    public function isAllowed(string $entryName): bool;
}
