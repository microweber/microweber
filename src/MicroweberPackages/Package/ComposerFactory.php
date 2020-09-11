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
use MicroweberPackages\Package\Installer\InstallationManager;

use Composer\Util\Loop;
class ComposerFactory extends Factory {

    /**
     * @param  Config                     $config The configuration
     * @param  Downloader\DownloadManager $dm     Manager use to download sources
     * @return Archiver\ArchiveManager
     */
    public function createArchiveManager(\Composer\Config $config, \Composer\Downloader\DownloadManager $dm, \Composer\Util\Loop $loop)
    {
        $am = new Archiver\ArchiveManager($dm, $loop);
        $am->addArchiver(new Archiver\ZipArchiver);

        return $am;
    }

    /**
     * @param  IO\IOInterface             $io
     * @param  Config                     $config
     * @param  EventDispatcher            $eventDispatcher
     * @return Downloader\DownloadManager
     */
    public function createDownloadManager(IO\IOInterface $io, Config $config, HttpDownloader $httpDownloader, ProcessExecutor $process, EventDispatcher $eventDispatcher = null)
    {
        $cache = null;
        if ($config->get('cache-files-ttl') > 0) {
            $cache = new Cache($io, $config->get('cache-files-dir'), 'a-z0-9_./');
            $cache->setReadOnly($config->get('cache-read-only'));
        }

        $fs = new Filesystem($process);

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

        $dm->setDownloader('zip', new Downloader\ZipDownloader($io, $config, $httpDownloader, $eventDispatcher, $cache, $fs, $process));
        return $dm;
    }


    public function createInstallationManager(Loop $loop, IOInterface $io, EventDispatcher $eventDispatcher = null)
    {
        return new InstallationManager($loop, $io, $eventDispatcher);
    }

}