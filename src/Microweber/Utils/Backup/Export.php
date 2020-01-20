<?php
namespace Microweber\Utils\Backup;

use Microweber\Utils\Backup\Exporters\JsonExport;
use Microweber\Utils\Backup\Exporters\CsvExport;
use Microweber\Utils\Backup\Exporters\XmlExport;
use Microweber\Utils\Backup\Exporters\ZipExport;
use Microweber\Utils\Backup\Loggers\BackupExportLogger;
use Microweber\App\Providers\Illuminate\Support\Facades\Cache;
use Microweber\Utils\Backup\Exporters\XlsxExport;

class Export
{
	public $skipTables;
	public $exportData;
	public $type = 'json';
	public $exportAllData = false;
	public $includeMedia = false;
	
	public function setType($type)
	{
		$this->type = $type;
	}
	
	public function setExportData($data) {
		$this->exportData = $data;
	}
	
	public function setExportAllData($exportAllData) {
		$this->exportAllData = $exportAllData;
	}
	
	public function setIncludeMedia($includeMedia) {
		$this->includeMedia = $includeMedia;
	}
	
	public function exportAsType($data)
	{
        $backupManager = new BackupManager();
        $exportCacheLocation = $backupManager->getBackupCacheLocation();

		$exportWithZip = false;
		$exportMediaUserFiles = false;

		if (array_key_exists('media', $data)) {
			$exportMediaUserFiles = true;
		}
		
		$export = $this->_getExporter($data);

		if (isset($export['files']) && count($export['files']) > 1) {
			$exportWithZip = true;
		}
		
		if ($exportWithZip || $exportMediaUserFiles) {

			// Make Zip
			$zipExport = new ZipExport();

            // Move files to zip temp
            if (isset($export['files'])) {
                foreach ($export['files'] as $file) {

                    $newFilePath = $exportCacheLocation  . $file['filename'];
                    rename($file['filepath'], $newFilePath);

                    // Add exported files
                    $zipExport->addFile(array('filepath'=>$newFilePath, 'filename'=>$file['filename']));
                }
            }

			if ($exportMediaUserFiles) {
				$zipExport->setExportMedia(true);
			}
			
			if ($this->includeMedia == false) {
				$zipExport->setExportMedia(false);
			}

			$zipExportReady = $zipExport->start();

			if (isset($zipExportReady['download']) && !empty($zipExportReady['download'])) {

                // Delete unused ziped files
                array_map('unlink', glob("$exportCacheLocation/*.*"));
                rmdir($exportCacheLocation);

				return array(
					'success' => 'Items are exported',
					'export_type' => $this->type,
					'data' => $zipExportReady
				);
			} else {
				return $zipExportReady;
			}
		}
		
		if (isset($export['files'])) {
		
			$exportSingleFile = false;
			
			if (count($export['files']) == 1) {
				$exportSingleFile = true;
			}
			
			if ($exportSingleFile && isset($export['files']) && !empty($export['files'])) {
				return array(
					'success' => 'Items are exported',
					'export_type' => $this->type,
					'data' => $export['files'][0]
				);
			} else {
				return $export;
			}
			
		}
		
	}

	public function start() {
		
		$readyData = $this->_getReadyDataCached();
		
		if (empty($readyData)) {
			return array("error"=>"Empty content data.");
		}
		
		return $this->exportAsType($readyData);
	}
	
	private function _getExportDataHash() {
		return md5(json_encode($this->exportData));
	}
	
	private function _getReadyDataCached() {
		
		return $this->_getReadyData();
		
		/* $exportDataHash = $this->_getExportDataHash();
		
		$zipExport = new ZipExport();
		$currentStep = $zipExport->getCurrentStep();
		
		if ($currentStep == 0) {
			// This is frist step
			Cache::forget($exportDataHash);
			return Cache::rememberForever($exportDataHash, function () {
				return $this->_getReadyData();
			});
		} else {
			BackupExportLogger::setLogInfo('Start exporting selected data...');
			// This is for the next steps from wizard
			return Cache::get($exportDataHash);
		} */
	}
	
