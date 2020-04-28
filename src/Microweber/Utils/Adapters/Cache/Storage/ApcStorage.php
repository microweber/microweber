<?php

namespace Microweber\Utils\Adapters\Cache\Storage;

use Illuminate\Cache\ApcStore;
use Illuminate\Cache\ApcWrapper;

class ApcStorage extends ApcStore
{
    public function __construct($prefix = '')
    {
        $apc = new ApcWrapper();

        return parent::__construct($apc, $prefix);
    }
}
