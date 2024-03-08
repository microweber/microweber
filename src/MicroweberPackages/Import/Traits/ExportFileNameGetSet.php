<?php
namespace MicroweberPackages\Import\Traits;

trait ExportFileNameGetSet
{
    /**
     * @var array
     */
    public $exportFileName;

    /**
     * @param $filename
     * @return void
     */
    public function setExportFileName($filename)
    {
        $filename = trim($filename);
        $filename = str_slug($filename);
        $filename = str_replace('-', '_', $filename);

        $this->exportFileName = $filename;
    }

}
