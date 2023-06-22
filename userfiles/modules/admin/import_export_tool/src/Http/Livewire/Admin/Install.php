<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin;

use Livewire\Component;
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

class Install extends AdminComponent
{
    public $listeners = [
      'startInstalling'=>'startInstalling'
    ];

    public $log = '';
    public $showInstaller = false;

    private $logger;

    public function render()
    {
        return view('import_export_tool::admin.livewire-install');
    }

    public function install()
    {
        $this->showInstaller = true;
    }

    public function startInstalling()
    {

        // set logger
        $this->logger = new ImportExportToolInstallLogger();

        $moduleMigrationPath = dirname(dirname(dirname(__DIR__))) . '/migrations';
        app()->import_export_migrator->run($moduleMigrationPath);
        $logs = app()->import_export_migrator->getLogs();

        if (empty($logs)) {
            $this->log = 'Noting to migrate!';
            return $this->redirect(route('admin.import-export-tool.index'));
        }

        $fi = new \FilesystemIterator($moduleMigrationPath, \FilesystemIterator::SKIP_DOTS);

        $successCount = 0;
        $migrationsCount = iterator_count($fi);
        if (!empty($logs)) {
            foreach ($logs as $log) {
                if ($log['success']) {
                    $successCount++;
                }
            }
        }

        if ($successCount == $migrationsCount) {
            $this->log = 'Done!';
            return $this->redirect(route('admin.import-export-tool.index'));
        }

        $this->log = 'Error when installing the module!';
    }
}

class ImportExportToolInstallLogger {

    public $log = '';

    public function log($text) {
        $this->log = $text;
    }

}
