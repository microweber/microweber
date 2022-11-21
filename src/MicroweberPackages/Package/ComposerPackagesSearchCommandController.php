<?php
namespace MicroweberPackages\Package;

use Composer\Package\CompletePackageInterface;
use Composer\Package\PackageInterface;
use Composer\Repository\PlatformRepository;
use Composer\Repository\RepositoryInterface;

use MicroweberPackages\Package\Helpers\ComposerAbstractController;
use MicroweberPackages\Package\PackageManagerException;

use MicroweberPackages\Package\Helpers\CompositeRepository;

// Deprecate this class

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
        mw()->update->log_msg('Preparing..');

        if (php_can_use_func('ini_set')) {
            ini_set('memory_limit', '2777M');
        }


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

        //do {
        try {

            $repositories = new CompositeRepository(
                array_merge(
                    array($localRepository, $platformRepo),
                    $known_repos
                )
            );

            $results = $this->_trySearch($repositories, $tokens, $searchIn);

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
                    $removed_repos[] = $known_repo;
                }

            }
            $errors[$err_code] = $err_msg;

        }

        //  } while (!$results_found or !$known_repos or !is_array($results));

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


//        if (!$results and $errors) {
//            throw new PackageManagerException('Package manager error: ' . implode("\n", $errors));
//        }
        if (!$results) {
            return;
        }

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


                    $last_v = $versions;
                    $last_v = array_pop($last_v);

                    $packages[$result['name']]['type'] = $last_v->getType();
                    $packages[$result['name']]['description'] = $last_v instanceof CompletePackageInterface
                        ? $last_v->getDescription()
                        : '';


                    $packages[$result['name']]['license'] = $last_v instanceof CompletePackageInterface
                        ? $last_v->getLicense()
                        : '';

                    $packages[$result['name']]['authors'] = $last_v instanceof CompletePackageInterface
                        ? $last_v->getAuthors()
                        : '';

                    $packages[$result['name']]['keywords'] = $last_v instanceof CompletePackageInterface
                        ? $last_v->getKeywords()
                        : '';


                    $packages[$result['name']]['support'] = $last_v instanceof CompletePackageInterface
                        ? $last_v->getSupport()
                        : '';


                    $packages[$result['name']]['homepage'] = $last_v instanceof CompletePackageInterface
                        ? $last_v->getHomepage()
                        : '';


                    $packages[$result['name']]['extra'] = $last_v instanceof CompletePackageInterface
                        ? $last_v->getExtra()
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
                            $version_info['folder'] = $version->getTargetDir();
                            $version_info['dist'] = $version->getDistUrls();
                            $version_info['dist_type'] = $version->getDistType();
                            if ($version_info['dist']) {
                                if ($version_info['version'] and !stristr($version_info['version'], '-dev') and !stristr($version_info['version'], 'dev-')) {
                                    //  if (!$latestVersion || $version->getReleaseDate() > $latestVersion->getReleaseDate()) {
                                    if (!$latestVersion || version_compare($version->getVersion(), $latestVersion->getVersion())) {
                                        $latestVersion = $version;
                                        $latestVersionInfo = $version_info;
                                    }
                                    $packages[$result['name']]['versions'][$version_info['version']] = $version_info;
                                    $packages[$result['name']]['mw-compatible'] = true;
                                }
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

        $packages = $this->_removeNonInstallablePackages($packages);


        return $packages;
    }

    private $_setDisableNonActiveReposInComposer = false;

    public function setDisableNonActiveReposInComposer($bool = false)
    {
        $this->_setDisableNonActiveReposInComposer = $bool;
    }

    private function _trySearch($repositories, $tokens, $searchIn, $type = 'microweber')
    {
        return $repositories->search(implode(' ', $tokens), $searchIn);
    }

    private function _removeNonInstallablePackages($packages)
    {

        return $packages; //TODO

        $modules_dir = normalize_path(modules_path(), true);
        $templates_dir = normalize_path(templates_path(), true);;
        $includes_path = normalize_path(mw_includes_path(), true);;
        $vendor_base_path = normalize_path(base_path() . '/vendor/', true);
        $mw_path = normalize_path(MW_PATH, true);


        if (is_array($packages) and !empty($packages)) {
            foreach ($packages as $pk => $package) {
                if (isset($package['latest_version']) and isset($package['latest_version']['type'])) {
                    $unset = false;
                    $type = $package['latest_version']['type'];

                    if ($type == 'microweber-core-update') {
                        if (is_link($vendor_base_path) or !is_writable($vendor_base_path)) {
                            $unset = true;
                        }
                        if (is_link($mw_path) or !is_writable($mw_path)) {
                            $unset = true;
                        }
                    }

                    if (isset($package['latest_version']['folder']) and $package['latest_version']['folder']) {
                        $folder_name = $package['latest_version']['folder'];
                        $folder = false;
                        if ($type == 'microweber-template') {
                            $folder = normalize_path($templates_dir . $folder_name, true);
                        }
                        if ($type == 'microweber-module') {
                            $folder = normalize_path($modules_dir . $folder_name, true);
                        }

                        if ($folder) {
                            if (!is_dir($folder)) {
                                $unset = false;
                            } else if (is_link($folder) or !is_writable($folder)) {
                                $unset = true;
                            }
                        }
                    }


                    if ($unset) {
                        unset($packages[$pk]);
                    }


                }
            }
        }

        return $packages;
    }
}
