<?php

namespace MicroweberPackages\Package\Helpers;

use Composer\Package\PackageInterface;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Util\Filesystem;
use Composer\Util\Silencer;
use Composer\Util\Platform;
use React\Promise\PromiseInterface;
use Composer\Installer\BinaryInstaller;


class PackageDependenciesInstaller extends BaseInstaller
{


//    public function __construct(IOInterface $io, Composer $composer, $type = 'library', Filesystem $filesystem = null, BinaryInstaller $binaryInstaller = null)
//    {
//        var_dump($type);
//        exit;
//    }


    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $this->initializeVendorDir();
        $downloadPath = $this->getInstallPath($package);

        // remove the binaries if it appears the package files are missing
        if (!is_readable($downloadPath) && $repo->hasPackage($package)) {
            $this->binaryInstaller->removeBinaries($package);
        }
//dd($package);
//     var_dump($package->getType());

        $this->installCode($package);
        $this->binaryInstaller->installBinaries($package, $this->getInstallPath($package));
        if (!$repo->hasPackage($package)) {
            $repo->addPackage(clone $package);
        }
    }

}