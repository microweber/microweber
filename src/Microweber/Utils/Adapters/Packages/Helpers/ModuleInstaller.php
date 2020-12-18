<?php

namespace Microweber\Utils\Adapters\Packages\Helpers;


use Composer\Package\PackageInterface;


class ModuleInstaller extends BaseInstaller
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
}