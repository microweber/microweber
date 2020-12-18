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



class ModuleInstaller extends PackageDependenciesInstaller
{


    protected $folder_base = 'userfiles/modules/';
    protected $supports = 'microweber-module';




}



/*class ModuleInstaller extends BaseInstaller
{


    protected $folder_base = 'userfiles/modules/';
    protected $supports = 'microweber-module';


    public function getInstallPath(PackageInterface $package)
    {
        return parent::getInstallPath($package);
    }


    public function supports($packageType)
    {
        return $this->supports === $packageType;
    }


}*/