	private function _getReadyData() {
		
		$exportTables = new ExportTables();

		foreach($this->_getTablesForExport() as $table) {			
			BackupExportLogger::setLogInfo('Exporting table: <b>' . $table. '</b>');
			
			$ids = array();
			
			if ($table == 'categories') {
				
				if (!empty($this->exportData['categoryIds'])) {
					$ids = $this->exportData['categoryIds'];
				}
				
				// Get all posts for this category
				$contentForCategories = get_content(array(
					"categories"=>$ids,
					"no_limit"=>true,
                    "do_not_replace_site_url"=>1
				));

				if (is_array($contentForCategories) && !empty($contentForCategories)) {
					$exportTables->addItemsToTable('content', $contentForCategories);
				}
			}
			
			if ($table == 'content') {
				if (!empty($this->exportData['contentIds'])) {
					$ids = $this->exportData['contentIds'];
				}
			}
			
			$tableContent = $this->_getTableContent($table, $ids);
			
			if (!empty($tableContent)) {
				
				$exportTables->addItemsToTable($table, $tableContent);
				
				$relations = array();
				foreach($tableContent as $content) {
					if (isset($content['rel_type']) && isset($content['rel_id'])) {
						$relations[$content['rel_type']][$content['rel_id']] = $content['rel_id'];
					}
				}
				
				if (!empty($relations)) {
					
					BackupExportLogger::setLogInfo('Get relations from table: <b>' . $table . '</b>');
					
					foreach($relations as $relationTable=>$relationIds) {
						
						BackupExportLogger::setLogInfo('Get data from relations table: <b>' . $relationTable. '</b>');
						
						$relationTableContent = $this->_getTableContent($relationTable, $relationIds);
						
						$exportTables->addItemsToTable($relationTable, $relationTableContent);
					}
				}
				
			}
		}
		
		return $exportTables->getAllTableItems();
	}
	
	private function _getTableContent($table, $ids = array()) {
		
		$exportFilter = array();
		$exportFilter['no_limit'] = 1;
		$exportFilter['do_not_replace_site_url'] = 1;
		
		if (!empty($ids)) {
			$exportFilter['ids'] = implode(',', $ids);
		}
		
		$tableExists = mw()->database_manager->table_exists($table);
		if (!$tableExists) {
			return;
		}
		
		$dbGet = db_get($table, $exportFilter);
		
		return $dbGet;
	}
	
	private function _skipTables() {
		
		$this->skipTables[] = 'modules';
		$this->skipTables[] = 'elements';
		$this->skipTables[] = 'users';
		$this->skipTables[] = 'log';
		$this->skipTables[] = 'notifications';
		$this->skipTables[] = 'content_revisions_history';
		$this->skipTables[] = 'module_templates';
		$this->skipTables[] = 'stats_users_online';
		$this->skipTables[] = 'stats_browser_agents';
		$this->skipTables[] = 'stats_referrers_paths';
		$this->skipTables[] = 'stats_referrers_domains';
		$this->skipTables[] = 'stats_referrers';
		$this->skipTables[] = 'stats_visits_log';
		$this->skipTables[] = 'stats_urls';
		$this->skipTables[] = 'system_licenses';
		$this->skipTables[] = 'users_oauth';
		$this->skipTables[] = 'sessions';
		$this->skipTables[] = 'global';
		
		return $this->skipTables;
	}
	
	private function _prepareSkipTables() {
		
		$skipTables = $this->_skipTables();
		
		// Add table categories if we have category ids
		if (!empty($this->exportData['categoryIds'])) {
			if (!in_array('categories',$this->exportData['tables'])) {
				$this->exportData['tables'][] = 'categories';
			}
		}
		
		// Add table categories if we have content ids
		if (!empty($this->exportData['contentIds'])) {
			if (!in_array('content', $this->exportData['tables'])) {
				$this->exportData['tables'][] = 'content';
			}
		}
		
		if (!empty($this->exportData['tables'])) {
			if (in_array('users', $this->exportData['tables'])) {
				$keyOfSkipTable = array_search('users', $skipTables);
				if ($keyOfSkipTable) {
					unset($skipTables[$keyOfSkipTable]);
				}
			}
		}
		
		return $skipTables;
	}
	
	private function _getTablesForExport() {
		
		$skipTables = $this->_prepareSkipTables();
		
		$tablesList = mw()->database_manager->get_tables_list();
		$tablePrefix = mw()->database_manager->get_prefix();
		
		$readyTableList = array();
		foreach ($tablesList as $tableName) {
			
			if ($tablePrefix) {
				$tableName = str_replace_first($tablePrefix, '', $tableName);
			}
			
			if (in_array($tableName, $skipTables)) {
				continue;
			}
			
			if (!empty($this->exportData) && $this->exportAllData == false) {
				if (isset($this->exportData['tables'])) {
					if (!in_array($tableName, $this->exportData['tables'])) {
						continue;
					}
				}
			}
			
			$readyTableList[] = $tableName;
			
		}
		
		return $readyTableList;
	}
	
	private function _getExporter($data) {
		
		$export = false;
		
		switch ($this->type) {
			case 'json':
				$export = new JsonExport($data);
				break;
				
			case 'csv':
				$export = new CsvExport($data);
				break;
				
			case 'xml':
				$export = new XmlExport($data);
				break;
				
			case 'xlsx':
				$export = new XlsxExport($data);
				break;
				
			/* case 'zip':
				$export = new ZipExport($data);
				break; */
				
			default:
				throw new \Exception('Format not supported for exporting.');
		}
		
		$export->setType($this->type);
		
		return $export->start();
	}
	
}