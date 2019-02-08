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
use Composer\Downloader\TransportException;
use Composer\Plugin\CommandEvent;
use Composer\Plugin\PluginEvents;
use Microweber\Utils\Adapters\Packages\PackageManagerException;

use Microweber\Utils\Adapters\Packages\Helpers\ComposerAbstractController;

class ComposerPackagesSearchCommandController extends ComposerAbstractController
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


        $packages = array();
        $packages = $this->searchPackages(
            $tokens,
            $searchName ? RepositoryInterface::SEARCH_NAME : RepositoryInterface::SEARCH_FULLTEXT
        );
//        try {
//
//
//        } catch (\Exception $e) {
//            // return $packages;
//        }


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

        ini_set('memory_limit', '2777M');


        $repositoryManager = $this->getRepositoryManager();

        $platformRepo = new PlatformRepository;
        $localRepository = $repositoryManager->getLocalRepository();
        $installedRepository = new CompositeRepository(
            array($localRepository, $platformRepo)
        );
        $known_repos = $known_repos_orig = $repositoryManager->getRepositories();

        $errors = array();
        $removed_repos = array();
        $has_error = false;
        $results = false;
        $results_found = array();

        do {
            try {
                $repositories = new CompositeRepository(
                    array_merge(
                        array($installedRepository),
                        $known_repos
                    )
                );
                $results = $this->_trySearch($repositories, $tokens, $searchIn);
                $has_error = false;
                if ($results) {
                    $results_found = $results;
                }
            } catch (\Composer\Downloader\TransportException $e) {
                $err_msg = $e->getMessage();
                $err_code = $e->getCode();
                $has_error = true;
                foreach ($known_repos as $rk => $known_repo) {
                    $u = $known_repo->getRepoConfig();
                    $u = $u['url'];

                    if (stristr($err_msg, $u)) {
                        unset($known_repos[$rk]);
                        //  dd($u,$known_repos);
                        $removed_repos[] = $known_repo;
                    }

                }
                $errors[$err_code] = $err_msg;
                //  dd($e->getMessage(),$e->getCode(), $platformRepo, $known_repos, $localRepository);

            }


        } while (!$results_found or !$known_repos);


        if ($removed_repos and $known_repos != $known_repos_orig) {
            if ($this->_setDisableNonActiveReposInComposer) {
                $f = $this->getConfigPathname();
                $f = normalize_path($f, false);
                $composer_temp = @file_get_contents($f);
                $composer_temp = @json_decode($composer_temp, true);


                foreach ($removed_repos as $removed_repo) {
                    $u = $removed_repo->getRepoConfig();

                    if (isset($u['url']) and isset($composer_temp['repositories']) and is_array($composer_temp['repositories']) and !empty($composer_temp['repositories'])) {
                        foreach ($composer_temp['repositories'] as $kk1 => $composer_repo_item) {

                            if (isset($composer_repo_item['url'])) {
                                if (stristr($composer_repo_item['url'], $u['url'])) {
                                    unset($composer_temp['repositories'][$kk1]);
                                }
                            }
                        }
                    }

                }

                file_put_contents($f, json_encode($composer_temp));

            }
        }


        $results = $results_found;


        if ($has_error) {
            //\Log::info('Package manager error: ' . implode("\n", $errors));
        }

        if (!$results) {
            throw new PackageManagerException('Package manager error: ' . implode("\n", $errors));
        }


        //$results = $repositories->search(implode(' ', $tokens), $searchIn);

        // dd($known_repos);


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
                        $version_repo_raw = $version->getVersion();
                        $version_repo = $version->getPrettyVersion();


                        $package_is_allowed = false;
                        if ($version_type and in_array($version_type, $allowed_package_types)) {
                            $package_is_allowed = true;

                        }


                        if ($version_type == 'microweber-core-update' and $result['name'] == 'microweber/update') {
                            if (MW_VERSION == $version_repo) {
                                $package_is_allowed = true;
                            }
                        }


                        if ($package_is_allowed) {

                            $version_info = array();
                            $version_info['version'] = $version->getPrettyVersion();
                            $version_info['version_raw'] = $version->getVersion();
                            $version_info['release_date'] = $version->getReleaseDate()->format('Y-m-d H:i:s');
                            $version_info['type'] = $version_type;
                            $version_info['requires'] = $version_requires;
                            $version_info['extra'] = $version->getExtra();
                            $version_info['dist'] = $version->getDistUrls();

                            if($version_info['dist']){
                            if (!$latestVersion || $version->getReleaseDate() > $latestVersion->getReleaseDate()) {
                                $latestVersion = $version;
                                $latestVersionInfo = $version_info;
                                // $version_info['version_is_latest'] = 1;
                            }
                            $packages[$result['name']]['versions'][$version_info['version']] = $version_info;
                            $packages[$result['name']]['mw-compatible'] = true;
                            }

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

    private $_setDisableNonActiveReposInComposer = false;

    public function setDisableNonActiveReposInComposer($bool = false)
    {
        $this->_setDisableNonActiveReposInComposer = $bool;
    }

    private function _trySearch($repositories, $tokens, $searchIn)
    {


        return $repositories->search(implode(' ', $tokens), $searchIn, 'microweber');

    }


}
