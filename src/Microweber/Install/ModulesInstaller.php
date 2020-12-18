<?php

namespace Microweber\Install;


class ModulesInstaller
{
    public $logger = null;

    public function run()
    {

        mw()->modules->logger = $this->logger;

        mw()->modules->install();
    }
}
