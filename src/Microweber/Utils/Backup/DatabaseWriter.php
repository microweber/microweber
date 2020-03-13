<?php
namespace Microweber\Utils\Backup;

use Illuminate\Support\Facades\Cache;

use Microweber\Utils\Backup\Traits\DatabaseCustomFieldsWriter;
use Microweber\Utils\Backup\Traits\DatabaseContentFieldsWriter;
use Microweber\Utils\Backup\Traits\DatabaseContentDataWriter;
use Microweber\Utils\Backup\Traits\DatabaseCategoryItemsWriter;
use Microweber\Utils\Backup\Traits\DatabaseCategoriesWriter;
use Microweber\Utils\Backup\Traits\DatabaseRelationWriter;
use Microweber\Utils\Backup\Traits\DatabaseContentWriter;
use Microweber\Utils\Backup\Traits\DatabaseMenusWriter;
use Microweber\Utils\Backup\Traits\DatabaseMediaWriter;
use Microweber\Utils\Backup\Loggers\BackupImportLogger;
use Microweber\Utils\Backup\Traits\DatabaseModuleWriter;
use Microweber\Utils\Backup\Traits\DatabaseTaggingTaggedWriter;

/**
 * Microweber - Backup Module Database Writer
 * @namespace Microweber\Utils\Backup
 * @package DatabaseWriter
 * @author Bozhidar Slaveykov
 */
class DatabaseWriter
{
	use DatabaseMediaWriter;
	use DatabaseModuleWriter;
	use DatabaseMenusWriter;
	use DatabaseRelationWriter;
	use DatabaseCustomFieldsWriter;
	use DatabaseContentWriter;
	use DatabaseContentFieldsWriter;
	use DatabaseContentDataWriter;
	use DatabaseCategoriesWriter;
	use DatabaseCategoryItemsWriter;
	use DatabaseTaggingTaggedWriter;
	
	/**
	 * The current batch step.
	 * @var integer
	 */
	public $currentStep = 0;
	
	/**
	 * The total steps for batch.
	 * @var integer
	 */
	public $totalSteps = 10;
	
	/**
	 * Overwrite by id
	 * @var string
	 */
	public $overwriteById = false;

    /**
     * Delete old content
     * @var bool
     */
	public $deleteOldContent = false;

	/**
	 * The content from backup file
	 * @var string
	 */
	public $content;
	
	/**
	 * The name of cache group for backup file.
	 * @var string
	 */
	private $_cacheGroupName = 'BackupImporting';
	
	public function setContent($content)
	{
		$this->content = $content;
	}
	
	public function getCurrentStep() {
		
		$this->currentStep = (int) cache_get('CurrentStep', $this->_cacheGroupName);
		
		if (!$this->currentStep) {
			$this->currentStep = 0;
		}
		
		/*
		if ($this->currentStep > $this->totalSteps) {
			//$this->_finishUp('getCurrentStep()');
			//$this->currentStep = 0;
		}*/
		
		return $this->currentStep;
	}
	
	public function setOverwriteById($overwrite) {
		$this->overwriteById = $overwrite;
	}


	public function setDeleteOldContent($delete) {
	    $this->deleteOldContent = $delete;
    }

	/**
	 * Unset item fields
	 * @param array $item
	 * @return array
	 */
	private function _unsetItemFields($item) {
		$unsetFields = array('id', 'rel_id', 'order_id', 'parent_id', 'position');
		foreach($unsetFields as $field) {
			unset($item[$field]);
		}
		return $item;
	}
	
