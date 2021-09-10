<?php

namespace MicroweberPackages\Backup;

use MicroweberPackages\Backup\Exporters\JsonExport;
use MicroweberPackages\Backup\Exporters\CsvExport;
use MicroweberPackages\Backup\Exporters\XmlExport;
use MicroweberPackages\Backup\Exporters\ZipExport;
use MicroweberPackages\Backup\Loggers\BackupExportLogger;
use Microweber\App\Providers\Illuminate\Support\Facades\Cache;
use MicroweberPackages\Backup\Exporters\XlsxExport;
use MicroweberPackages\Backup\Traits\ExportGetSet;

class Export
{
    use ExportGetSet;

    public $exportData;
    public $type = 'json';
    public $exportAllData = false;

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setExportData($data)
    {
        $this->exportData = $data;
    }

    public function setExportAllData($exportAllData)
    {
        $this->exportAllData = $exportAllData;
    }

    public function exportAsType($data)
    {
        $backupManager = new BackupManager();
        $exportCacheLocation = $backupManager->getBackupCacheLocation();

        $exportWithZip = false;
        $exportMediaUserFiles = false;

        if (array_key_exists('media', $data)) {
            $exportMediaUserFiles = true;
            $this->exportMedia = true;
        }

        if (!empty($this->exportData['contentIds'])) {
            $this->exportMedia = true;
        }

        $export = $this->_getExporter($data);

        if (isset($export['files']) && count($export['files']) > 1) {
            $exportWithZip = true;
        }

        if ($this->exportModules) {
            $exportWithZip = true;
        }

        if ($this->exportTemplates) {
            $exportWithZip = true;
        }

        if ($this->exportOnlyTemplate) {
            $exportWithZip = true;
            unset($export['files']);
        }

        if ($exportWithZip || $exportMediaUserFiles) {

            // Make Zip
            $zipExport = new ZipExport();

            // Move files to zip temp
            if (isset($export['files'])) {
                foreach ($export['files'] as $file) {

                    $newFilePath = $exportCacheLocation . $file['filename'];
                    rename($file['filepath'], $newFilePath);

                    // Add exported files
                    $zipExport->addFile(array('filepath' => $newFilePath, 'filename' => $file['filename']));
                }
            }

            if ($exportMediaUserFiles) {
                $zipExport->setExportMedia(true);
            }

            if ($this->exportMedia == false) {
                $zipExport->setExportMedia(false);
            }

            if ($this->exportModules) {
                $zipExport->setExportModules($this->exportModules);
            }

            if ($this->exportTemplates) {
                $zipExport->setExportTemplates($this->exportTemplates);
            }

            if ($this->exportOnlyTemplate) {
                $zipExport->setExportOnlyTemplate($this->exportOnlyTemplate);
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

    public function start()
    {

        $readyData = $this->_getReadyData();

        if (empty($readyData)) {
            return array("error" => "Empty content data.");
        }

        return $this->exportAsType($readyData);
    }

    private function _getExportDataHash()
    {
        return md5(json_encode($this->exportData));
    }

    private function _getReadyData()
    {

        $exportTables = new ExportTables();

        $tablesStructures = array();

        foreach ($this->_getTablesForExport() as $table) {

            BackupExportLogger::setLogInfo('Exporting table: <b>' . $table . '</b>');

            $tableFields = app()->database_manager->get_fields($table);
            if ($tableFields) {
                $tableFieldsStructure = array();
                foreach ($tableFields as $tableField) {
                    $tableFieldType = \DB::getSchemaBuilder()->getColumnType($table, $tableField);
                    $tableFieldsStructure[$tableField] = $tableFieldType;
                }
                $tablesStructures[$table] = $tableFieldsStructure;
            }

            if ($this->exportAllData) {
                $tableContent = $this->_getTableContent($table);
                if (!empty($tableContent)) {
                    $exportTables->addItemsToTable($table, $tableContent);
                }
                continue;
            }

            $ids = array();

            if ($table == 'categories') {

                if (!empty($this->exportData['categoryIds'])) {
                    $ids = $this->exportData['categoryIds'];
                }

                // Get all posts for this category
                $contentForCategories = get_content(array(
                    "categories" => $ids,
                    "no_limit" => true,
                    "do_not_replace_site_url" => 1
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
                foreach ($tableContent as $content) {
                    if (isset($content['rel_type']) && isset($content['rel_id'])) {
                        $relations[$content['rel_type']][$content['rel_id']] = $content['rel_id'];
                    }
                }

                if (!empty($relations)) {

                    BackupExportLogger::setLogInfo('Get relations from table: <b>' . $table . '</b>');

                    foreach ($relations as $relationTable => $relationIds) {

                        BackupExportLogger::setLogInfo('Get data from relations table: <b>' . $relationTable . '</b>');

                        $relationTableContent = $this->_getTableContent($relationTable, $relationIds);

                        $exportTables->addItemsToTable($relationTable, $relationTableContent);
                    }
                }

            }
        }

        $exportTablesReady = $exportTables->getAllTableItems();
        $exportTablesReady['__table_structures'] = $tablesStructures;

        // Show only requried content ids
        if (isset($exportTablesReady['content'])) {
            $contentTableData = [];
            foreach ($exportTablesReady['content'] as $tableData) {
                if (in_array($tableData['id'], $this->exportData['contentIds'])) {
                    $contentTableData[] = $tableData;
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


        if ($table == 'media') {

            $exportFilter['media_type_without_media_tn_temp'] = function ($query_filter) {
                $query_filter->where('media_type', '!=', 'media_tn_temp');

                return $query_filter;
            };

        }
        $dbGet = db_get($table, $exportFilter);

        return $dbGet;
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

        return $this->skipTables;
    }

    private function _prepareSkipTables()
    {

        $skipTables = $this->_skipTables();

        // Add table categories if we have category ids
        if (!empty($this->exportData['categoryIds'])) {
            if (!in_array('categories', $this->exportData['tables'])) {
                $this->exportData['tables'][] = 'categories';
            }
        }

        // Add table categories if we have content ids
        if (!empty($this->exportData['contentIds'])) {
            if (!in_array('content', $this->exportData['tables'])) {
                $this->exportData['tables'][] = 'content';
                $this->exportData['tables'][] = 'categories';
                $this->exportData['tables'][] = 'categories_items';
                $this->exportData['tables'][] = 'content_data';
                $this->exportData['tables'][] = 'content_fields';
                $this->exportData['tables'][] = 'content_related';
                $this->exportData['tables'][] = 'custom_fields';
                $this->exportData['tables'][] = 'custom_fields_values';
                $this->exportData['tables'][] = 'elements';
                $this->exportData['tables'][] = 'media';
                $this->exportData['tables'][] = 'menus';
                $this->exportData['tables'][] = 'testimonials';
                $this->exportData['tables'][] = 'tagging_tagged';
                $this->exportData['tables'][] = 'tagging_tags';
                $this->exportData['tables'][] = 'tagging_tag_groups';
                $this->exportData['tables'][] = 'options';
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

    private function _getTablesForExport()
    {

        $skipTables = $this->_prepareSkipTables();

        $tablesList = mw()->database_manager->get_tables_list(true);
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

    private function _getExporter($data)
    {

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
