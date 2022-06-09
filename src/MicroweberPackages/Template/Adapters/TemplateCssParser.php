<?php

namespace MicroweberPackages\Template\Adapters;

class TemplateCssParser
{
    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;

    public function __construct($app = null)
    {
        $this->app = $app;
        if (!is_object($this->app)) {
            $this->app = app();
        }
    }

    public function getStylesheet($lessFilePath, $defaultCssFile = false, $cache = true)
    {




        if (config('microweber.developer_mode') == 1) {
            $cache = false;
        }
        $returnUrl = false;
        $themeFolderName = $this->app->template->folder_name();
        $optionGroupName = 'mw-template-' . $themeFolderName;

        $outputFileLocations = $this->_getOutputFileLocations($lessFilePath, $optionGroupName);
        $user_has_settings = $this->_getOptionVariables($optionGroupName);

        if ($defaultCssFile and !$user_has_settings) {

            $templatePath = templates_path() . $themeFolderName;
            $defaultCssFilePath = templates_path() . $themeFolderName;
            $defaultCssFilePath = normalize_path($templatePath . '/' . $defaultCssFile, false);
            $defaultCssFileUrl = templates_url() . $themeFolderName;
            $defaultCssFileUrl = $defaultCssFileUrl . '/' . $defaultCssFile;
            if (is_file($defaultCssFilePath) and !$user_has_settings) {
                return $defaultCssFileUrl;
            }
        }


        $token = md5(mw()->user_manager->session_id());


        $to_generate_css_file = false;


        if (isset($outputFileLocations['output']['file']) and (!is_file($outputFileLocations['output']['file']) or !$cache)) {
            $to_generate_css_file = $returnUrl = api_url('template/compile_css?path=' . $lessFilePath . '&option_group=' . $optionGroupName . '&template_folder=' . $themeFolderName . '&token=' . $token);
            if (!defined('MW_NO_OUTPUT_CACHE')) {
                define('MW_NO_OUTPUT_CACHE', true);
            }

        }

        if ($cache == false and $to_generate_css_file) {
            $returnUrl = $to_generate_css_file;
        } else {
            if (isset($outputFileLocations['output']['file']) && is_file($outputFileLocations['output']['file'])) {
                $returnUrl = $outputFileLocations['output']['fileUrl'];

            } else if ($to_generate_css_file and isset($outputFileLocations['cssFilePath']) and isset($outputFileLocations['output']['fileCss']) and !is_file($outputFileLocations['output']['fileCss'])) {
                $returnUrl = $to_generate_css_file;

            } else if (isset($outputFileLocations['output']['fileCssUrl']) and isset($outputFileLocations['output']['fileCss']) and is_file($outputFileLocations['output']['fileCss'])) {
                $returnUrl = $outputFileLocations['output']['fileCssUrl'];

            } else {
                //    $returnUrl = $outputFileLocations['output']['fileUrl'];
            }

        }

        return $returnUrl;

    }

    public function compile($options)
    {
        if (php_can_use_func('ini_set')) {
            ini_set('memory_limit', '-1');
        }
        if (php_can_use_func('set_time_limit')) {
            set_time_limit(1200);
        }
        $token = md5(mw()->user_manager->session_id());

        if ($options['token'] !== $token) {
            return;
        }

        $compileFile = $this->_getOutputDir($options['path']);
        $compileFile = normalize_path($compileFile, false);
        $extension = get_file_extension($compileFile);

        if ($extension == 'less') {
            return $this->compileLess($options);
        } else {
            return $this->compileSass($options);
        }

    }

    public function delete_compiled($options)
    {

        $optionGroup = mw()->option_manager->get_all('option_group=' . $options['option_group']);
        if (isset($options['delete_options']) and $options['delete_options']) {
            if (!empty($optionGroup)) {
                foreach ($optionGroup as $option) {
                    mw()->option_manager->delete($option['option_key'], $option['option_group']);
                }
            }
        }
        $compileFile = $this->_getOutputDir($options['path']);
        $compileFile = normalize_path($compileFile, false);
        $compileFile = $compileFile . '.css';

        @unlink($compileFile);

        return 1;
    }

