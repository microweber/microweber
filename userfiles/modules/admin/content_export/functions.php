<?php
use Microweber\Utils\ContentExport;
use Microweber\Utils\ContentImport;

api_expose('content_export_start');
function content_export_start($data)
{
	has_access();
	
	$export = new ContentExport($data);
	$export->setExportFormatType('json');
	
	return $export->start();
	
}

api_expose('content_export_download');
function content_export_download($data)
{
	/* if (!has_access()) {
		mw_error('You dont have access to see this page');
	} */

	$export = new ContentExport();
	
	return $export->download($data['filename']);
}


api_expose('content_import');
function content_import($data)
{
	if (!has_access()) {
		mw_error('You dont have access to see this page');
	}
	
	$import = new ContentImport();
	
	return $import->start();
}