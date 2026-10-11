<?php

namespace MicroweberPackages\Template\Adapters;

/**
 * @deprecated
 */
class TemplateStackRenderer
{
    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;
    public $stacks = array();
    private $_stacks_for_display = array();
    private $_printer = array();


    public function __construct($app = null)
    {
        $this->app = $app;
    }

    public function add($src, $group = 'default', $key = null)
    {
        if (!isset($this->stacks[$group])) {
            $this->stacks[$group] = array();
        }
        if ($key) {
            $this->stacks[$group][$key] = $src;
        } else {
            $this->stacks[$group][] = $src;
        }
    }


    public function display($group = 'default', $to_return = false)
    {
        if (!isset($this->_stacks_for_display[$group])) {
            $this->_stacks_for_display[$group] = true;

            if (!isset($this->_printer[$group])) {
                $replace_later = '<!-- [template-stack-display-' . $group . '] -->';
                $this->_printer[$group] = $replace_later;
                if($to_return){
                    return $replace_later;
                } else {
                    print $replace_later;
                }
            }
        }
    }


    public function render($layout)
    {
     //   $compile_assets = \Config::get('microweber.compile_assets');
        $compile_assets = false;

        if ($this->_printer) {
            foreach ($this->_printer as $stack_name => $replace_key) {
                if (isset($this->_stacks_for_display[$stack_name])) {
                    if(!isset($this->stacks[$stack_name])){
                        $this->stacks[$stack_name] = array();
                    }
                    $stack = $this->stacks[$stack_name];
                    if ($stack) {
                        $stack_html = '';
                        $stack_html = $this->__buildHTML($stack, $compile_assets);

                        $layout = str_replace_first($replace_key, $stack_html, $layout);
                    }
                }


            }
        }

        return $layout;
    }


    private function __buildHTML($stack_items, $compile_assets)
    {


        $html_out = '';

        $internals_js = array(
         //   app()->template_manager->get_apijs_settings_url(),
          //  app()->template_manager->get_apijs_url()
        );

        $css = array();
        $js = array();
        foreach ($stack_items as $stack_item) {
            $url = strtok($stack_item, '?');
            $ext = get_file_extension($url);
            $ext = strtolower($ext);

            if ($ext == 'js' or in_array($url, $internals_js) or in_array($stack_item, $internals_js)) {
                $js[] = $url;
            } elseif ($ext == 'css') {
                $css[] = $url;
            }
        }


        if ($js) {
            $compiledJs = array_unique($js);
            if ($compile_assets) {
                $compiledJs = $this->__compileJs($compiledJs);
            }
            foreach ($compiledJs as $file) {
                $html_out .= '<script src="' . $file . '"></script>' . "\n";
            }
        }
        if ($css) {

            $compiledCss = array_unique($css);
            if ($compile_assets) {
                $compiledCss = $this->__compileCss($compiledCss);
            }
            if (!empty($compiledCss) && is_array($compiledCss)) {
                foreach ($compiledCss as $cssUrl) {
                    $html_out .= '<link rel="stylesheet" media="all" type="text/css" href="' . $cssUrl . '">' . "\n";
                }
            }
        }


        return $html_out;
    }

