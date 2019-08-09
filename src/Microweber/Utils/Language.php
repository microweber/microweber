<?php

namespace Microweber\Utils;

use Microweber\Utils\Backup\Exporters\SpreadsheetHelper;

api_expose_admin('Microweber/Utils/Language/export');
api_expose_admin('Microweber/Utils/Language/upload');

class Language
{
	public function upload($params) {
		
		$localFielapth = url2dir($params['src']);
		
		$rows = SpreadsheetHelper::newSpreadsheet($localFielapth)->getRows();
	
		// Unset first rows
		unset($rows[0]);
		
		$readyData = array();
		foreach($rows as $row) {
			$readyData[$row[0]] = $row[1];
		}
		
		$save = mw()->lang_helper->save_language_file_content($readyData);
		
		return $save;
	}
	
	public function export($params) {
		
		$content = array();
		
		$filename = $params['namespace'];
		$filename = str_replace('/', false, $filename);
		$filename = str_replace('\\', false, $filename);
		$exportFilename = userfiles_path() .'/'. $filename . '.xlsx';
		
		if ($params['namespace'] == 'global') {
			$content = mw()->lang_helper->get_language_file_content();
		} else {
			$content = mw()->lang_helper->get_language_file_content($params['namespace']);
		}
		
		$rows = array();
		foreach($content as $keyCont=>$contValue) {
			$rows[] = array($keyCont, $contValue);
		}
		
		$spreadsheet = SpreadsheetHelper::newSpreadsheet();
		$spreadsheet->addRow(['Key', 'Value']);
		$spreadsheet->addRows($rows);
		$spreadsheet->save($exportFilename);
		
		echo dir2url($exportFilename);
		
	}
}