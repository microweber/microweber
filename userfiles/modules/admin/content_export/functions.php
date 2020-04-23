<?php

api_expose('content_export_start');
function content_export_start($data)
{
	only_admin_access();

	$export = new \MicroweberPackages\Utils\Misc\ContentExport($data);
	$export->setExportFormatType('json');

	return $export->start();

}

api_expose('content_export_download');
function content_export_download($data)
{
    only_admin_access();

	$export = new \MicroweberPackages\Utils\Misc\ContentExport();

	return $export->download($data['filename']);
}


api_expose('content_import');
function content_import($data)
{
	if (!is_admin()) {
		mw_error('Must be admin');
	}

	/*$import = new ContentImport();

	return $import->start();*/
}