    private function __compileCss($cssFiles)
    {

        // Output dirs
        $outputDir = media_uploads_path() . 'css/';
        $outputUrl = media_uploads_url() . 'css/';

        if (!is_dir($outputDir)) {
            mkdir_recursive($outputDir);
        }

        //$cssFiles[] = 'https://bootswatch.com/4/cosmo/bootstrap.css';

        $localFiles = array();
        if (is_array($cssFiles)) {
            foreach ($cssFiles as $css) {

                $isLocal = false;
                $localFilename = url2dir($css);
                $public_url_dir = dir2url(dirname($localFilename)) . '/';

                if (is_file($localFilename)) {
                    $isLocal = true;
                }

                if ($isLocal) {
                    $localFiles[] = array('mtime' => filemtime($localFilename), 'public_filename' => $css, 'public_url_dir' => $public_url_dir, 'local_filename' => $localFilename);
                } else {
                    $readyCssLinks[] = $css;
                }

            }
        }
        //	dd($localFiles);
        if (!empty($localFiles) && is_array($localFiles)) {

            $hashLocalFiles = md5(serialize($localFiles));
            $combineFilename = $hashLocalFiles . '.css';

            $readyCssContent = '';
            foreach ($localFiles as $localFile) {

                $parser = new \Less_Parser();
                $parser->parseFile($localFile['local_filename'], $localFile['public_url_dir']);
                $readyCssContent .= $parser->getCss() . "\n";

            }

            $save = file_put_contents($outputDir . $combineFilename, $readyCssContent);


            $parser = new \Less_Parser();
            $parser->parseFile($outputDir . $combineFilename, dir2url($outputDir . $combineFilename . ''));
            $orderImportCssFiles = $parser->getCss() . "\n";

            $save = file_put_contents($outputDir . $combineFilename, $orderImportCssFiles);
            if ($save) {
                $readyCssLinks[] = $outputUrl . $combineFilename;
            }
        }

        return $readyCssLinks;
    }

    private function __compileJs($jsFiles)
    {

        // Output dirs
        $outputDir = media_uploads_path() . 'js/';
        $outputUrl = media_uploads_url() . 'js/';

        if (!is_dir($outputDir)) {
            mkdir_recursive($outputDir);
        }

        //$jsFiles[] = 'https://bootswatch.com/4/cosmo/bootstrap.js';

        $localFiles = array();
        if (is_array($jsFiles)) {
            foreach ($jsFiles as $js) {

                $isLocal = false;
                $localFilename = url2dir($js);

                if (is_file($localFilename)) {
                    $isLocal = true;
                }

                if ($isLocal) {
                    $localFiles[] = array('mtime' => filemtime($localFilename), 'public_filename' => $js, 'local_filename' => $localFilename);
                } else {
                    $readyJsLinks[] = $js;
                }

            }
        }

        if (!empty($localFiles) && is_array($localFiles)) {

            $hashLocalFiles = md5(serialize($localFiles));
            $combineFilename = $hashLocalFiles . '.js';

            $readyJsContent = '';
            foreach ($localFiles as $localFile) {

                $fileContent = file_get_contents($localFile['local_filename']);
                if (!empty($fileContent)) {
                    $readyJsContent .= $fileContent . PHP_EOL;
                }

            }

            $save = file_put_contents($outputDir . $combineFilename, $readyJsContent);
            if ($save) {
                $readyJsLinks[] = $outputUrl . $combineFilename;
            }

        }

        return $readyJsLinks;
    }


    /*
    	if (!empty($localFiles) && is_array($localFiles)) {

	    	$hashLocalFiles = md5(serialize($localFiles));
	    	$combineFilename = $hashLocalFiles . '.css';

	    	$readyCssContent = '';
	    	foreach($localFiles as $localFile) {

	    		$fileContent = file_get_contents($localFile['local_filename']);
	    		if (!empty($fileContent)) {
	    			$readyCssContent .= $fileContent .  PHP_EOL;
	    		}

	    	}

	    	$minifyCssContent = $this->__minifyCss($readyCssContent);

	    	$save = file_put_contents($outputDir . $combineFilename, $minifyCssContent);
	    	if ($save) {
	    		$readyCssLinks[] = $outputUrl . $combineFilename;
	    	}

    	}
    */


    private function __minifyCss($css)
    {
        try {
            return app(\MicroweberPackages\Minifier\Services\CssMinify::class)->minify((string) $css);
        } catch (\Throwable $e) {
            // Runtime resilience only: keep the original CSS if minification of
            // this specific asset throws — never masks a missing dependency.
            return $css;
        }
    }
}
