<?php

namespace MicroweberPackages\Backup\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Export\Formats\Helpers\SpreadsheetHelper;

class LanguageController
{
	public function upload(Request $request) {

        $src = $request->get('src');
        $namespace = $request->get('namespace', false);
        $language = $request->get('language', false);

		$localFielapth = url2dir($src);

		$rows = SpreadsheetHelper::newSpreadsheet($localFielapth)->getRows();

		// Unset first rows
		unset($rows[0]);

		$readyData = array();
		foreach($rows as $row) {
			$readyData[$row[0]] = $row[1];
		}

		if ($namespace) {
			$readyData['___namespace'] = $namespace;
		}

		if ($language) {
			$readyData['___lang'] = $language;
		}

		$save = mw()->lang_helper->save_language_file_content($readyData);

		return $save;
	}

	public function export(Request $request) {

	    $namespace = $request->get('namespace');
        $language = $request->get('language', false);

		$filename = $namespace;
		$filename = str_replace('/', '-', $filename);
		$filename = str_replace('\\', '-', $filename);

		if (!empty($language)) {
			$filename = $language .'-' . $filename;
		}

		$exportFilename = userfiles_path() .'/'. $filename . '.xlsx';

		if ($namespace == 'global') {
			$content = mw()->lang_helper->get_language_file_content();
		} else {
			$content = mw()->lang_helper->get_language_file_content($namespace);
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
