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

/**
 * Microweber - Backup Module Database Writer
 * @namespace Microweber\Utils\Backup
 * @package DatabaseWriter
 * @author Bozhidar Slaveykov
 */
class DatabaseWriter
{
	use DatabaseMediaWriter;
	use DatabaseMenusWriter;
	use DatabaseRelationWriter;
	use DatabaseCustomFieldsWriter;
	use DatabaseContentWriter;
	use DatabaseContentFieldsWriter;
	use DatabaseContentDataWriter;
	use DatabaseCategoriesWriter;
	use DatabaseCategoryItemsWriter;
	
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
		
		if ($this->currentStep > $this->totalSteps) {
			$this->_finishUp();
			$this->currentStep = 0;
		}
		
		return $this->currentStep;
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
		
		if ($item['save_to_table'] == 'media' && empty($item['title'])) {
			$this->_saveMedia($item);
			return;
		}
		
		if ($item['save_to_table'] == 'menus' && empty($item['title'])) {
			$this->_saveMenuItem($item);
			return;
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
		$itemIdDatabase = DatabaseSave::save($saveItem['save_to_table'], $saveItem);
		
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
			
			$this->_saveCustomFields($savedItem);
			$this->_saveContentData($savedItem);
			$this->_saveCategoriesItems($savedItem);
			
			$this->_fixRelations($savedItem);
			$this->_fixParentRelationship($savedItem);
			$this->_fixCategoryParents();
			$this->_fixMenuParents();
			
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
	}
	
	public function runWriterWithBatch() 
	{
		if ($this->getCurrentStep() == 0) {
			// Clear old log file
			BackupImportLogger::clearLog();
		}
		
		BackupImportLogger::setLogInfo('Importing database batch: ' . $this->getCurrentStep() . '/' . $this->totalSteps);

		if (empty($this->content)) {
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
			$totalItemsForBatch = round($totalItemsForSave / $this->totalSteps, 0);
			
			$itemsBatch = array_chunk($itemsForSave, $totalItemsForBatch);
			
			if (!isset($itemsBatch[$this->getCurrentStep()])) {
				
				BackupImportLogger::setLogInfo('No items in batch for current step.');
				$this->_finishUp();
				
				return array("success"=>"Done! All steps are finished.");
			}
			
			$success = array();
			foreach($itemsBatch[$this->getCurrentStep()] as $item) {
				//echo 'Save item' . PHP_EOL;
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
		}
		
		return $log;
	}
	
	/**
	 * Clear all cache on framework 
 	 */
	private function _finishUp() {
		
		// cache_delete($this->_cacheGroupName);
		
		BackupImportLogger::setLogInfo('Cleaning up custom css cache');
		
		mw()->template->clear_cached_custom_css();
		
		if (function_exists('mw_post_update')) {
			mw_post_update();
		}
		
		BackupImportLogger::setLogInfo('Cleaning up system cache');
		
		mw()->cache_manager->clear();
		
		BackupImportLogger::setLogInfo('Done!');
	}
	
}
