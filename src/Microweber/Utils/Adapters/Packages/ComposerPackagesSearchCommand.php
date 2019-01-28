<?php


namespace Microweber\Utils\Adapters\Packages;

use Composer\Console\Application;
use Composer\Command\UpdateCommand;
use Composer\Command\InstallCommand;
use Composer\Command\SearchCommand;
use Composer\Config;
use Symfony\Component\Console\Input\ArrayInput;
use Composer\Factory;
use Composer\IO\ConsoleIO;
use Composer\IO\BufferIO;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\StreamOutput;
use Composer\Installer;
use Composer\Package\CompletePackageInterface;
use Composer\Package\Link;
use Composer\Package\PackageInterface;
use Composer\Repository\CompositeRepository;
use Composer\Repository\PlatformRepository;
use Composer\Repository\RepositoryInterface;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Composer\Plugin\CommandEvent;
use Composer\Plugin\PluginEvents;


use Microweber\Utils\Adapters\Packages\Helpers\ComposerAbstractController;
use Microweber\Utils\Adapters\Packages\Helpers\ComposerControllerInterface;

class ComposerPackagesSearchCommand extends ComposerAbstractController
{


    /**
     * {@inheritdoc}
     */
    public function handle($input)
    {
        $keyword = $input;

        $tokens = explode(' ', $keyword);
        $tokens = array_map('trim', $tokens);
        $tokens = array_filter($tokens);

        $searchName = count($tokens) == 1 && strpos($tokens[0], '/') !== false;
        //$this->io->getOutput()


        $packages = $this->searchPackages(
            $tokens,
            $searchName ? RepositoryInterface::SEARCH_NAME : RepositoryInterface::SEARCH_FULLTEXT
        );


        if (!empty($packages)) {
            return $packages;
        }

    }

    /**
     * Search for packages.
     *
     * @param array $tokens
     * @param int $searchIn
     *
     * @return CompletePackageInterface[]
     */
    protected function searchPackages(array $tokens, $searchIn)
    {

        $allowed_package_types = array(
            'microweber-template',
            'microweber-module',
        );

        $repositoryManager = $this->getRepositoryManager();

        $platformRepo = new PlatformRepository;
        $localRepository = $repositoryManager->getLocalRepository();
        $installedRepository = new CompositeRepository(
            array($localRepository, $platformRepo)
        );
        $repositories = new CompositeRepository(
            array_merge(
                array($installedRepository),
                $repositoryManager->getRepositories()
            )
        );
        ini_set('memory_limit', '2777M');

        $results = $repositories->search(implode(' ', $tokens), $searchIn, 'microweber');
        //$results = $repositories->search(implode(' ', $tokens), $searchIn);


        $mwVersion = 1;
        if (defined('MW_VERSION')) {
            $mwVersion = $mwVersion;

        }

        //  $constraint = $this->createConstraint('=', $mwVersion);

        //$constraint->setPrettyString($mwVersion);

        $packages = array();
        foreach ($results as $result) {
            if (!isset($packages[$result['name']])) {
                /** @var PackageInterface[] $versions */
                $versions = $repositories->findPackages($result['name']);

                /** @var PackageInterface|CompletePackageInterface $latestVersion */
                $latestVersion = false;
                $latestVersionInfo = false;

                $packages[$result['name']] = $result;

                if (count($versions)) {
                    $packages[$result['name']]['type'] = $versions[0]->getType();
                    $packages[$result['name']]['description'] = $versions[0] instanceof CompletePackageInterface
                        ? $versions[0]->getDescription()
                        : '';
                    $packages[$result['name']]['mw-compatible'] = null;
                    $packages[$result['name']]['versions'] = array();

                    foreach ($versions as $version) {
                        $version_requires = $version->getRequires();

                        $version_type = $version->getType();


                        if ($version_type and in_array($version_type, $allowed_package_types)) {

                            $version_info = array();
                            $version_info['version'] = $version->getPrettyVersion();
                            $version_info['version_raw'] = $version->getVersion();
                            $version_info['release_date'] = $version->getReleaseDate()->format('Y-m-d H:i:s');
                            $version_info['type'] = $version_type;
                            $version_info['requires'] = $version_requires;
                            $version_info['extra'] = $version->getExtra();
                            $version_info['dist'] = $version->getDistUrls();

                            if (!$latestVersion || $version->getReleaseDate() > $latestVersion->getReleaseDate()) {
                                $latestVersion = $version;
                                $latestVersionInfo = $version_info;
                                // $version_info['version_is_latest'] = 1;
                            }
                            $packages[$result['name']]['versions'][$version_info['version']] = $version_info;
                            $packages[$result['name']]['mw-compatible'] = true;
                        }
//                        if (isset($requires['microweber/microweber']) && $requires['microweber/microweber'] instanceof Link) {
//                            /** @var Link $link */
//                            $link = $requires['microweber/microweber'];
//
//                            if ($link->getConstraint()->matches($constraint)) {
//                                $packages[$result['name']]['mw-compatible'] = true;
//
//                                if (!$latestVersion || $version->getReleaseDate() > $latestVersion->getReleaseDate()) {
//                                    $latestVersion = $version;
//                                }
//                            }
//                        }
                    }
                }


                if ($latestVersion) {
                    $packages[$result['name']]['type'] = $latestVersion->getType();
                    $packages[$result['name']]['latest_version'] = $latestVersionInfo;

                    if ($latestVersion instanceof CompletePackageInterface) {
                        $packages[$result['name']]['description'] = $latestVersion->getDescription();
                    }
                }

                if (!$packages[$result['name']]['mw-compatible']) {
                    unset($packages[$result['name']]);
                }

            }
        }


        return $packages;
    }


}
