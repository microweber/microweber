<?php

namespace MicroweberPackages\Package;

use Composer\Factory as Factory;
use Composer\Config as Config;
use Composer\Downloader;
use Composer\Package\Archiver;
use Composer\IO;
use Composer\Cache as Cache;
use Composer\Package\Locker;
use Composer\Util\Filesystem;
use Composer\Util\ProcessExecutor;
use Composer\EventDispatcher\EventDispatcher;
use Composer\IO\IOInterface;
use Composer\Downloader\DownloadManager;
use Composer\Composer;
use Composer\Util\RemoteFilesystem;
use Composer\Package\Loader\RootPackageLoader;

use Composer\Config\JsonConfigSource;
use Composer\Json\JsonFile;

use Composer\Package\Version\VersionGuesser;
use Composer\Repository\RepositoryManager;
use Composer\Repository\RepositoryFactory;
use Composer\Repository\WritableRepositoryInterface;
use Composer\Util\Platform;

use Composer\Util\Silencer;
use Composer\Plugin\PluginEvents;
use Composer\EventDispatcher\Event;
use MicroweberPackages\Package\Helpers\ComposerRemoteFilesystem;
use Seld\JsonLint\DuplicateKeyException;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput;
use Composer\Autoload\AutoloadGenerator;
use Composer\Package\Version\VersionParser;
use Composer\Downloader\TransportException;
use Seld\JsonLint\JsonParser;







