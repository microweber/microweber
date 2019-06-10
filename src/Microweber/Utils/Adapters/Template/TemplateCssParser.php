<?php

namespace Microweber\Utils\Adapters\Template;

class TemplateCssParser
{
    /** @var \Microweber\Application */
    public $app;

    public function __construct($app = null)
    {
        $this->app = $app;
    }

    public function getStylesheet($lessFilePath, $optionGroupName = false, $cache = true) {
    	
    	$themeFolderName = $this->app->template->folder_name();
    	
    	if (!$optionGroupName) {
    		$optionGroupName = $themeFolderName;
    	}
    	
    	$outputFileLocations = $this->_getOutputFileLocations($lessFilePath, $optionGroupName);
    	
    	if ($cache == false || !is_file($outputFileLocations['output']['file'])) {
    		return api_url('template/compile_css?path=' . $lessFilePath . '&option_group=' . $optionGroupName . '&template_folder=' . $themeFolderName);
    	}
    	
    	return $outputFileLocations['output']['fileUrl'];
    	
    }
    
    public function compile($options) {
    	
    	$compileFile = $this->_getOutputDir($options['path']);
		$extension = get_file_extension($compileFile);

		if ($extension == 'less') {
			return $this->compileLess($options);
		} else {
			return $this->compileSaas($options);
		}
		
    }
    
    public function compileSaas() {
    	
    }
    
    public function compileLess($params) {
    	
    	$lessFilePath = array_get($params, 'path', false);
    	$optionGroupName = array_get($params, 'option_group', false);
    	$templateFolder = array_get($params, 'template_folder', false);
		
    	$outputFileLocations = $this->_getOutputFileLocations($lessFilePath, $templateFolder);
    	
    	$parserOptions = array(
    		'sourceMap' => true,
    		'compress' => true,
    		'sourceMapWriteTo' => $outputFileLocations['output']['fileMap'],
    		'sourceMapURL' => $outputFileLocations['output']['fileMapUrl'],
    		'sourceMapBasepath' => $outputFileLocations['lessDirPath'],
    	);
    	
    	$cssContent = '';
    	try {
    		$parser = new \Less_Parser($parserOptions);
    		$parser->parseFile($outputFileLocations['styleFilePath'], $outputFileLocations['templateUrlWithPath']);
    		$parser->ModifyVars($this->_getOptionVariables($optionGroupName));
    		
    		$cssContent = $parser->getCss();
    		
    	} catch (\Exception $e) {
    		// dd($e);
    		return $e->getMessage();
    	}
    	
    	// Save compiled file
    	$this->_saveCompiledCss($outputFileLocations['output']['file'], $cssContent);
    	
    	$response = \Response::make($cssContent);
    	$response->header('Content-Type', 'text/css');
    	
    	return $response;
    }
    
    private function _getOutputFileLocations($lessFilePath, $templateFolder) {
    	
    	$lessFilePath = str_replace('\\', '/', $lessFilePath);
    	
    	$lessDirPath = dirname($lessFilePath);
    	$templateUrlWithPath = templates_url() . $templateFolder . '/' . $lessDirPath . '/';
    	$templatePath = templates_path() . $templateFolder;
    	
    	// Output dirs
    	$outputDir = media_uploads_path() . 'css/';
    	$outputUrl = media_uploads_url() . 'css/';
    	
    	$outputFile = $outputDir . $lessFilePath . '.css';
    	$outputFileUrl = $outputUrl . $lessFilePath . '.css';
    	$outputFileMap = $outputDir . $lessFilePath . '.map';
    	$outputFileMapUrl = $outputUrl . $lessFilePath . '.map';
    	
    	$styleFilePath = normalize_path($templatePath . '/' . $lessFilePath, false);
    	$styleFilePath = str_replace('..', '', $styleFilePath);
    	
    	return array(
    		'lessFilePath'=>$lessFilePath,
    		'lessDirPath'=>$lessDirPath,
    		'styleFilePath'=>$styleFilePath,
    		'templatePath'=>$templatePath,
    		'templateUrlWithPath'=>$templateUrlWithPath,
    		'output'=>array(
    			'url'=>$outputUrl,
    			'dir'=>$outputDir,
    			'file'=>$outputFile,
    			'fileUrl'=>$outputFileUrl,
				'fileMap'=>$outputFileMap,
    			'fileMapUrl'=>$outputFileMapUrl
    		)
    	);
    }
    
    private function _saveCompiledCss($outputFile, $cssContent) {
    	
    	file_put_contents($outputFile, $cssContent);
    	
    }
    
    private function _getOptionVariables($optionGroupName) {
    	
    	$optionGroup = mw()->option_manager->get_all('option_group=' . $optionGroupName);
    	
    	
    	$variables = array();
    	
    	if (is_array($optionGroup) and !empty($optionGroup)) {
    		foreach ($optionGroup as $optionGroupItem) {
    			$variables[$optionGroupItem['option_key']] = $optionGroupItem['option_value'];
    		}
    	}
    	
    	return $variables;
    	
    }
    
    private function _getOutputDir($path = false) {
    	
    	$path = str_replace('\\', '/', $path);
    	
    	$output_dir = media_uploads_path() . 'css/';
    	
    	$dn_out = dirname($output_dir . $path);
    	if (!is_dir($dn_out)) {
    		mkdir_recursive($dn_out);
    	}
    	
    	return  $output_dir . $path;
    }
    
 }