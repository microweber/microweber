<?php
use Microweber\Utils\Import;
use Microweber\Utils\ContentExport;

api_expose('content_export_start');
function content_export_start($data)
{
	only_admin_access();
	
	$export = new ContentExport($data);
	$export->setExportFormatType('json');
	
	return $export->start();
	
}

api_expose('content_export_download');
function content_export_download($data)
{
	if (!is_admin()) {
		mw_error('must be admin');
	}

	$export = new ContentExport();
	$exportLocation = $export->getExportLocation();
	
	$exportPath = $exportLocation . $data['filename'];
	$exportPath = str_replace('..', '', $exportPath);
	
	if (!is_file($exportPath)) {
		return array('error' => 'You have not provided a existing filename to download.');
		
		die();
	}
	
	// Check if the file exist.
	if (file_exists($exportPath)) {
		
		// Add headers
		header('Cache-Control: public');
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename=' . basename($exportPath));
		header('Content-Length: ' . filesize($exportPath));
		
		// Read file
		$import = new Import();
		$import->readfile_chunked($exportPath);
		
	} else {
		die('File does not exist');
	}
}