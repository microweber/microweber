<?php
namespace MicroweberPackages\Import\Traits;

trait ExportGetSet
{
    /**
     * @var bool
     */
    public $exportWithZip = false;

    public $exportFileName = false;

    /**
     * @param bool $bool
     * @return void
     */
    public function setExportWithZip(bool $bool) {
        $this->exportWithZip = $bool;
    }

    /**
     * Export media
     * @var string
     */
    public $exportMedia = false;

    /**
     * @param bool $bool
     * @return void
     */
    public function setExportMedia(bool $bool)
    {
        $this->exportMedia = $bool;
    }

    /**
     * Export modules
     * @var bool
     */
    public $exportModules = false;

    /**
     * @param array $modules
     * @return void
     */
    public function setExportModules(array $modules)
    {
        $this->exportModules = $modules;
    }

    /**
     * Export templates
     * @var bool
     */
    public $exportTemplates = false;

    /**
     * @param array $templates
     * @return void
     */
    public function setExportTemplates(array $templates)
    {
        $this->exportTemplates = $templates;
    }

    /**
     * Export only current template
     */
    public $exportOnlyTemplate = false;

    /**
     * @param string $template
     * @return void
     */
    public function setExportOnlyTemplate(string $template)
    {
        $this->exportOnlyTemplate = $template;
    }

    /**
     * @param $tables
     * @return void
     */
    public function setExportTables(array $tables) {
        $this->setExportData('tables', $tables);
    }

    public $allowSkipTables = true;
    public function setAllowSkipTables(bool $bool) {
        $this->allowSkipTables = $bool;
    }

    /**
     * @var
     */
    public $skipTables;

    /**
     * @param $tables
     * @return void
     */
    public function setSkipTables(array $tables) {
        $this->skipTables($tables);
    }

    /**
     * @param $tableOrTables
     * @return void
     */
    public function addSkipTable(string $tableOrTables)
    {
        if (is_array($tableOrTables)) {
            foreach($tableOrTables as $table) {
                $this->skipTables[] = $table;
            }
        } else {
            $this->skipTables[] = $tableOrTables;
        }
    }

    /**
     * @param $filename
     * @return void
     */
    public function setExportFileName($filename)
    {
        $this->exportFileName = $filename;
    }
}
