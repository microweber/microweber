<?php

namespace Modules\Backup;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use Modules\Backup\Formats\JsonBackup;
use Modules\Backup\Formats\ZipBatchBackup;
use Modules\Backup\Loggers\BackupLogger;
use Modules\Backup\Traits\BackupFileNameGetSet;
use Modules\Backup\Traits\BackupGetSet;

class Backup
{
    use BackupGetSet;
    use BackupFileNameGetSet;

    public $type = 'json';
    public $backupData = ['categoryIds' => [], 'contentIds' => [], 'tables' => []];
    public $backupAllData = false;
    public $logger;
    public $sessionId;

    public function __construct() {
        $this->logger = new BackupLogger();
    }

    /**
     * Set export file format
     * @param string $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * Set wich data want to export
     * @param array $data
     */
    public function setBackupData($dataType, $dataIds): void
    {
        $this->backupData[$dataType] = $dataIds;
    }

    /**
     * Set export full
     * @param string $type
     */
    public function setBackupAllData($all = true): void
    {
        $this->backupAllData = $all;
    }

    /**
     * Set logger
     * @param class $logger
     */
    public function setLogger($logger): void
    {
        $this->logger = $logger;
    }

    public function setSessionId($sessionId): void
    {
        $this->sessionId = $sessionId;
    }


    public function start()
    {
        MultilanguageHelpers::setMultilanguageEnabled(false);
        Config::set('microweber.disable_model_cache', 1);

        if (!$this->sessionId) {
            throw new \Exception('SessionId is missing.');
         }

        SessionStepper::setSessionId($this->sessionId);

        if (!SessionStepper::isFinished()) {
            SessionStepper::nextStep();
        }

        $isFirstStep = SessionStepper::isFirstStep();

        $readyDataCacheId = 'readyData' . $this->sessionId;
        $readyDataCacheGroup = 'mwBackup';

        $exportCacheLocation = backup_cache_location();

        if ($isFirstStep) {

            $data = $this->_getReadyData();
            if (empty($data)) {
                return array("error" => "Empty content data.");
            }
            cache_save($data, $readyDataCacheId,$readyDataCacheGroup);
        } else {
            $data = cache_get($readyDataCacheId, $readyDataCacheGroup);
        }

        if (empty($data)) {
            return array("error" => "Session backup is broken. Data is not cached.");
        }

        $backupWithZip = ($this->backupAllData ? true : false);
        $backupMediaUserFiles = ($this->backupAllData ? true : false);

     /*   if (array_key_exists('media', $data)) {
            $backupMediaUserFiles = true;
            $this->backupMedia = true;
        }*/

        if (!empty($this->backupData['contentIds'])) {
            $this->backupMedia = true;
			$backupMediaUserFiles = true;
			$backupWithZip = true;
        }

        $exportedDataCacheId = 'exportedData' . $this->sessionId;

        if ($isFirstStep) {
            $backup = new JsonBackup($data);
            $backup->setType($this->type);
            $backup = $backup->start();
            cache_save($backup, $exportedDataCacheId,$readyDataCacheGroup);
        } else {
            $backup = cache_get($exportedDataCacheId, $readyDataCacheGroup);
        }

        if (empty($backup)) {
            return array("error" => "Backup session is broken. Exported data is not cached.");
        }

        if (isset($backup['files']) && count($backup['files']) > 1) {
            $backupWithZip = true;
        }

        if ($this->backupModules) {
            $backupWithZip = true;
        }

        if ($this->backupTemplates) {
            $backupWithZip = true;
        }
        if ($this->backupWithZip) {
            $backupWithZip = true;
        }

        if ($this->backupOnlyTemplate) {
            $backupWithZip = true;
            unset($backup['files']);
        }
        if($backupMediaUserFiles){
            $backupWithZip = true;
        }

        if ($backupWithZip) {

            // Make Zip
            $zipExport = new ZipBatchBackup();
            $zipExport->setLogger($this->logger);

            // Move files to zip temp
            if (isset($backup['files'])) {
                foreach ($backup['files'] as $file) {

                    $newFilePath = $exportCacheLocation . $file['filename'];
                    if ($isFirstStep) {
                        rename($file['filepath'], $newFilePath);
                    }

                    // Add exported files
                    $zipExport->addFile(array('filepath' => $newFilePath, 'filename' => $file['filename']));
                }
            }

            if ($backupMediaUserFiles) {
                $zipExport->setBackupMedia(true);
            }

            if ($this->backupModules) {
            //    $zipExport->setBackupModules($this->backupModules);
            }

            if ($this->backupTemplates) {
             //   $zipExport->setBackupTemplates($this->backupTemplates);
            }

            if ($this->backupOnlyTemplate) {
                $zipExport->setBackupOnlyTemplate($this->backupOnlyTemplate);
            }

            if ($this->backupFileName) {
                $zipExport->setBackupFileName($this->backupFileName);
            }

            $zipExportReady = $zipExport->start();

            if (isset($zipExportReady['download']) && !empty($zipExportReady['download'])) {

                // Delete unused ziped files
                array_map('unlink', glob("$exportCacheLocation/*.*"));
                rmdir($exportCacheLocation);

                return array(
                    'success' => 'Items are exported',
                    'export_type' => $this->type,
                    'filepath' => $zipExportReady['filepath'],
                    'filename' => $zipExportReady['filename'],
                    'downloadUrl' => $zipExportReady['download'],
                    'data' => $zipExportReady
                );
            } else {
                return $zipExportReady;
            }
        }

        if (isset($backup['files'])) {

            $exportSingleFile = false;

            if (count($backup['files']) == 1) {
                $exportSingleFile = true;
            }

            if ($exportSingleFile && isset($backup['files']) && !empty($backup['files'])) {
                $response = $backup['files'][0];
                $response['success'] = 'Items are exported';
                $response['export_type'] = $this->type;

                return $response;
            } else {
                return $backup;
            }

        }

    }

