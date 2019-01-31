<?php


namespace Microweber\Utils\Adapters\Packages;


use Composer\Installer;

class InstallPackage extends ComposerAbstractController
{

    public function execute()
    {
        /** @var $composer \Composer\Composer */
        $composer = $this->getComposer();



        $io = $this->getIO();
        $config = $composer->getConfig();
        $optimize = $this->getOptions()->optimizeAutoloader() || $config->get('optimize-autoloader');
        $authoritative = $this->getOptions()->classmapAuthoritative() || $config->get('classmap-authoritative');
        $install = Installer::create($io, $composer)
            ->setDryRun($this->getOptions()->dryRun())
            ->setVerbose($this->getOptions()->verbose())
            ->setPreferSource($this->getOptions()->preferSource())
            ->setPreferDist($this->getOptions()->preferDist())
            ->setDevMode(!$this->getOptions()->noDev())
            ->setDumpAutoloader(!$this->getOptions()->noAutoloader())
            ->setRunScripts(!$this->getOptions()->noScripts())
            ->setOptimizeAutoloader($optimize)
            ->setClassMapAuthoritative($authoritative)
            ->setIgnorePlatformRequirements($this->getOptions()->ignorePlatformReqs());
        try {
            return $install->run();
        } catch (\Exception $e) {
            $msg = sprintf('%s received an error from Composer: %s in %s::%s', __METHOD__, $e->getMessage(), $e->getFile(), $e->getLine());
            $this->app['logger.system']->critical($msg, ['event' => 'exception', 'exception' => $e]);
            throw new PackageManagerException($e->getMessage(), $e->getCode(), $e);
        }
    }
}