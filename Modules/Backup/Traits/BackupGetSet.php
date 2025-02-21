<?php
namespace Modules\Backup\Traits;

trait BackupGetSet
{
    /**
     * @var bool
     */
    public $backupWithZip = false;

    /**
     * @param bool $bool
     * @return void
     */
    public function setBackupWithZip(bool $bool) {
        $this->backupWithZip = $bool;
    }

    /**
     * Backup media
     * @var string
     */
    public $backupMedia = false;

    /**
     * @param bool $bool
     * @return void
     */
    public function setBackupMedia(bool $bool)
    {
        $this->backupMedia = $bool;
    }

    /**
     * Export modules
     * @var bool
     */
    public $backupModules = false;

    /**
     * @param array $modules
     * @return void
     */
    public function setBackupModules(array $modules)
    {
        $this->backupModules = $modules;
    }

    /**
     * Export templates
     * @var bool
     */
    public $backupTemplates = false;

    /**
     * @param array $templates
     * @return void
     */
    public function setBackupTemplates(array $templates)
    {
        $this->backupTemplates = $templates;
    }

    /**
     * Export only current template
     */
    public $backupOnlyTemplate = false;

    /**
     * @param string $template
     * @return void
     */
    public function setBackupOnlyTemplate(string $template)
    {
        $this->backupOnlyTemplate = $template;
    }

    /**
     * @param $tables
     * @return void
     */
    public function setBackupTables(array $tables) {
        $this->setBackupData('tables', $tables);
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
        $this->skipTables = $tables;
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
