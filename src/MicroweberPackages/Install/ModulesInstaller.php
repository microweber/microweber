<?php

namespace MicroweberPackages\Install;


class ModulesInstaller
{
    public $logger = null;

    public function run()
    {
        mw()->module_manager->logger = $this->logger;
        mw()->module_manager->install();
    }
}
