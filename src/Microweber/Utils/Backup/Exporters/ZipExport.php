<?php
namespace Microweber\Utils\Backup\Exporters;

class ZipExport extends DefaultExport
{
	public function start() {
		
		$json = new JsonExport($this->data);
		$getJson = $json->start();
		
		// Get zip filename
		$zipFilename = $this->_generateFilename();
		
		// Generate zip file
		$zip = new \Microweber\Utils\Zip($zipFilename['filepath']);
		$zip->setZipFile($zipFilename['filepath']);
		$zip->setComment("Microweber backup of the userfiles folder and db.
                \n The Microweber version at the time of backup was {MW_VERSION}
                \nCreated on " . date('l jS \of F Y h:i:s A'));
		
		// Add json file
		if ($getJson['filepath']) {
			$zip->addLargeFile($getJson['filepath'], 'mw_content.json', filectime($getJson['filepath']), 'Json Restore file');
		}
		
		// Add user media files
		$zip->addDirectoryContent(userfiles_path() . DIRECTORY_SEPARATOR . 'css', 'css', true);
		$zip->addDirectoryContent(userfiles_path() . DIRECTORY_SEPARATOR . 'media', 'media', true);
		
		return $zipFilename;
	}
}