    private function _getbackupDataHash()
    {
        return md5(json_encode($this->backupData));
    }

    private function _getReadyData()
    {
        $backupTables = new BackupTables();

        $tablesStructures = array();

        foreach ($this->_getTablesForBackup() as $table) {

            $this->logger->setLogInfo('Backup table: <b>' . $table . '</b>');

            $tableFields = app()->database_manager->get_fields($table, false, true);

            if ($tableFields) {
                $tableFieldsStructure = array();
                foreach ($tableFields as $tableField) {
                    if (isset($tableField['name'])) {
                        $tableFieldsStructure[$tableField['name']] = $tableField['type'];
                    }
                }
                $tablesStructures[$table] = $tableFieldsStructure;
            }

            if ($this->backupAllData) {
                $tableContent = $this->_getTableContent($table);
                if (!empty($tableContent)) {
                    $backupTables->addItemsToTable($table, $tableContent);
                }
                continue;
            }

            $ids = array();

            if ($table == 'categories') {

                if (!empty($this->backupData['categoryIds'])) {
                    $ids = $this->backupData['categoryIds'];
                }

                // Get all posts for this category
                $contentForCategories = get_content(array(
                    "categories" => $ids,
                    "no_limit" => true,
                    "do_not_replace_site_url" => 1
                ));

                if (is_array($contentForCategories) && !empty($contentForCategories)) {
                    $backupTables->addItemsToTable('content', $contentForCategories);
                }
            }

            if ($table == 'content') {
                if (!empty($this->backupData['contentIds'])) {
                    $ids = $this->backupData['contentIds'];
                }
            }

            $tableContent = $this->_getTableContent($table, $ids);
            if (!empty($tableContent)) {

                $backupTables->addItemsToTable($table, $tableContent);

                $relations = array();
                foreach ($tableContent as $content) {
                    if (isset($content['rel_type']) && isset($content['rel_id'])) {
                        $relations[$content['rel_type']][$content['rel_id']] = $content['rel_id'];
                    }
                }

                if (!empty($relations)) {

                    $this->logger->setLogInfo('Get relations from table: <b>' . $table . '</b>');

                    foreach ($relations as $relationTable => $relationIds) {

                        $this->logger->setLogInfo('Get data from relations table: <b>' . $relationTable . '</b>');

                        $relationTableContent = $this->_getTableContent($relationTable, $relationIds);

                        $backupTables->addItemsToTable($relationTable, $relationTableContent);
                    }
                }

            }
        }

        $exportTablesReady = $backupTables->getAllTableItems();
        $exportTablesReady['__table_structures'] = $tablesStructures;

        // Show only requried content ids
        if (isset($exportTablesReady['content'])) {
            $contentTableData = [];
            foreach ($exportTablesReady['content'] as $tableData) {
                if (in_array($tableData['id'], $this->backupData['contentIds'])) {
                    $contentTableData[] = $tableData;
                } else {
                    $contentTableData = $this->_getTableContent('content');
                }
            }
            $exportTablesReady['content'] = $contentTableData;
        }

        return $exportTablesReady;
    }

