<?php

namespace Microweber\Utils\Adapters\Packages\Helpers;


use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;


class BaseInstaller extends LibraryInstaller
{


    protected $folder_base = '';
    protected $supports = '';


    public function getInstallPath(PackageInterface $package)
    {
        $extra = $package->getExtra();;


        if (!$extra or !isset($extra['folder']) or !($extra['folder'])) {
            throw new \InvalidArgumentException(
                'Unable to determinate the install folder for ' . $package->getPrettyName()
            );
        }

        $folder = $extra['folder'];

        $folder = str_replace('..', '', $folder);
        return $this->folder_base . $folder;
    }


    public function supports($packageType)
    {
        return $this->supports === $packageType;
    }
}