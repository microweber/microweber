<?php

namespace MicroweberPackages\Package;

use Composer\Factory as Factory;
use Composer\Config as Config;
use Composer\Downloader;
use Composer\Package\Archiver;
use Composer\IO;
use Composer\Cache as Cache;
use Composer\Util\Filesystem;
use Composer\Util\HttpDownloader;
use Composer\Util\ProcessExecutor;
use Composer\EventDispatcher\EventDispatcher;
use Composer\IO\IOInterface;
use Composer\Downloader\DownloadManager;
use Composer;
use Composer\Util\RemoteFilesystem;


use MicroweberPackages\Package\Installer\InstallationManager;

use Composer\Util\Loop;
class ComposerFactory extends Factory {

//    /**
//     * @param  Config                     $config The configuration
//     * @param  Downloader\DownloadManager $dm     Manager use to download sources
//     * @return Archiver\ArchiveManager
//     */
//    public function createArchiveManager(\Composer\Config $config, \Composer\Downloader\DownloadManager $dm, \Composer\Util\Loop $loop)
//    {
//        $am = new Archiver\ArchiveManager($dm, $loop);
//        $am->addArchiver(new Archiver\ZipArchiver);
//
//        return $am;
//    }

    /**
     * @param  Config                     $config The configuration
     * @param  Downloader\DownloadManager $dm     Manager use to download sources
     * @return Archiver\ArchiveManager
     */
    public function createArchiveManager(Config $config, Downloader\DownloadManager $dm = null)
    {
        if (null === $dm) {
            $io = new IO\NullIO();
            $io->loadConfiguration($config);
            $dm = $this->createDownloadManager($io, $config);
        }

        $am = new Archiver\ArchiveManager($dm);
        $am->addArchiver(new Archiver\ZipArchiver);
       // $am->addArchiver(new Archiver\PharArchiver);

        return $am;
    }


    /**
     * @param  IO\IOInterface             $io
     * @param  Config                     $config
     * @param  EventDispatcher            $eventDispatcher
     * @return Downloader\DownloadManager
     */
    public function createDownloadManager(IOInterface $io, Config $config, EventDispatcher $eventDispatcher = null, RemoteFilesystem $rfs = null)
    {
        $cache = null;
//        if ($config->get('cache-files-ttl') > 0) {
//            $cache = new Cache($io, $config->get('cache-files-dir'), 'a-z0-9_./');
//            $cache->setReadOnly($config->get('cache-read-only'));
//        }


        $executor = new ProcessExecutor($io);
        $fs = new Filesystem($executor);



        $dm = new Downloader\DownloadManager($io, false, $fs);
        switch ($preferred = $config->get('preferred-install')) {
            case 'dist':
                $dm->setPreferDist(true);
                break;
            case 'source':
                $dm->setPreferSource(true);
                break;
            case 'auto':
            default:
                // noop
                break;
        }

        if (is_array($preferred)) {
            $dm->setPreferences($preferred);
        }

        //$dm->setDownloader('zip', new Downloader\ZipDownloader($io, $config, $httpDownloader, $eventDispatcher, $cache, $fs, $process));

        $dm->setDownloader('zip', new Downloader\ZipDownloader($io, $config, $eventDispatcher, $cache, $executor, $rfs));

        return $dm;
    }

//    protected function createInstallationManager()
//    {
//        return new InstallationManager();
//    }



//    public function createInstallationManager(Loop $loop, IOInterface $io, EventDispatcher $eventDispatcher = null)
//    {
//        return new InstallationManager($loop, $io, $eventDispatcher);
//    }

}