<?php

namespace MicroweberPackages\Package\Helpers;


use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;


class BaseInstaller extends LibraryInstaller
{


    protected $folder_base = '';
    protected $supports = '';


    public function getInstallPath(PackageInterface $package)
    {
        $extra = $package->getExtra();
        $target_dir = $package->getTargetDir();


        $folder = false;

        if ($target_dir) {
            $folder = $target_dir;
        }
        if (!$folder and ($extra and isset($extra['folder']) and ($extra['folder']))) {
            $folder = $extra['folder'];
        }

        if (!$folder) {
            throw new \InvalidArgumentException(
                'Unable to determinate the install folder for ' . $package->getPrettyName()
            );
        }

        $folder = sanitize_path($folder);
        return $this->folder_base . $folder;
    }


    public function supports($packageType)
    {
        return $this->supports === $packageType;
    }
}
