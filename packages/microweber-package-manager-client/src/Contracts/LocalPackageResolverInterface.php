<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Contracts;

/**
 * Optional host-app bridge for resolving locally installed modules/templates.
 *
 * Implement in the CMS (or leave null for standalone Laravel apps).
 *
 * @phpstan-type LocalPackage array{
 *     name?: string,
 *     dir_name?: string,
 *     version?: string,
 *     is_symlink?: bool|int|string,
 *     type?: string
 * }
 */
interface LocalPackageResolverInterface
{
    /**
     * @return list<LocalPackage>
     */
    public function modules(): array;

    /**
     * @return list<LocalPackage>
     */
    public function templates(): array;
}
