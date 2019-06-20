<?php

namespace Microweber\Commands;

use Illuminate\Console\Command;
use Microweber\Controllers\DefaultController;
use Microweber\App\Providers\Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputArgument;
use Microweber\Install\DbInstaller;

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
    	}
    }
    
    protected function getArguments()
    {
    	return [
    		['only-content', InputArgument::OPTIONAL, 'Reset only content.']
    	];
    }
}