use MicroweberPackages\Package\Installer\InstallationManager;

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



    /**
     * Creates a Composer instance
     *
     * @param  IOInterface               $io             IO instance
     * @param  array|string|null         $localConfig    either a configuration array or a filename to read from, if null it will
     *                                                   read from the default filename
     * @param  bool                      $disablePlugins Whether plugins should not be loaded
     * @param  bool                      $fullLoad       Whether to initialize everything or only main project stuff (used when loading the global composer)
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @return Composer
     */
    public function createComposer(IOInterface $io, $localConfig = null, $disablePlugins = false, $cwd = null, $fullLoad = true)
    {
        $cwd = $cwd ?: getcwd();

        // load Composer configuration
        if (null === $localConfig) {
            $localConfig = static::getComposerFile();
        }

        if (is_string($localConfig)) {
            $composerFile = $localConfig;

            $file = new JsonFile($localConfig, null, $io);

            if (!$file->exists()) {
                if ($localConfig === './composer.json' || $localConfig === 'composer.json') {
                    $message = 'Composer could not find a composer.json file in '.$cwd;
                } else {
                    $message = 'Composer could not find the config file: '.$localConfig;
                }
                $instructions = 'To initialize a project, please create a composer.json file as described in the https://getcomposer.org/ "Getting Started" section';
                throw new \InvalidArgumentException($message.PHP_EOL.$instructions);
            }

            $file->validateSchema(JsonFile::LAX_SCHEMA);
            $jsonParser = new JsonParser;
            try {
                $jsonParser->parse(file_get_contents($localConfig), JsonParser::DETECT_KEY_CONFLICTS);
            } catch (DuplicateKeyException $e) {
                $details = $e->getDetails();
                $io->writeError('<warning>Key '.$details['key'].' is a duplicate in '.$localConfig.' at line '.$details['line'].'</warning>');
            }

            $localConfig = $file->read();
        }

        // Load config and override with local config/auth config
        $config = static::createConfig($io, $cwd);
        $config->merge($localConfig);

        if (!isset($composerFile)) {
            $composerFile = static::getComposerFile();
        }

        if (isset($composerFile)) {
            $io->writeError('Loading config file ' . $composerFile, true, IOInterface::DEBUG);
            $config->setConfigSource(new JsonConfigSource(new JsonFile(realpath($composerFile), null, $io)));

            $localAuthFile = new JsonFile(dirname(realpath($composerFile)) . '/auth.json', null, $io);
            if ($localAuthFile->exists()) {
                $io->writeError('Loading config file ' . $localAuthFile->getPath(), true, IOInterface::DEBUG);
                $config->merge(array('config' => $localAuthFile->read()));
                $config->setAuthConfigSource(new JsonConfigSource($localAuthFile, true));
            }
        }

        $vendorDir = $config->get('vendor-dir');

        // initialize composer
        $composer = new Composer();
        $composer->setConfig($config);

        if ($fullLoad) {
            // load auth configs into the IO instance
            $io->loadConfiguration($config);
        }

        $rfs = self::createRemoteFilesystem($io, $config);

        // initialize event dispatcher
        $dispatcher = new EventDispatcher($composer, $io);
        $composer->setEventDispatcher($dispatcher);

        // initialize repository manager
        $rm = RepositoryFactory::manager($io, $config, $dispatcher, $rfs);
        $composer->setRepositoryManager($rm);

        // load local repository
        $this->addLocalRepository($io, $rm, $vendorDir);

        // force-set the version of the global package if not defined as
        // guessing it adds no value and only takes time
        if (!$fullLoad && !isset($localConfig['version'])) {
            $localConfig['version'] = '1.0.0';
        }

        // load package
        $parser = new VersionParser;
        $guesser = new VersionGuesser($config, new ProcessExecutor($io), $parser);
        $loader = new RootPackageLoader($rm, $config, $parser, $guesser, $io);
        $package = $loader->load($localConfig, 'Composer\Package\RootPackage', $cwd);
        $composer->setPackage($package);

        // initialize installation manager
        $im = $this->createInstallationManager();
        $composer->setInstallationManager($im);

        if ($fullLoad) {
            // initialize download manager
            $dm = $this->createDownloadManager($io, $config, $dispatcher, $rfs);
            $composer->setDownloadManager($dm);

            // initialize autoload generator
            $generator = new AutoloadGenerator($dispatcher, $io);
            $composer->setAutoloadGenerator($generator);

            // initialize archive manager
            $am = $this->createArchiveManager($config, $dm);
            $composer->setArchiveManager($am);
        }

        // add installers to the manager (must happen after download manager is created since they read it out of $composer)
        $this->createDefaultInstallers($im, $composer, $io);

        if ($fullLoad) {
            $globalComposer = null;
            if (realpath($config->get('home')) !== $cwd) {
                $globalComposer = $this->createGlobalComposer($io, $config, $disablePlugins);
            }

            $pm = $this->createPluginManager($io, $composer, $globalComposer, $disablePlugins);
            $composer->setPluginManager($pm);

            $pm->loadInstalledPlugins();
        }

        // init locker if possible
        if ($fullLoad && isset($composerFile)) {
            $lockFile = "json" === pathinfo($composerFile, PATHINFO_EXTENSION)
                ? substr($composerFile, 0, -4).'lock'
                : $composerFile . '.lock';

            $locker = new Locker($io, new JsonFile($lockFile, null, $io), $rm, $im, file_get_contents($composerFile));
            $composer->setLocker($locker);
        }

        if ($fullLoad) {
            $initEvent = new Event(PluginEvents::INIT);
            $composer->getEventDispatcher()->dispatch($initEvent->getName(), $initEvent);

            // once everything is initialized we can
            // purge packages from local repos if they have been deleted on the filesystem
            if ($rm->getLocalRepository()) {
                $this->purgePackages($rm->getLocalRepository(), $im);
            }
        }

        return $composer;
    }



    /**
     * @param  IOInterface      $io      IO instance
     * @param  Config           $config  Config instance
     * @param  array            $options Array of options passed directly to RemoteFilesystem constructor
     * @return RemoteFilesystem
     */
    public static function createRemoteFilesystem(IOInterface $io, Config $config = null, $options = array())
    {

        static $warned = false;
        $disableTls = false;
        if ($config && $config->get('disable-tls') === true) {
            if (!$warned) {
                $io->writeError('<warning>You are running Composer with SSL/TLS protection disabled.</warning>');
            }
            $warned = true;
            $disableTls = true;
        } elseif (!extension_loaded('openssl')) {
            throw new \Exception('The openssl extension is required for SSL/TLS protection but is not available. '
                . 'If you can not enable the openssl extension, you can disable this error, at your own risk, by setting the \'disable-tls\' option to true.');
        }
        $remoteFilesystemOptions = array();
        if ($disableTls === false) {
            if ($config && $config->get('cafile')) {
                $remoteFilesystemOptions['ssl']['cafile'] = $config->get('cafile');
            }
            if ($config && $config->get('capath')) {
                $remoteFilesystemOptions['ssl']['capath'] = $config->get('capath');
            }
            $remoteFilesystemOptions = array_replace_recursive($remoteFilesystemOptions, $options);
        }
        try {
            $remoteFilesystem = new ComposerRemoteFilesystem($io, $config, $remoteFilesystemOptions, $disableTls);
        } catch (TransportException $e) {
            if (false !== strpos($e->getMessage(), 'cafile')) {
                $io->write('<error>Unable to locate a valid CA certificate file. You must set a valid \'cafile\' option.</error>');
                $io->write('<error>A valid CA certificate file is required for SSL/TLS protection.</error>');
                if (PHP_VERSION_ID < 50600) {
                    $io->write('<error>It is recommended you upgrade to PHP 5.6+ which can detect your system CA file automatically.</error>');
                }
                $io->write('<error>You can disable this error, at your own risk, by setting the \'disable-tls\' option to true.</error>');
            }
            throw $e;
        }

        return $remoteFilesystem;
    }




}
