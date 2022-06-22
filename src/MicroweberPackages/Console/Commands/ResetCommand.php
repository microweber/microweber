<?php

namespace MicroweberPackages\Console\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\App\Http\Controllers\FrontendController;
use Symfony\Component\Console\Input\InputArgument;
use MicroweberPackages\Install\DbInstaller;
use MicroweberPackages\Install\ModulesInstaller;
use MicroweberPackages\Install\DefaultOptionsInstaller;

class ResetCommand extends Command
{
    protected $name = 'microweber:reset';
    protected $description = 'Reset Microweber';


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'microweber:reset {--only-content=}';

    public function handle()
    {
    	if($this->option('only-content')) {
    		$truncateTables = '
				categories,
				categories_items,
				comments,
				content,
				content_data,
				content_fields,
				content_fields_drafts,
				custom_fields,
				custom_fields_values,
				elements,
				media,
				menus,
				modules
			';
    		$tables = explode(',', $truncateTables);
	    } else {
	    	$dbInstaller = new DbInstaller();
	    	$tables = array();
	    	foreach ($dbInstaller->getSystemSchemas() as $schemas) {
	    		if (method_exists($schemas, 'get')) {
	    			$schemaArray = $schemas->get();
	    			if (is_array($schemaArray)) {
	    				foreach ($schemaArray as $table => $columns) {
	    					$tables[] = $table;
	    				}
	    			}
	    		}
	    	}
	    }

    	foreach ($tables as $table)
    	{
    		$table = trim($table);
    		\DB::table($table)->truncate();
    		$this->info('Truncate table: ' . $table);
    	}

    	// Reload modules
    	$installer = new ModulesInstaller();
    	$installer->run();

    	$installer = new DefaultOptionsInstaller();
    	$installer->run();

    	// Remove files

    	$removeFiles = array();
/*
    	$removeFiles[] = userfiles_path() . 'cache';
    	$removeFiles[] = userfiles_path() . 'media';
    	$removeFiles[] = userfiles_path() . 'css';
    	*/
    	$removeFiles[] = userfiles_path() . 'backup-export-session.log';
    	$removeFiles[] = userfiles_path() . 'mw_content.json';
        $removeFiles[] = userfiles_path() . 'install_item_log.txt';

        $removeFiles[] = storage_path() . '\install_log.txt';
    	$removeFiles[] = storage_path() . '\localhost.sqlite';
/*    	$removeFiles[] = storage_path() . '\logs';
    	$removeFiles[] = storage_path() . '\cache';
    	$removeFiles[] = storage_path() . '\app';*/
    	$removeFiles[] = storage_path() . '\backup_content';
    	$removeFiles[] = storage_path() . '\export_content';
    	$removeFiles[] = storage_path() . '\debugbar';
    	$removeFiles[] = storage_path() . '\html_purifier';
    	/*
    	$removeFiles[] = storage_path() . '\framework\cache' . DIRECTORY_SEPARATOR;
    	$removeFiles[] = storage_path() . '\framework\sessions'. DIRECTORY_SEPARATOR;
    	$removeFiles[] = storage_path() . '\framework\views'. DIRECTORY_SEPARATOR;*/

        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(storage_path()));
        foreach ($rii as $file) {
            if (!$file->isDir()) {
                if (strpos($file->getPathname(), '.sqlite') !== false) {
                    $removeFiles[] = $file->getPathname();
                }
            }
        }

        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(userfiles_path()));
        foreach ($rii as $file) {
            if (!$file->isDir()) {
                if (strpos($file->getPathname(), 'backup_') !== false) {
                    $removeFiles[] = $file->getPathname();
                }
                if (strpos($file->getPathname(), 'backup_export_') !== false) {
                    $removeFiles[] = $file->getPathname();
                }
                if (strpos($file->getPathname(), 'backup-import-') !== false) {
                    $removeFiles[] = $file->getPathname();
                }
                if (strpos($file->getPathname(), 'backup_import_') !== false) {
                    $removeFiles[] = $file->getPathname();
                }
            }
        }
        // Except files
        $exceptedFiles = [];
        foreach ($removeFiles as $file) {
            if (strpos($file, '.htaccess') !== false) {
                continue;
            }
            if (strpos($file, '.gitignore') !== false) {
                continue;
            }
            if (strpos($file, 'index.html') !== false) {
                continue;
            }
            $exceptedFiles[] = $file;
        }

        $removeFiles = $exceptedFiles;

    	foreach($removeFiles as $fileOrDir) {
    		if (is_file($fileOrDir)) {
    			$this->info('Remove file: ' . $fileOrDir);
    			@unlink($fileOrDir);
    		} else {
    			$this->info('Remove files from path: ' . $fileOrDir);
    			$this->_removeFilesFromPath($fileOrDir);
    		}
    	}

    	\Cache::flush();
    }

    /**
     * Remove dir recursive
     * @param string $dir
     */
    private function _removeFilesFromPath($dir)
    {
    	if (!is_dir($dir)) {
    		return;
    	}

    	$files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);

    	foreach ($files as $fileinfo) {
    		$todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
    		@$todo($fileinfo->getRealPath());
    	}

    	@rmdir($dir);
    }

}
