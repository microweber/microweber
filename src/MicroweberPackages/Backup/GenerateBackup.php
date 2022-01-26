<?php

namespace MicroweberPackages\Backup;

use MicroweberPackages\Export\Export;

class GenerateBackup extends Export
{
    public $type = 'zip';
    public $exportAllData = true;

}
