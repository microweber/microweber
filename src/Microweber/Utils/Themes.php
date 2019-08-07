<?php
/**
 * Author: bobi
 * Upload themes from zip
 * @namespace Microweber\Utils
 * 
 */

namespace Microweber\Utils;

api_expose_admin('Microweber\Utils\Themes\upload');

if (defined('INI_SYSTEM_CHECK_DISABLED') == false) {
	define('INI_SYSTEM_CHECK_DISABLED', ini_get('disable_functions'));
}

class Themes
{
	/**
	 * Upload zip file
	 * @param get params $query
	 * @return string[]
	 */
	public function upload($query)
	{
		only_admin_access();
		
		$overwrite = false;
		if (isset($query['overwrite']) && $query['overwrite'] == 1) { 
			$overwrite = true;
		}
		
		if (! isset($query['src'])) {
			return array(
				'error' => 'You have not provided src to the file.'
			);
		}
		
		$checkFile = url2dir(trim($query['src']));
		if (!is_file($checkFile)) {
			return array(
				'error' => 'Uploaded file is not found!'
			);
		}
			
		$templatesPath = $this->_getTemplatesPath();
		
		// Make cache dir
		$cacheTemplateDir = $templatesPath . md5(time()). '/';
		mkdir($cacheTemplateDir);
		
		if (!copy($checkFile, $cacheTemplateDir . 'uploaded-file.zip')) {
			
			// Remove cached dir
			rmdir_recursive($cacheTemplateDir, false);
			
			return array(
				'error' => 'Error moving uploaded file!'
			);
		}
				
		// Unzip uploaded files
		$unzip = new \Microweber\Utils\Unzip();
		$unzip->extract($cacheTemplateDir . 'uploaded-file.zip', $cacheTemplateDir);  
		
		// Check config file
		if (!is_file($cacheTemplateDir . "config.php") || !is_file($cacheTemplateDir . "composer.json")) {
			
			// Remove cached dir
			rmdir_recursive($cacheTemplateDir, false);
			
			return array(
				'error' => "config.php or composer.json is not found in template."
			);
		}
		
		// include($cacheTemplateDir . 'config.php');
		$composerThemeJson = json_decode(file_get_contents($cacheTemplateDir . "composer.json"), true);
		
		if (!isset($composerThemeJson['target-dir'])) {
			
			// Remove cached dir
			rmdir_recursive($cacheTemplateDir, false);
			
			return array(
				'error' => "Target dir not found in composer.json."
			);
		}
		
		// Remove uploaded file
		@unlink($cacheTemplateDir . 'uploaded-file.zip');
		
		if ($overwrite) {
			// Delete old folder
			rmdir_recursive($templatesPath .'/'. $composerThemeJson['target-dir'], false);
		}
		
		// Rename cache folder name to theme name
		$renameFolder = @rename($cacheTemplateDir, $templatesPath .'/'. $composerThemeJson['target-dir']);
		
		if (!$renameFolder) {
			
			// Remove cached dir
			rmdir_recursive($cacheTemplateDir, false);
			
			return array(
				'success' => "Template allready exists!"
			);
		}
		
		return array(
			'success' => "Template was uploaded success!"
		);
	}
	
	/**
	 * Get template folder
	 * @return string
	 */
	protected function _getTemplatesPath() {
		
		$templatesPath = userfiles_path() . 'templates';
		$templatesPath = normalize_path($templatesPath);
		
		return $templatesPath;
	}
	
}