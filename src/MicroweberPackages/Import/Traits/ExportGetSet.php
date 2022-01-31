<?php
namespace MicroweberPackages\Import\Traits;

trait ExportGetSet
{
    public $exportWithZip = false;

    /**
     * Export media
     * @var string
     */
    public $exportMedia = false;
    public function setExportMedia(bool $bool)
    {
        $this->exportMedia = $bool;
    }

    /**
     * Export modules
     * @var bool
     */
    public $exportModules = false;
    public function setExportModules(array $modules)
    {
        $this->exportModules = $modules;
    }

    /**
     * Export templates
     * @var bool
     */
    public $exportTemplates = false;
    public function setExportTemplates(array $templates)
    {
        $this->exportTemplates = $templates;
    }

    /**
     * Export only current template
     */
    public $exportOnlyTemplate = false;

    public function setExportOnlyTemplate(string $template)
    {
        $this->exportOnlyTemplate = $template;
    }

    /**
     * Add skip tables
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
     * @var
     */
    public $exportTables;

    /**
     * @param $tables
     * @return void
     */
    public function setExportTables(array $tables) {
        $this->skipTables = [];
        $this->exportTables = $tables;
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
}
