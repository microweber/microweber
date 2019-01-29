<?php

namespace Microweber\Utils\Adapters\Packages\Helpers;

use Composer\Installer;

/**
 * Class ControllerInterface
 */
interface ComposerControllerInterface
{
    /**
     * Handle the request and return the output html.
     *
     * @param \Input $input
     *
     * @return string
     */
    public function handle($input);
}
