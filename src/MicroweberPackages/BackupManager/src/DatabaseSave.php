<?php
namespace MicroweberPackages\BackupManager;

/**
 * Microweber - Backup Module Database Save
 *
 * @namespace MicroweberPackages\BackupManager
 * @package DatabaseWriter
 * @author Bozhidar Slaveykov
 */
class DatabaseSave
{

	public static function save($table, $tableData)
	{
		$tableData['skip_cache'] = true;
		$tableData['allow_html'] = true;
		$tableData['allow_scripts'] = true;
		
		return db_save($table, $tableData);
	}
}