<?php

namespace Microweber\Utils\Adapters\Packages;



use Composer\Factory as Factory;
use Composer\Config;
use Composer\Downloader;
use Composer\Package\Archiver;
use Composer\IO;



class ComposerFactory extends Factory {



    public function createArchiveManager(Config $config, Downloader\DownloadManager $dm = null)
    {
        if (null === $dm) {
            $io = new IO\NullIO();
            $io->loadConfiguration($config);
            $dm = $this->createDownloadManager($io, $config);
        }

        $am = new Archiver\ArchiveManager($dm);
        $am->addArchiver(new Archiver\ZipArchiver);

        return $am;
    }




}