<?php

namespace MicroweberPackages\Backup\Exporters;

use MicroweberPackages\Backup\EncodingFix;

class JsonExport extends DefaultExport
{
    /**
     * The type of export
     * @var string
     */
    public $type = 'json';

    public function start()
    {
        $dump = $this->getDump();
        $jsonFilename = $this->_generateFilename();

        file_put_contents($jsonFilename['filepath'], $dump);

        return array("files" => array($jsonFilename));
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

        return json_encode(EncodingFix::encode($data));
    }

}


