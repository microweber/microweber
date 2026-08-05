<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Contracts;

/**
 * Optional host-app hook run after a successful package install/update.
 *
 * @phpstan-type PackageMeta array<string, mixed>
 */
interface PostInstallHookInterface
{
    /**
     * @param PackageMeta $package
     * @param array{success?: string, log?: string, redirect_to?: string} $response
     * @return array{success?: string, log?: string, redirect_to?: string}
     */
    public function afterInstall(array $package, array $response): array;
}
