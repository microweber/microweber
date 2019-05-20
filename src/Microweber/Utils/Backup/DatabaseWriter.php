<?php
namespace Microweber\Utils\Backup;

use Microweber\Utils\Backup\Traits\DatabaseCustomFieldsWriter;
use Microweber\Utils\Backup\Traits\DatabaseContentFieldsWriter;
use Microweber\Utils\Backup\Traits\DatabaseContentDataWriter;
use Microweber\Utils\Backup\Traits\BackupLogger;
use Illuminate\Support\Facades\Cache;

class DatabaseWriter
{
	use BackupLogger;
	use DatabaseCustomFieldsWriter;
	use DatabaseContentFieldsWriter;
	use DatabaseContentDataWriter;
	
	private $_cacheGroupName = 'BackupImporting';
	public $currentStep = 0;
	public $content;

	public function setContent($content)
	{
		$this->content = $content;
		
		$this->currentStep = (int) cache_get('CurrentStep', $this->_cacheGroupName);
		if (!$this->currentStep) {
			$this->currentStep = 0;
		}
	}

	private function _getRelationship($item)
	{
		$relationship = array();
		foreach ($this->content[$item['rel_type']] as $dataItem) {
			if ($dataItem['id'] == $item['rel_id']) {
				$relationship = $dataItem;
			}
		}
		return $relationship;
	}
	
	private function _fixItemEncoding($item) {
		
		// Fix encoding
		array_walk_recursive($item, function (&$element) {
			if (is_string($element)) {
				$element = utf8_decode($element);
				$element = str_replace('Â ', ' ', $element);
				$element = str_replace("Â ", ' ', $element);
			}
		});
		
		return $item;
	}
	
	private function _unsetItemFields($item) {
		$unsetFields = array('id', 'rel_id', 'order_id', 'position');
		foreach($unsetFields as $field) {
			unset($item[$field]);
		}
		return $item;
	}
	
	private function _saveItem($table, $item) {
		
		$item = $this->_fixItemEncoding($item);

		$dbSelectParams = array();
		$dbSelectParams['no_cache'] = true;
		$dbSelectParams['limit'] = 1;
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		
		$recognizeParams = array(
			'payment_verify_token',
			'option_key',
			'option_value',
			'option_group',
			'session_id',
			'created_at',
			'title',
			'email',
			'username',
			'first_name',
			'last_name',
			'amount'
		);
		
		foreach($recognizeParams as $recognizeParam) {
			if (isset($item[$recognizeParam])) {
				$dbSelectParams[$recognizeParam] = $item[$recognizeParam];
			}
		}
		
		$checkItemIsExists = db_get($table, $dbSelectParams);
		return;
		
		/* 
		if (isset($item['email'])) {
			
			var_dump($checkItemIsExists);
			die();
		} */
		
		if ($checkItemIsExists) {
			$itemId = $checkItemIsExists['id'];
			echo $table . ': Item is allready saved.' . PHP_EOL;
		} else {
			echo $table . ': Item is saved.' . PHP_EOL;
			$item = $this->_unsetItemFields($item);
			$item['skip_cache'] = true;
			$itemId = db_save($table, $item);
		}
		$this->_saveCustomFields($item, $itemId);
		$this->_saveContentData($itemId);
	}
	
	private function _saveItemWithRelationship($table, $item) {
		
		$relationship = $this->_getRelationship($item);

		$item = $this->_fixItemEncoding($item);
		$item = $this->_unsetItemFields($item);
		
		$item['allow_html'] = true;
		$item['allow_scripts'] = true;
		$item['is_deleted'] = false;
		
		
		$dbSelectParams = array();
		$dbSelectParams['no_cache'] = true;
		$dbSelectParams['limit'] = 1;
		$dbSelectParams['single'] = true;
		$dbSelectParams['do_not_replace_site_url'] = 1;
		$dbSelectParams['title'] = $item['title'];
		
		$checkItemIsExists = db_get($table, $dbSelectParams);
		
		if ($checkItemIsExists) {
			$itemId = $checkItemIsExists['id'];
			echo $table . ': Item is allready saved.' . PHP_EOL;
		} else {
			echo $table . ': Item is saved.' . PHP_EOL;
			$item['skip_cache'] = true;
			$itemId = db_save($table, $item);
		}
		
		$this->_saveCustomFields($item, $itemId);
		$this->_saveContentData($itemId);
		
	}
	
	public function runWriter()
	{
		
		$totalSteps = 15;
		
		$this->setLogInfo('Importing database batch: ' . $this->currentStep . '/' . $totalSteps);
		
		$importTables = array('users', 'categories', 'modules', 'comments', 'content', 'media', 'options', 'calendar', 'cart_orders');
		//$importTables = array('cart_orders');
		
		// All db tables
		$itemsForSave = array();
		foreach ($importTables as $table) {
			if (isset($this->content[$table])) {
				foreach ($this->content[$table] as $item) {
					
					/* if (isset($item['rel_type']) && isset($item['rel_id'])) {			
						$this->_saveItemWithRelationship($table, $item);
						continue;
					} */
					
					$itemsForSave[] = $item;
				}
			}
		}
		
		if (!empty($itemsForSave)) {
			
			$totalItemsForSave = sizeof($itemsForSave);
			$totalItemsForBatch = round($totalItemsForSave / $totalSteps, 0);
			
			$itemsBatch = array_chunk($itemsForSave, $totalItemsForBatch);
			
			if (!isset($itemsBatch[$this->currentStep])) {
				
				$this->setLogInfo('No items in batch for current step.');
				$this->setLogInfo('Done!');
				
				$this->_finishUp();
				
				return array("success"=>"Done! All steps are finished.");
			}
			
			$success = array();
			foreach($itemsBatch[$this->currentStep] as $item) {
				echo 'Save item' . PHP_EOL;
				$success[] = $this->_saveItem($table, $item);
			}
			
			echo 'Save cache ... ' .$this->currentStep. PHP_EOL;
			
			cache_save($this->currentStep + 1, 'CurrentStep',$this->_cacheGroupName, 60 * 10);
			
		}
	}
	
	private function _finishUp() {
		
		
		// cache_delete($this->_cacheGroupName);
		
		$this->setLogInfo('Cleaning up custom css cache');
		
		mw()->template->clear_cached_custom_css();
		
		if (function_exists('mw_post_update')) {
			mw_post_update();
		}
		
		$this->setLogInfo('Cleaning up system cache');
		
		mw()->cache_manager->clear();
	}
	
}
