<?php
namespace MicroweberPackages\Import;


use MicroweberPackages\Backup\Loggers\DefaultLogger;
use MicroweberPackages\Export\SessionStepper;
use MicroweberPackages\Import\Formats\ZipReader;
use MicroweberPackages\Import\Loggers\ImportLogger;
use MicroweberPackages\Import\Traits\DatabaseCategoriesWriter;
use MicroweberPackages\Import\Traits\DatabaseCategoryItemsWriter;
use MicroweberPackages\Import\Traits\DatabaseContentDataWriter;
use MicroweberPackages\Import\Traits\DatabaseContentFieldsWriter;
use MicroweberPackages\Import\Traits\DatabaseContentWriter;
use MicroweberPackages\Import\Traits\DatabaseCustomFieldsWriter;
use MicroweberPackages\Import\Traits\DatabaseMediaWriter;
use MicroweberPackages\Import\Traits\DatabaseMenusWriter;
use MicroweberPackages\Import\Traits\DatabaseModuleWriter;
use MicroweberPackages\Import\Traits\DatabaseRelationWriter;
use MicroweberPackages\Import\Traits\DatabaseTaggingTaggedWriter;
use MicroweberPackages\Utils\Zip\Zip;

/**
 * Microweber - Backup Module Database Writer
 * @namespace MicroweberPackages\Backup
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
     * @var bool
     */
	public $deleteOldCssFiles = false;

	/**
	 * The content from backup file
	 * @var string
	 */
	public $content;

    public $logger;

	public function setContent($content)
	{
		$this->content = $content;
	}

	public function setOverwriteById($overwrite) {
		$this->overwriteById = $overwrite;
	}

	public function setDeleteOldContent($delete) {
	    $this->deleteOldContent = $delete;
    }

    public function setDeleteOldCssFiles($delete) {
	    $this->deleteOldCssFiles = $delete;
    }

    public function setLogger($logger) {
        $this->logger = $logger;
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

		if ($this->overwriteById) {
            if (isset($item['price'])) {
                $itemIdDatabase = DatabaseSave::saveProduct($item);
                $this->logger->setLogInfo('Saving product.. item id: ' . $itemIdDatabase);

                return array('item'=>$item, 'itemIdDatabase'=>$itemIdDatabase);
            }
        }

		if ($this->overwriteById && isset($item['id'])) {

            $itemIdDatabase = DatabaseSave::save($item['save_to_table'], $item);
            $this->logger->setLogInfo('Saving in table "' . $item['save_to_table'] . '"  Item id: ' . $itemIdDatabase);

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
            $this->logger->setLogInfo('Update item ' . $this->_getItemFriendlyName($item) . ' in ' . $item['save_to_table']);
		} else {
            $this->logger->setLogInfo('Save item ' . $this->_getItemFriendlyName($item) . ' in ' . $item['save_to_table']);
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
        if ($this->overwriteById) {
            return true;
        }

		if ($savedItem) {
			$this->_fixRelations($savedItem);
			$this->_fixParentRelationship($savedItem);
		}

	}

	/**
	 * Run database writer.
	 * @return string[]
	 */
	public function runWriter()
	{
        $this->logger->clearLog();
        $this->_deleteOldCssFiles();
        $this->_deleteOldContent();

        if (isset($this->content->__table_structures)) {
            $this->logger->setLogInfo('Building database tables');

            app()->database_manager->build_tables($this->content->__table_structures);
        }

		foreach ($this->content as $table=>$items) {

            if (!\Schema::hasTable($table)) {
                continue;
            }

            $this->logger->setLogInfo('Importing in table: ' . $table);

			if (!empty($items)) {
				foreach($items as $item) {
					$item['save_to_table'] = $table;
					$this->_saveItem($item);
				}
			}
		}

		$this->_finishUp('runWriterBottom');

	}

	public function runWriterWithBatch()
	{
        $currentStep = SessionStepper::currentStep();
        $totalSteps = SessionStepper::totalSteps();

        if ($currentStep == 1) {
            // Clear old log file
            $this->logger->clearLog();
            $this->_deleteOldContent();
        }

		$this->logger->setLogInfo('Importing database batch: ' .$currentStep . '/' . $totalSteps);

		if (empty($this->content)) {
			$this->_finishUp('runWriterWithBatchNothingToImport');
			return array("success"=>"Nothing to import.");
		}

		if (isset($this->content->__table_structures)) {
		    app()->database_manager->build_tables($this->content->__table_structures);
        }

		// All db tables
        $topItemsForSave = array();
        $otherItemsForSave = array();
		foreach ($this->content as $table=>$items) {

            if (!\Schema::hasTable($table)) {
                continue;
            }

			if (!empty($items)) {
				foreach($items as $item) {
					$item['save_to_table'] = $table;
                    if (isset($item['content_type']) && $item['content_type'] == 'page') {
                        $topItemsForSave[] = $item;
                    } else {
                        $otherItemsForSave[] = $item;
                    }
				}
			}
			$this->logger->setLogInfo('Prepare content for table: ' . $table);
		}

        $itemsForSave = array_merge($topItemsForSave, $otherItemsForSave);

		if (!empty($itemsForSave)) {

			$totalItemsForSave = sizeof($itemsForSave);
            $totalItemsForBatch = (int) ceil($totalItemsForSave / $totalSteps);

			if ($totalItemsForBatch > 0) {
				$itemsBatch = array_chunk($itemsForSave, $totalItemsForBatch);
			} else {
                $itemsBatch = [];
				$itemsBatch[] = $itemsForSave;
			}

            $selectBatch = ($currentStep - 1);

            if (!isset($itemsBatch[$selectBatch])) {
                SessionStepper::finish();
            }

            if (SessionStepper::isFinished()) {
                $this->logger->setLogInfo('No items in batch for current step.');
                $this->_finishUp();
                return array("success"=>"Done! All steps are finished.");
            }

			$success = array();
			foreach($itemsBatch[$selectBatch] as $item) {
                try {
                    $success[] = $this->_saveItem($item);
                    $this->logger->setLogInfo('Save content to table: ' . $item['save_to_table']);
                } catch (\Exception $e) {
                    $this->logger->setLogInfo('Error when save in table: ' . $item['save_to_table']);
                    $this->logger->setLogInfo($e->getMessage());
                }
			}

            return $success;
		}

	}

	public function getImportLog() {

        $log = array();
        $log['current_step'] = SessionStepper::currentStep();
        $log['total_steps'] = SessionStepper::totalSteps();
        $log['precentage'] = SessionStepper::percentage();
        $log['session_id'] = SessionStepper::$sessionId;

        $log['data'] = false;

        if (SessionStepper::isFirstStep()) {
            $log['started'] = true;
        }

        if (SessionStepper::isFinished()) {
            $log['done'] = true;
            // Finish up
            $this->_finishUp('getImportLog');
            // Clear log file
            $this->logger->clearLog();
        }

        return $log;
	}

	private function _deleteOldCssFiles()
    {
        // Delete old css files
        if ($this->deleteOldCssFiles) {
            $currentTemplate = get_option('current_template','template');
            if (!empty($currentTemplate)) {
                $deleteFolder = userfiles_path() . 'css' . DS . $currentTemplate;
                if (is_dir($deleteFolder)) {
                    rmdir_recursive($deleteFolder, false);
                }
            }
        }
    }

	private function _deleteOldContent()
    {
        // Delete old content
        if ($this->deleteOldContent) {
            if (!empty($this->content)) {
                foreach ($this->content as $table => $items) {
                    if ($table == 'users' || $table == 'users_oauth' || $table == 'system_licenses') {
                        continue;
                    }
                    if (\Schema::hasTable($table)) {
                        $this->logger->setLogInfo('Truncate table: ' . $table);
                        try {
                            \DB::table($table)->truncate();
                        } catch (\Exception $e) {
                            $this->logger->setLogInfo('Can\'t truncate table: ' . $table);
                        }
                    }
                }
            }
        }
    }

	/**
	 * Clear all cache on framework
	 */
	private function _finishUp($callFrom = '') {

        $this->logger->setLogInfo('Call from: ' . $callFrom);

		if (function_exists('mw_post_update')) {
			mw_post_update();
		}

        $this->logger->setLogInfo('Cleaning up system cache');

		mw()->cache_manager->clear();

        $zipReader = new ZipReader();
        $zipReader->clearCache();

        $this->logger->setLogInfo('Done!');
	}
}
