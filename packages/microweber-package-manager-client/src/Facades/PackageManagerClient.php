<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\PackageManagerClient\InstallTarget;
use MicroweberPackages\PackageManagerClient\PackageManagerClientService;

/**
 * PackageManagerClient facade — greppable public API for package manager client.
 *
 * @method static array<string, mixed> search(array<string, string> $filter = [])
 * @method static array<string, mixed> getPackageByName(string $packageName, string|false $packageVersion = false)
 * @method static array<string, mixed> requestInstall(array<string, string> $params)
 * @method static array<string, mixed> requestUpdate(array<string, string> $params)
 * @method static array<string, mixed>|false install(array<string, mixed> $package)
 * @method static InstallTarget detectInstallDir(array<string, mixed> $package)
 * @method static int countNewUpdates()
 * @method static PackageManagerClientService setPackageServers(array<int, string> $servers)
 * @method static PackageManagerClientService setLicenses(array<int, array<string, mixed>> $licenses)
 *
 * @see \MicroweberPackages\PackageManagerClient\PackageManagerClientService
 * @mixin \MicroweberPackages\PackageManagerClient\PackageManagerClientService
 */
class PackageManagerClient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PackageManagerClientService::class;
    }
}