	private function _saveItemDatabase($item) {
		
		if ($this->overwriteById  && isset($item['id'])) {
			
			// We will overwrite content by id from our db structure
			$dbSelectParams = array();
			$dbSelectParams['no_cache'] = true;
			$dbSelectParams['limit'] = 1;
			$dbSelectParams['single'] = true;
			$dbSelectParams['do_not_replace_site_url'] = 1;
			$dbSelectParams['fields'] = 'id';
			$dbSelectParams['id'] = $item['id'];
			
			$itemIdDatabase = DatabaseSave::save($item['save_to_table'], $item);
			
			return array('item'=>$item, 'itemIdDatabase'=>$itemIdDatabase);
		}
		
		if ($item['save_to_table'] == 'options') {
			if (isset($item['option_key']) && $item['option_key'] == 'current_template') {
				if (!is_dir(userfiles_path().'/templates/'.$item['option_value'])) {
					// Template not found
					return;
				}
			}
		}
		
		if ($item['save_to_table'] == 'custom_fields') {
			$this->_saveCustomField($item);
			return;
		}
		
		if ($item['save_to_table'] == 'content_data') {
			$this->_saveContentData($item);
			return;
		}
		
		if ($item['save_to_table'] == 'tagging_tagged') {
			$this->_taggingTagged($item);
			return;
		}
		
		if (isset($item['rel_type']) && $item['rel_type'] == 'modules' && $item['save_to_table'] == 'media') {
			$this->_saveModule($item);
			return;
		}
		
		if ($item['save_to_table'] == 'media' && empty($item['title'])) {
			$this->_saveMedia($item);
			return;
		}
		
		if ($item['save_to_table'] == 'menus') {
			if($this->_saveMenuItem($item)) {
				$this->_fixMenuParents();
			}
			return;
		}
		
		if ($item['save_to_table'] == 'categories_items') {
			if ($this->_saveCategoriesItems($item)) {
				$this->_fixCategoryParents();
				return;
			}
		}
		
		if ($item['save_to_table'] == 'categories') {
			$this->_fixCategoryParents();
		}
		
		// Dont import menus without title
		if ($item['save_to_table'] == 'content_fields' && empty($item['title'])) {
			$this->_saveContentField($item);
			return; 
		}
		
		$dbSelectParams = array();
		$dbSelectParams['no_cache'] = true;
		$dbSelectParams['limit'] = 1;
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		$dbSelectParams['fields'] = 'id';
		
		foreach(DatabaseDublicateChecker::getRecognizeFields($item['save_to_table']) as $tableField) {
			if (isset($item[$tableField])) {
				$dbSelectParams[$tableField] = $item[$tableField];
			}
		}
		
		$checkItemIsExists = db_get($item['save_to_table'], $dbSelectParams);
		if ($checkItemIsExists) {
			BackupImportLogger::setLogInfo('Update item ' . $this->_getItemFriendlyName($item) . ' in ' . $item['save_to_table']);
		} else {
			BackupImportLogger::setLogInfo('Save item ' . $this->_getItemFriendlyName($item) . ' in ' . $item['save_to_table']);
		}
		
		$saveItem = $this->_unsetItemFields($item);
		if ($checkItemIsExists) {
			$saveItem['id'] = $checkItemIsExists['id'];
		}
		
		$saveAsContent = false;
		if ($saveItem['save_to_table'] == 'content') {
			$saveAsContent = true;
			if (isset($saveItem['content_type']) && $saveItem['content_type'] == 'page') {
				$saveAsContent = false;
			}
		}
		
		if ($saveAsContent) {
			$itemIdDatabase = DatabaseSaveContent::save($saveItem['save_to_table'], $saveItem);
		} else {
			$itemIdDatabase = DatabaseSave::save($saveItem['save_to_table'], $saveItem);
		}
		
		return array('item'=>$item, 'itemIdDatabase'=>$itemIdDatabase);
	}
	
	private function _getItemFriendlyName($item) {
		$name = '';
		if (isset($item['title'])) {
			$name = $item['title'];
		}
		if (isset($item['name'])) {
			$name = $item['name'];
		}
		return $name;
	}
	
	/**
	 * Save item in database
	 * @param string $table
	 * @param array $item
	 */
	private function _saveItem($item) {
		
		$savedItem = $this->_saveItemDatabase($item);
		
		if ($savedItem) {
			$this->_fixRelations($savedItem);
			$this->_fixParentRelationship($savedItem);
		}
		
		//echo $item['save_to_table'];
		//die();
	}
	
	/**
	 * Run database writer.
	 * @return string[]
	 */
	public function runWriter()
	{
		/*
		 //$importTables = array('users', 'categories', 'modules', 'comments', 'content', 'media', 'options', 'calendar', 'cart_orders');
		 */
		
		/* $importTables = array('comments');
		
		foreach ($importTables as $table) {
		if (isset($this->content[$table])) {
		foreach ($this->content[$table] as $item) {
		$item['save_to_table'] = $table;
		$this->_saveItem($item);
		$items[] = $item;
		}
		}
		}
		
		var_dump($items);
		return; */

		foreach ($this->content as $table=>$items) {
			if (!empty($items)) {
				foreach($items as $item) {
					$item['save_to_table'] = $table;
					$this->_saveItem($item);
				}
			}
		}
		
		$this->_finishUp('runWriterBottom');
		cache_save($this->totalSteps, 'CurrentStep', $this->_cacheGroupName, 60 * 10);
		
	}
	
