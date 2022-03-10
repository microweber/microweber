<?php

namespace MicroweberPackages\Security;

use MicroweberPackages\SecurityScanner\Scanner;

class ZipScanner
{
    public $isOk = false;

    public function scanZip($zipFile)
    {
        $zipArchive = new \ZipArchive();
        $zipArchive->open($zipFile);

        $viruses = [];
        for ($i = 0; $i < $zipArchive->numFiles; $i++) {
            $stat = $zipArchive->statIndex($i);
            $zipfileBasename = basename($stat['name']);
            $zipfileContent = $zipArchive->getFromName($zipfileBasename);

            $scan = new Scanner();
            $scanStatus = $scan->scanString($zipfileContent);
            if (isset($scanStatus['error']) && $scanStatus['error']) {
                $viruses[] = $scanStatus;
            }
        }

        if (empty($viruses)) {
            $this->isOk = true;
        } else {
            $this->isOk = false;
        }
    }

    public function isOk()
    {
        return $this->isOk;
    }
}
