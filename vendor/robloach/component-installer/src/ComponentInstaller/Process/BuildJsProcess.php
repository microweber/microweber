<?php

/*
 * This file is part of Component Installer.
 *
 * (c) Rob Loach (http://robloach.net)
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace ComponentInstaller\Process;

use Composer\Composer;
use Composer\Package\Package;
use Composer\Package\Loader\ArrayLoader;
use Composer\Util\Filesystem;

/**
 * Builds all JavaScript Components into one require-built.js.
 */
class BuildJsProcess extends Process
{
    /**
     * {@inheritdoc}
     */
    public function process()
    {
        return $this->compile($this->packages);
    }

    /**
     * Copy file assets from the given packages to the component directory.
     *
     * @param array $packages
     *   An array of packages.
     */
    public function compile($packages)
    {
        // Set up the initial require-build.js file.
        $destination = $this->componentDir.DIRECTORY_SEPARATOR.'require-built.js';
        $require = $this->componentDir.DIRECTORY_SEPARATOR.'require.js';
        copy($require, $destination);

        // Cycle through each package and add it to the built require.js file.
        foreach ($packages as $package) {
            // Retrieve some information about the package
            $name = isset($package['name']) ? $package['name'] : '__component__';
            $extra = isset($package['extra']) ? $package['extra'] : array();
            $componentName = $this->getComponentName($name, $extra);

            // Find where the source file is located.
            $packageDir = $this->componentDir.DIRECTORY_SEPARATOR.$componentName;
            $source = $packageDir.DIRECTORY_SEPARATOR.$componentName.'-built.js';

            // Make sure the source script is available.
            if (file_exists($source)) {
                // Build the compiled script.
                $content = file_get_contents($source);
                $output = $this->definePrefix($componentName) . $content . $this->definePostfix();

                // Append the module definition to the destination file.
                file_put_contents($destination, $output, FILE_APPEND);
            }
        }

        return true;
    }

    /**
     * Provide the initial definition prefix.
     *
     * @return string Begin the module definition.
     */
    protected function definePrefix($componentName)
    {
        // Define the module using the simplified CommonJS wrapper.
        return "\ndefine('$componentName', function (require, exports, module) {\n";
    }

    /**
     * Finish the module definition.
     *
     * @return string Close brackets to finish the module.
     */
    protected function definePostfix()
    {
        return "\n});\n";
    }
}