	public function runWriterWithBatch()
	{
		if ($this->getCurrentStep() == 0) {
			BackupImportLogger::clearLog();
			$this->_deleteOldContent();
		}

		BackupImportLogger::setLogInfo('Importing database batch: ' . ($this->getCurrentStep() + 1) . '/' . $this->totalSteps);
		
		if (empty($this->content)) {
			$this->_finishUp('runWriterWithBatchNothingToImport');
			return array("success"=>"Nothing to import.");
		}
		
		//$importTables = array('users', 'categories', 'modules', 'comments', 'content', 'media', 'options', 'calendar', 'cart_orders');
		//$importTables = array('content', 'categories');
		$excludeTables = array();
		
		// All db tables
		$itemsForSave = array();
		foreach ($this->content as $table=>$items) {
			
			if (in_array($table, $excludeTables)) {
				continue;
			}
			
			if (!empty($items)) {
				foreach($items as $item) {
					$item['save_to_table'] = $table;
					$itemsForSave[] = $item;
				}
			}
			BackupImportLogger::setLogInfo('Save content to table: ' . $table);
		}
		
		if (!empty($itemsForSave)) {
			
			$totalItemsForSave = sizeof($itemsForSave);
			$totalItemsForBatch = ($totalItemsForSave / $this->totalSteps);
            $totalItemsForBatch = ceil($totalItemsForBatch);
			
			if ($totalItemsForBatch > 0) {
				$itemsBatch = array_chunk($itemsForSave, $totalItemsForBatch);
			} else {
				$itemsBatch[0] = $itemsForSave;
			}
			
			if (!isset($itemsBatch[$this->getCurrentStep()])) {
				
				BackupImportLogger::setLogInfo('No items in batch for current step.');
				
				cache_save($this->totalSteps, 'CurrentStep', $this->_cacheGroupName, 60 * 10);
				
				return array("success"=>"Done! All steps are finished.");
			}
			
			$success = array();
			foreach($itemsBatch[$this->getCurrentStep()] as $item) {
				//echo 'Save item' . PHP_EOL;
				//	BackupImportLogger::setLogInfo('Save content to table: ' . $item['save_to_table']);
				$success[] = $this->_saveItem($item);
			}
			
			//echo 'Save cache ... ' .$this->currentStep. PHP_EOL;
			
			cache_save($this->getCurrentStep() + 1, 'CurrentStep', $this->_cacheGroupName, 60 * 10);
			
		}
		
	}
	
	public function getImportLog() {
		
		$log = array();
		$log['current_step'] = $this->getCurrentStep();
		$log['total_steps'] = $this->totalSteps;
		$log['precentage'] = ($this->getCurrentStep() * 100) / $this->totalSteps;
		
		if ($this->getCurrentStep() >= $this->totalSteps) {
			
			$log['done'] = true;
			
			// Finish up
			$this->_finishUp();
			
			// Clear log file
			BackupImportLogger::clearLog();
			
		}
		
		return $log;
	}

	private function _deleteOldContent()
    {
        // Delete old content
        if (!empty($this->content) && $this->deleteOldContent) {
            foreach ($this->content as $table=>$items) {
                if ($table == 'users' || $table == 'users_oauth' || $table == 'system_licenses') {
                    continue;
                }
                \DB::table($table)->truncate();
            }
        }
    }
	
	/**
	 * Clear all cache on framework
	 */
	private function _finishUp($callFrom = '') {
		
		// BackupImportLogger::setLogInfo('Call from: ' . $callFrom);
		
		// cache_delete($this->_cacheGroupName);
		
		if (function_exists('mw_post_update')) {
			mw_post_update();
		}
		
		BackupImportLogger::setLogInfo('Cleaning up system cache');
		
		mw()->cache_manager->clear();
		
		BackupImportLogger::setLogInfo('Done!');
	}
}
