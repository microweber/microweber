<?php

namespace MicroweberPackages\Backup;

use MicroweberPackages\Import\Import;

class Restore extends Import
{
    public $batchImporting = true;
    public $ovewriteById = true;
    public $deleteOldContent = true;


}
