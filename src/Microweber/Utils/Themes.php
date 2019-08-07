<?php
namespace Microweber\Utils;

api_expose_admin('Microweber\Utils\Themes\upload');

if (defined('INI_SYSTEM_CHECK_DISABLED') == false) {
	define('INI_SYSTEM_CHECK_DISABLED', ini_get('disable_functions'));
}

class Themes
{
	protected function _getTemplatesPath() {
		
		$templatesPath = userfiles_path() . 'templates';
		$templatesPath = normalize_path($templatesPath);
		
		return $templatesPath;
	}
	
	public function upload($query)
	{
		only_admin_access();
		
		if (! isset($query['src'])) {
			return array(
				'error' => 'You have not provided src to the file.'
			);
		}
		
		$checkFile = url2dir(trim($query['src']));
		if (is_file($checkFile)) {
			
			$templatesPath = $this->_getTemplatesPath();
			
			// Make cache dir
			$cacheTemplateDir = $templatesPath . md5(time()). '/';
			mkdir($cacheTemplateDir);
			
			if (copy($checkFile, $cacheTemplateDir . 'uploaded-file.zip')) {
				
				// Unzip uploaded files
				$unzip = new \Microweber\Utils\Unzip();
				$unzip->extract($cacheTemplateDir . 'uploaded-file.zip', $cacheTemplateDir); 
				
				
				
				
				
				return array(
					'success' => "Theme was uploaded success!"
				);
				
			} else {
				return array(
					'error' => 'Error moving uploaded file!'
				);
			}
			
		} else {
			return array(
				'error' => 'Uploaded file is not found!'
			);
		}
	}
}