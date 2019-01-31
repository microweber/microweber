<?php

namespace Microweber\Utils\Adapters\Packages\Helpers;


use Composer\Composer;
use Composer\DependencyResolver\Pool;
use Composer\Installer;
use Composer\IO\BufferIO;
use Composer\Package\PackageInterface;
use Composer\Repository\CompositeRepository;
use Composer\Repository\PlatformRepository;
use Composer\Repository\RepositoryInterface;
use Composer\Repository\RepositoryManager;
use Composer\Semver\Constraint\Constraint;


abstract class ComposerAbstractController implements ComposerControllerInterface
{

    /**
     * The pathname to the composer config file.
     *
     * @var string
     */
    protected $configPathname = null;

    /**
     * The io system.
     *
     * @var BufferIO
     */
    protected $io = null;

    /**
     * @var Composer
     */
    protected $composer;

    public function __construct()
    {
       // parent::__construct();
    }

    /**
     * @param string $configPathname
     */
    public function setConfigPathname($configPathname)
    {
        $this->configPathname = (string) $configPathname;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfigPathname()
    {
        return $this->configPathname;
    }

    /**
     * @param BufferIO $io
     */
    public function setIo(BufferIO $io)
    {
        $this->io = $io;
        return $this;
    }

    /**
     * @return BufferIO
     */
    public function getIo()
    {
        return $this->io;
    }

    /**
     * @param Composer $composer
     */
    public function setComposer(Composer $composer)
    {
        $this->composer = $composer;
        return $this;
    }

    /**
     * @return Composer
     */
    public function getComposer()
    {
        return $this->composer;
    }

    /**
     * @return RepositoryManager
     */
    protected function getRepositoryManager()
    {
        return $this->composer->getRepositoryManager();
    }

    /**
     * Build dependency graph of installed packages.
     *
     * @param RepositoryInterface $repository
     *
     * @return array
     */
    protected function calculateDependencyMap(RepositoryInterface $repository, $inverted = false)
    {
        $dependencyMap = array();

        /** @var \Composer\Package\PackageInterface $package */
        foreach ($repository->getPackages() as $package) {
            $this->fillDependencyMap($package, $dependencyMap, $inverted);
        }

        return $dependencyMap;
    }

    /**
     * Fill the dependency graph with installed packages.
     *
     * @param RepositoryInterface $repository
     * @param PackageInterface    $package
     * @param array               $dependencyMap
     */
    protected function fillDependencyMap(
        PackageInterface $package,
        array &$dependencyMap,
        $inverted
    ) {
        /** @var \Composer\Package\Link $requireLink */
        foreach ($package->getRequires() as $requireLink) {
            if ($inverted) {
                $dependencyMap[$package->getName()][$requireLink->getTarget()] = $requireLink->getPrettyConstraint();
            } else {
                $dependencyMap[$requireLink->getTarget()][$package->getName()] = $requireLink->getPrettyConstraint();
            }
        }
    }

    protected function getPool($minimumStability = 'dev', $stabilityFlags = array())
    {
        $repositoryManager = $this->getRepositoryManager();

        $platformRepo        = new PlatformRepository;
        $localRepository     = $repositoryManager->getLocalRepository();
        $installedRepository = new CompositeRepository(
            array($localRepository, $platformRepo)
        );
        $repositories        = new CompositeRepository(
            array_merge(
                array($installedRepository),
                $repositoryManager->getRepositories()
            )
        );

        $pool = new Pool($minimumStability, $stabilityFlags);
        $pool->addRepository($repositories);

        return $pool;
    }

    /**
     * Create a constraint instance and set operator and version to compare a package with.
     *
     * @param string $operator A comparison operator.
     * @param string $version  A version to compare to.
     * @see    https://github.com/composer/semver/issues/17
     */
    protected function createConstraint($operator, $version)
    {
        if (!class_exists('Composer\Semver\Constraint\Constraint')) {
            return new \Composer\Package\LinkConstraint\VersionConstraint($operator, $version);
        }

        return new Constraint($operator, $version);
    }
}