    public function compileSass($params)
    {


        $lessFilePath = array_get($params, 'path', false);
        $optionGroupName = array_get($params, 'option_group', false);
        $templateFolder = array_get($params, 'template_folder', false);
        $cssPath = array_get($params, 'css_path', false);
        $outputFileLocations = $this->_getOutputFileLocations($lessFilePath, $templateFolder);

        $dn = dirname($outputFileLocations['output']['file']);
        if (!is_dir($dn)) {
            mkdir_recursive($dn);
        }

        $parserOptions = array(
            'sourceMap' => true,
            'compress' => true,
            'sourceMapWriteTo' => $outputFileLocations['output']['fileMap'],
            'sourceMapURL' => $outputFileLocations['output']['fileMapUrl'],
            'sourceMapBasepath' => $outputFileLocations['lessDirPath'],
        );


//        $options = [
//            'importPaths'        => $this->importPaths,
//            'registeredVars'     => $this->registeredVars,
//            'registeredFeatures' => $this->registeredFeatures,
//            'encoding'           => $this->encoding,
//            'sourceMap'          => serialize($this->sourceMap),
//            'sourceMapOptions'   => $this->sourceMapOptions,
//            'formatter'          => $this->formatter,
//            'legacyImportPath'   => $this->legacyCwdImportPath,
//        ];



        $compiler = new \ScssPhp\ScssPhp\Compiler();


        $compiler->setSourceMapOptions(array(
            'sourceMapWriteTo' =>$outputFileLocations['output']['fileMap'],
            'sourceMapURL' => $outputFileLocations['output']['fileMapUrl'],
            'sourceMapBasepath' => $outputFileLocations['lessDirPath'],
            'sourceRoot' => dirname($outputFileLocations['styleFilePath']).'/',

        ));
        if(!is_file($outputFileLocations['styleFilePath'])){
            return;
        }

        $cssOrig = file_get_contents($outputFileLocations['styleFilePath']);
        $cssOrigFileDistContent=  '';
        $cssOrigFileDist =normalize_path( $outputFileLocations['styleFilePathDist'], false);
        if(is_file($cssOrigFileDist)){
            $cssOrigFileDistContent = file_get_contents($cssOrigFileDist);
        }


      //  $cssOrigNoSettings = file_get_contents($outputFileLocations['output']['fileCss']);
         $variables =  $this->_getOptionVariables($optionGroupName);

        if(!$variables){
            $cssOrigFileDistContent = $this->replaceAssetsRelativePaths($cssOrigFileDistContent,$params);

            $response = \Response::make($cssOrigFileDistContent);
            $response->header('Content-Type', 'text/css');
            return $response;
        }

        $compiler->setVariables($variables);
        $compiler->addParsedFile($outputFileLocations['styleFilePath']);
        $compiler->addImportPath(dirname($outputFileLocations['styleFilePath']).'/');

        $cssContent = $compiler->compile($cssOrig,dirname($outputFileLocations['styleFilePath']).'/');


        $cssContent = $this->replaceAssetsRelativePaths($cssContent,$params);

        //replace vars with with -- as  --primary: $primary;
        foreach ($variables as $variable_name=>$variable_val){
            $replace = '--'.$variable_name.': '.$variable_val.'';
            $search = '--'.$variable_name.': $'.$variable_name.'';
            $cssContent = str_replace($search,$replace,$cssContent);

            $search = '$'.$variable_name.'';
            $replace = "$variable_val";
            $cssContent = str_replace($search,$replace,$cssContent);

        }


        $this->_saveCompiledCss($outputFileLocations['output']['file'], $cssContent);



       $response = \Response::make($cssContent);
       $response->header('Content-Type', 'text/css');

       return $response;

    }

    public function replaceAssetsRelativePaths($cssContent, $params)
    {


        if ($cssContent and isset($params['template_folder']) and isset($params['path'])) {

            $template_url_css_assets = templates_url() . $params['template_folder'] . '/' . dirname(dirname($params['path'])) . '/';
            $cssContent = str_replace('../', $template_url_css_assets, $cssContent);
            // relative to userfiles/media/default/css/new-world
             $cssContent = str_replace(userfiles_url(), '../../../../../../', $cssContent);

        }
        return $cssContent;
    }

