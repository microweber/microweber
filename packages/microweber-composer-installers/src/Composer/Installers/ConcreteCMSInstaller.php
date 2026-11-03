<?php

namespace Composer\Installers;

class ConcreteCMSInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array(
        'core'       => 'concrete/',
        'block'      => 'application/blocks/{$name}/',
        'package'    => 'packages/{$name}/',
        'theme'      => 'application/themes/{$name}/',
        'update'     => 'updates/{$name}/',
    );
}
