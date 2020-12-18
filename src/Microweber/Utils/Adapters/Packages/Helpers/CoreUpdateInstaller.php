<?php

namespace Microweber\Utils\Adapters\Packages\Helpers;


use Composer\Package\PackageInterface;


class CoreUpdateInstaller extends BaseInstaller
{


    protected $folder_base = './microweber-core-update/';
    protected $supports = 'microweber-core-update';

    public function getInstallPath(PackageInterface $package)
    {

        return $this->folder_base . 'install-update';
    }


    public function supports($packageType)
    {
        return $this->supports === $packageType;
    }
}