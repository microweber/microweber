<?php

namespace MicroweberPackages\Export\Formats;

use MicroweberPackages\Backup\EncodingFix;

class JsonExport extends DefaultExport
{
    /**
     * The type of export
     * @var string
     */
    public $type = 'json';
    public $filename = false;
    public $useEncodeFix = true;

    public function start()
    {
        $dump = $this->getDump();
        $jsonFilename = $this->_generateFilename($this->filename);

        file_put_contents($jsonFilename['filepath'], $dump);

        return array("files" => array($jsonFilename));
    }

    public function setFilename($filename) {
        $this->filename = $filename;
    }

    public function getDump()
    {
        $data = $dump = $this->data;

        if (is_array($dump)) {
            array_walk_recursive(
                $dump,
                function (&$value) {
                    if (is_string($value)) {
                        $value = mw()->url_manager->replace_site_url($value);
                    }
                }
            );
            if (is_array($dump)) {
                $data = $dump ;
            }
        }

        if ($this->useEncodeFix) {
            return json_encode(EncodingFix::encode($data));
        } else {
            return json_encode($data);
        }
    }

}


