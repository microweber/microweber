<?php

namespace Microweber\Utils\Adapters\Packages;





use Composer\Factory as Factory;
use Composer\Config as Config;
use Composer\Downloader;
use Composer\Package\Archiver;

use Composer\IO;
use Composer\Config\JsonConfigSource;
use Composer\Json\JsonFile;
use Composer\Cache as Cache;
use Composer\IO\IOInterface;
use Composer\Package\Version\VersionGuesser;
use Composer\Repository\RepositoryManager;
use Composer\Repository\RepositoryFactory;
use Composer\Repository\WritableRepositoryInterface;
use Composer\Util\Filesystem;
use Composer\Util\Platform;
use Composer\Util\ProcessExecutor;
use Composer\Util\RemoteFilesystem as RemoteFilesystem;
use Composer\Util\Silencer;
use Composer\Plugin\PluginEvents;
use Composer\EventDispatcher\Event;
use Seld\JsonLint\DuplicateKeyException;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput;
use Composer\EventDispatcher\EventDispatcher;
use Composer\Autoload\AutoloadGenerator;
use Composer\Package\Version\VersionParser;
use Composer\Downloader\TransportException;
use Seld\JsonLint\JsonParser;
use Microweber\Utils\Adapters\Packages\Helpers\DownloadManager as DownloadManager;

use Microweber\Utils\Adapters\Packages\Helpers\ZipDownloader;



class ComposerFactory extends Factory {


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
        if ($config->get('cache-files-ttl') > 0) {
            $cache = new Cache($io, $config->get('cache-files-dir'), 'a-z0-9_./');
        }

        $dm = new DownloadManager($io);
//        switch ($preferred = $config->get('preferred-install')) {
//            case 'dist':
//                $dm->setPreferDist(true);
//                break;
//            case 'source':
//           //     $dm->setPreferSource(true);
//                break;
//            case 'auto':
//            default:
//                // noop
//                break;
//        }

//        if (is_array($preferred)) {
//      //      $dm->setPreferences($preferred);
//        }
        $dm->setPreferDist(true);

        $executor = new ProcessExecutor($io);
        $fs = new Filesystem($executor);
        $dm->setDownloader('zip', new ZipDownloader($io, $config, $eventDispatcher, $cache, $executor, $rfs));
      //  $dm->setDownloader('git', new ZipDownloader($io, $config, $eventDispatcher, $cache, $executor, $rfs));
       // $dm->setDownloader('zip', new Downloader\FileDownloader($io, $config, $eventDispatcher, $cache, $rfs));


      //  $dm->setDownloader('file', new Downloader\FileDownloader($io, $config, $eventDispatcher, $cache, $rfs));
      //  $dm->setDownloader('path', new Downloader\PathDownloader($io, $config, $eventDispatcher, $cache, $rfs));

        return $dm;
    }




}