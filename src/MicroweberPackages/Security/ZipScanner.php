<?php

namespace MicroweberPackages\Security;

class ZipScanner
{
    public function scanZip($zipFile)
    {
        $zipArchive = new \ZipArchive();
        $zipArchive->open($zipFile);

        for ($i = 0; $i < $zipArchive->numFiles; $i++) {
            $stat = $zipArchive->statIndex($i);
            print_r(basename($stat['name']) . PHP_EOL);
        }
    }

    public function scanString($string)
    {



    }
}