        public function compileLess($params)
    {

        $lessFilePath = array_get($params, 'path', false);
        $optionGroupName = array_get($params, 'option_group', false);
        $templateFolder = array_get($params, 'template_folder', false);
        $cssPath = array_get($params, 'css_path', false);
        $outputFileLocations = $this->_getOutputFileLocations($lessFilePath, $templateFolder);

        $dn = dirname($outputFileLocations['output']['file']);
        if (!is_dir($dn)) {
            mkdir_recursive($dn);
        }


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

            if (isset($outputFileLocations['templateUrlWithPathCss']) and $outputFileLocations['templateUrlWithPathCss']) {
//templateUrlWithPathCss
                $parser->parseFile($outputFileLocations['styleFilePath'], $outputFileLocations['templateUrlWithPathCss']);

            } else {
                $parser->parseFile($outputFileLocations['styleFilePath'], $outputFileLocations['templateUrlWithPath']);

            }


            //templateUrlWithPathCss

            $parser->ModifyVars($this->_getOptionVariables($optionGroupName));

            $cssContent = $parser->getCss();

//            if (strpos($outputFileLocations['lessFilePath'], '/css/less/') !== false) {
//                $cssContent = str_replace('/css/img/', '/img/', $cssContent);
//            }

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

    private function _getOutputFileLocations($lessFilePath, $templateFolder)
    {

        $lessFilePath = str_replace('\\', '/', $lessFilePath);

        $templateConfig = mw()->template->get_config();

        if (isset($templateConfig['version'])) {
            $lessFilePathWithVersion = $lessFilePath . '.' . MW_VERSION . '-' . $templateConfig['version'];
        } else {
            $lessFilePathWithVersion = $lessFilePath . '.' . MW_VERSION;
        }

        $lessDirPath = dirname($lessFilePathWithVersion);
        $templateUrlWithPathBase = templates_url() . $templateFolder . '/';
        $templateUrlWithPath = $templateUrlWithPathBase . $lessDirPath . '/';
        $templatePath = templates_path() . $templateFolder;


        // Output dirs
        $outputDir = media_uploads_path() . 'css/' . $templateConfig['dir_name'] . '/';
        $outputUrl = media_uploads_url() . 'css/' . $templateConfig['dir_name'] . '/';

        if (!is_dir($outputDir)) {
            mkdir_recursive($outputDir);
        }


        $outputFile = $outputDir . $lessFilePathWithVersion . '.css';

        $mtime = false;
        if (is_file($outputFile)) {
            $mtime = filemtime($outputFile);
        }

        if (is_file($lessFilePath)) {
            $mtime =$mtime.''. filemtime($lessFilePath);
        }

        $outputFileUrl = $outputUrl . $lessFilePathWithVersion . '.css';
        if ($mtime) {
            $outputFileUrl = $outputUrl . $lessFilePathWithVersion . '.css?t=' . $mtime;
        }
        $outputFileMap = $outputDir . $lessFilePathWithVersion . '.map';
        $outputFileMapUrl = $outputUrl . $lessFilePathWithVersion . '.map';

        $styleFilePath = normalize_path($templatePath . '/' . $lessFilePath, false);

        $cssfilepath = false;
        $templateUrlWithPathCss = false;
        $outputFileCss = false;
        $outputFileCssUrl = false;
        $outputFileCssLocal = false;
        if (is_array($templateConfig) and isset($templateConfig['stylesheet_compiler']) and isset($templateConfig['stylesheet_compiler']['css_file']) and $templateConfig['stylesheet_compiler']['css_file']) {
            $cssfilepath = $templateConfig['stylesheet_compiler']['css_file'];
            $templateUrlWithPathCss = $templateUrlWithPathBase . dirname($cssfilepath) . '/';
            $outputFileCss = $templateUrlWithPathBase . dirname($cssfilepath) . '/';
            $outputFileCss = $outputDir . $cssfilepath;

            //  $outputFileCss
            $mtime2 = false;
            $outputFileCssUrl = $outputUrl . $cssfilepath . '';
            $outputFileCssLocal = $outputDir . $cssfilepath . '';

            if (is_file($outputFileCss)) {
                $mtime2 = filemtime($outputFileCss);
                $outputFileCssUrl = $outputUrl . $cssfilepath . '?t=' . $mtime2;

            }

            //   $styleFilePath = normalize_path($templatePath . '/' . $templateConfig['stylesheet_compiler']['css_file'], false);
        }

        $styleFilePathCss = normalize_path($templatePath . '/' . $cssfilepath, false);


        $styleFilePath = str_replace('..', '', $styleFilePath);

        return array(
            'lessFilePath' => $lessFilePath,
            'lessDirPath' => $lessDirPath,
            'styleFilePath' => $styleFilePath,
            'styleFilePathDist' => $styleFilePathCss,
            'cssFilePath' => $cssfilepath,
            'templateUrlWithPathCss' => $templateUrlWithPathCss,
            //'templatePath' => $templatePath,
            'templateUrlWithPath' => $templateUrlWithPath,
            'output' => array(
                'url' => $outputUrl,
                'dir' => $outputDir,
                'file' => $outputFile,

                'fileUrl' => $outputFileUrl,
                'fileMap' => $outputFileMap,
                'fileMapUrl' => $outputFileMapUrl,
                'fileCss' => $outputFileCss,
                'fileCssUrl' => $outputFileCssUrl,
                //'fileCssLocal' => $outputFileCssLocal,
            )
        );
    }

    private function _saveCompiledCss($outputFile, $cssContent)
    {

        $outputFile = normalize_path($outputFile, false);

        $dir = dirname($outputFile);
        if (!is_dir($dir)) {
            mkdir_recursive($dir);
        }


        file_put_contents($outputFile, $cssContent);

    }

    private function _getOptionVariables($optionGroupName)
    {

       // $optionGroup = mw()->option_manager->get_all('option_group=' . $optionGroupName);
        $optionGroup = app()->option_repository->getOptionsByGroup($optionGroupName);


        $variables = array();

        if (is_array($optionGroup) and !empty($optionGroup)) {
            foreach ($optionGroup as $optionGroupItem) {
                $variables[$optionGroupItem['option_key']] = $optionGroupItem['option_value'];
            }
        }

        return $variables;

    }

    private function _getOutputDir($path = false)
    {

        $path = str_replace('\\', '/', $path);

        $output_dir = media_uploads_path() . 'css/';

        $dn_out = dirname($output_dir . $path);
        if (!is_dir($dn_out)) {
            mkdir_recursive($dn_out);
        }

        return $output_dir . $path;
    }

}