    private function _getTableContent($table, $ids = array())
    {
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

        $dbGetQuery=DB::table($table)
            ->select('*');
        if ($table == 'media') {
            $dbGetQuery->where('media_type', '!=', 'media_tn_temp');
//            $exportFilter['media_type_without_media_tn_temp'] = function ($query_filter) {
//                $query_filter->where('media_type', '!=', 'media_tn_temp');
//
//                return $query_filter;
//            };

        }
     //   $dbGet = db_get($table, $exportFilter);

        $tableContent = [];

        $col_exist = Schema::hasColumn($table, 'id');
        if ($col_exist) {
            $dbGetQuery->orderBy('id')->chunk(1000, function ($chunkData) use (&$tableContent) {
                foreach ($chunkData as $item) {
                    $tableContent[] = (array)$item;
                }
            });
        } else {
            $chunkData = $dbGetQuery->get();
            foreach ($chunkData as $item) {
                $tableContent[] = (array)$item;
            }
        }





      //  $dbGet = $dbGetQuery->toArray();
        return $tableContent;
    }

    private function _skipTables()
    {
        $this->skipTables[] = '__table_structures';
        $this->skipTables[] = 'modules';
        $this->skipTables[] = 'elements';
        $this->skipTables[] = 'users';
        $this->skipTables[] = 'login_attempts';
        $this->skipTables[] = 'log';
        $this->skipTables[] = 'notifications';
        $this->skipTables[] = 'countries';
        $this->skipTables[] = 'content_revisions_history';
        $this->skipTables[] = 'content_fields_drafts';
        $this->skipTables[] = 'module_templates';
        $this->skipTables[] = 'stats_users_online';
        $this->skipTables[] = 'stats_browser_agents';
        $this->skipTables[] = 'stats_referrers_paths';
        $this->skipTables[] = 'stats_referrers_domains';
        $this->skipTables[] = 'stats_referrers';
        $this->skipTables[] = 'stats_visits_log';
        $this->skipTables[] = 'stats_urls';
        $this->skipTables[] = 'stats_sessions';
        $this->skipTables[] = 'stats_geoip';
        $this->skipTables[] = 'system_licenses';
        $this->skipTables[] = 'users_oauth';
        $this->skipTables[] = 'sessions';
        $this->skipTables[] = 'global';
        $this->skipTables[] = 'migrations';
        $this->skipTables[] = 'translation_keys';
        $this->skipTables[] = 'translation_texts';
        $this->skipTables[] = 'media_thumbnails';
        $this->skipTables[] = 'personal_access_tokens';
        $this->skipTables[] = 'password_resets';
        $this->skipTables[] = 'stats_events';

        return $this->skipTables;
    }

    private function _prepareSkipTables()
    {

        if (!$this->allowSkipTables) {
            return [];
        }

        $skipTables = $this->_skipTables();

        // Add table categories if we have category ids
        if (!empty($this->backupData['categoryIds'])) {
            if (!in_array('categories', $this->backupData['tables'])) {
                $this->backupData['tables'][] = 'categories';
            }
        }

        // Add table categories if we have content ids
        if (!empty($this->backupData['contentIds'])) {
            if (!in_array('content', $this->backupData['tables'])) {
                $this->backupData['tables'][] = 'content';
                $this->backupData['tables'][] = 'categories';
                $this->backupData['tables'][] = 'categories_items';
                $this->backupData['tables'][] = 'content_data';
                $this->backupData['tables'][] = 'content_fields';
                $this->backupData['tables'][] = 'content_related';
                $this->backupData['tables'][] = 'custom_fields';
                $this->backupData['tables'][] = 'custom_fields_values';
                $this->backupData['tables'][] = 'elements';
                $this->backupData['tables'][] = 'media';
                $this->backupData['tables'][] = 'menus';
                $this->backupData['tables'][] = 'testimonials';
                $this->backupData['tables'][] = 'tagging_tagged';
                $this->backupData['tables'][] = 'tagging_tags';
                $this->backupData['tables'][] = 'tagging_tag_groups';
                $this->backupData['tables'][] = 'options';
            }
        }

        if (!empty($this->backupData['tables'])) {
            if (in_array('users', $this->backupData['tables'])) {
                $keyOfSkipTable = array_search('users', $skipTables);
                if ($keyOfSkipTable) {
                    unset($skipTables[$keyOfSkipTable]);
                }
            }
        }

        return $skipTables;
    }

    private function _getTablesForBackup()
    {
        $skipTables = $this->_prepareSkipTables();

        $tablesList = mw()->database_manager->get_tables_list(true);
        $tablePrefix = mw()->database_manager->get_prefix();

        $readyTableList = array();
        foreach ($tablesList as $tableName) {

            if ($tablePrefix) {
                $tableName = str_replace_first($tablePrefix, '', $tableName);
            }

            // Skip tables
            if (!empty($skipTables)) {
                if (in_array($tableName, $skipTables)) {
                    continue;
                }
            }

            if (!empty($this->backupData) && $this->backupAllData == false) {
                if (isset($this->backupData['tables'])) {
                    if (!in_array($tableName, $this->backupData['tables'])) {
                        continue;
                    }
                }
            }

            $readyTableList[] = $tableName;
        }

        return $readyTableList;
    }


}
