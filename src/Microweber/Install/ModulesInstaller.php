<?php

namespace Microweber\Install;


class ModulesInstaller
{
    public function run()
    {
        mw()->modules->install();
    }
}
