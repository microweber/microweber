<?php
namespace Microweber\Commands;

use Illuminate\Console\Command;
use Microweber\Controllers\DefaultController;
use Microweber\App\Providers\Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputArgument;
use Microweber\Install\DbInstaller;
use Microweber\Install\ModulesInstaller;
use Microweber\Install\DefaultOptionsInstaller;
use Microweber\App\Providers\Illuminate\Support\Facades\Cache;

class ResetCommand extends Command
{
    protected $name = 'microweber:reset';
    protected $description = 'Reset Microweber';
    protected $controller;

    public function __construct(DefaultController $controller)
    {
    	$this->controller = $controller;
    	parent::__construct();
    }

    public function fire()
    {
    	if($this->argument('only-content')) {
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
    		DB::table($table)->truncate();
    		$this->info('Truncate table: ' . $table);
    	}
    	
    	// Reload modules
    	$installer = new ModulesInstaller();
    	$installer->run();
    	
    	$installer = new DefaultOptionsInstaller();
    	$installer->run();
    	
    	// Remove files
    	
    	$removeFiles = array();
    	
    	$removeFiles[] = userfiles_path() . 'cache';
    	$removeFiles[] = userfiles_path() . 'media';
    	$removeFiles[] = userfiles_path() . 'css';
    	$removeFiles[] = userfiles_path() . 'elements';
    	$removeFiles[] = userfiles_path() . 'backup-export-session.log';
    	$removeFiles[] = userfiles_path() . 'install_item_log.txt';
    	$removeFiles[] = userfiles_path() . 'install_log.txt';
    	$removeFiles[] = userfiles_path() . 'mw_content.json';
    	
    	$removeFiles[] = storage_path() . '\localhost.sqlite';
    	$removeFiles[] = storage_path() . '\logs';
    	$removeFiles[] = storage_path() . '\cache';
    	$removeFiles[] = storage_path() . '\app';
    	$removeFiles[] = storage_path() . '\backup_content';
    	$removeFiles[] = storage_path() . '\framework\cache' . DIRECTORY_SEPARATOR;
    	$removeFiles[] = storage_path() . '\framework\sessions'. DIRECTORY_SEPARATOR;
    	$removeFiles[] = storage_path() . '\framework\views'. DIRECTORY_SEPARATOR;
    	
    	foreach($removeFiles as $fileOrDir) {
    		if (is_file($fileOrDir)) {
    			$this->info('Remove file: ' . $fileOrDir);
    			@unlink($fileOrDir);
    		} else {
    			$this->info('Remove files from path: ' . $fileOrDir);
    			$this->_removeFilesFromPath($fileOrDir);
    		}
    	}
    	
    	@mkdir(storage_path() . '\framework\cache');
    	@mkdir(storage_path() . '\framework\sessions');
    	@mkdir(storage_path() . '\framework\views');
    	
    	Cache::flush();
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
    
    protected function getArguments()
    {
    	return [
    		['only-content', InputArgument::OPTIONAL, 'Reset only content.']
    	];
    }
}
