<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/16/2021
 * Time: 11:58 AM
 */

namespace MicroweberPackages\Package\Helpers;


use Composer\Autoload\AutoloadGenerator;
use Composer\Package\PackageInterface;

class ComposerAutoloadGenerator extends AutoloadGenerator
{



    /**
     * @param PackageInterface $package
     *
     * @throws \InvalidArgumentException Throws an exception, if the package has illegal settings.
     */
    protected function validatePackage(PackageInterface $package)
    {
//        $autoload = $package->getAutoload();
//        if (!empty($autoload['psr-4']) && null !== $package->getTargetDir()) {
//            $name = $package->getName();
//            $package->getTargetDir();
//            throw new \InvalidArgumentException("PSR-4 autoloading is incompatible with the target-dir property, remove the target-dir in package '$name'.");
//        }
//        if (!empty($autoload['psr-4'])) {
//            foreach ($autoload['psr-4'] as $namespace => $dirs) {
//                if ($namespace !== '' && '\\' !== substr($namespace, -1)) {
//                    throw new \InvalidArgumentException("psr-4 namespaces must end with a namespace separator, '$namespace' does not, use '$namespace\\'.");
//                }
//            }
//        }
    }



}