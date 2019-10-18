<?php

namespace Microweber\Install;


class ModulesInstaller
{
    public $logger = null;

    public function run()
    {

        mw()->modules_manager->logger = $this->logger;

        mw()->modules_manager->install();
    }
}
