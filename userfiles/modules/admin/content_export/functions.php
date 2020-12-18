<?php
use Microweber\Utils\ContentExport;
use Microweber\Utils\ContentImport;

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
	/* if (!is_admin()) {
		mw_error('Must be admin');
	} */

	$export = new ContentExport();
	
	return $export->download($data['filename']);
}


api_expose('content_import');
function content_import($data)
{
	if (!is_admin()) {
		mw_error('Must be admin');
	}
	
	$import = new ContentImport();
	
	return $import->start();
}