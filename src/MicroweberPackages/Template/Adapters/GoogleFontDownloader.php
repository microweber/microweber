<?php
namespace MicroweberPackages\Template\Adapters;

use Psr\Log\LoggerInterface;

/**
 * Description of gFontDownloader
 * @description Downloads specified Fonts from Google API and stores them locally
 * @author smt
 * @author Tordt Schmidt <info@smt-webservices.de>
 */
class GoogleFontDownloader {

    protected $_config = ['output' => '.' . DIRECTORY_SEPARATOR, 'onRecoverableError' => 'stop'];
    protected $_request = array();
    protected $_defaultUA = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0';
    protected $_formats = [
        'eot' => 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0)',
        'woff' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:27.0) Gecko/20100101 Firefox/27.0',
        'woff2' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0',
        'svg' => 'Mozilla/4.0 (iPad; CPU OS 4_0_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/4.1 Mobile/9A405 Safari/7534.48.3',
        'ttf' => 'Mozilla/5.0 (Unknown; Linux x86_64) AppleWebKit/538.1 (KHTML, like Gecko) Safari/538.1 Daum/4.1'
    ];
    protected $_definitions = [];

    /*
     * get configured formats if set
     * else all available formats
     * @return array of formats
     */

    protected function _getFormats() {
        if (!isset($this->_getConfig()['formats'])) {
            return array_keys($this->_formats);
        }
        return $this->_getConfig()['formats'];
    }

    /*
     * add Font to Download list
     * @param string $name font Name
     * @param string $style font Style (normal/italic)
     * @param array $weights array of font weights
     * @param array $formats array of file formats , array keys of $_formats used when not set
     * @param array $subsets not used as of now
     */

    public function addFont($name, $style = 'normal', $weights = array('400'), $formats = false, $subsets = false) {
        foreach ($weights as $weight) {
            if (!$formats) {
                $this->_request[$name][$style][$weight]['formats'] = $this->_getFormats();
            } else {
                $this->_request[$name][$style][$weight]['formats'] = array_merge($this->_request[$name][$style]['formats'], $formats);
            }
        }
        if ($subsets) {
            //have fun, i'm not going to implement subsets
        }
    }

    /*
     * adds Fonts to Download List using urls
     * @param string $url URL of fonts to be downloaded
     * param array $formats array of file formats , array keys of $_formats used when not set
     */

    public function addFontByUrl($url, $formats = false, $subsets = false) {
        $params = array();
        $query = parse_url($url, PHP_URL_QUERY);
        parse_str($query, $params);
        $fonts = explode('|', $params['family']?$params['family']:$params['selection_family']);
        foreach ($fonts as $font) {
            $fontName = current(explode(':', $font));
            $list = explode(',', end(explode(':', $font)));

            foreach ($list as $item) {
                $this->addFont($fontName, (stripos($item, 'i') !== false ? 'italic' : 'normal'), [$item>0?(int)$item:400], $formats, $subsets);
            }
        }
    }

    /*
     * override config
     * @param mixed $keyOrValues either false (use config.json file) or array of key=>value pairs  or string/int arraykey
     * @param mixed $value value if first param is string/int
     * @throws InvalidArgumentException on errorous params
     * @throws Exception on no provided params plus missing config file
     */

    public function setConfig($keyOrValues = false, $value = null) {
        if (!$keyOrValues) {
            if ($confFile = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.json')) {
                $this->_config = json_decode($confFile, true);
            } else {
                $this->_handleError(new \Exception('Config File Not Found'), 'warning', true);
            }
        } elseif (is_array($keyOrValues)) {
            $this->_config = array_merge($this->_getConfig(), $keyOrValues);
        } elseif ($keyOrValues && !is_array($keyOrValues) && $value !== null) {
            $this->_config[$keyOrValues] = $value;
        } else {
            $this->_handleError(new \InvalidArgumentException(), 'warning', true);
        }
    }

    /*
     * get Configuration
     * @param string $key configuration key to be retrieved, defaults to false = all keys as array
     * @throws  OutOfBoundsException on missing requested config key
     * @returns mixed complete config array or config value of requestet key
     */

    protected function _getConfig($key = false) {
        if ($key) {
            if (array_key_exists($key, $this->_config)) {
                return $this->_config[$key];
            } else {
                $this->_handleError(new \OutOfBoundsException(), 'warning', true);
            }
        }
        return $this->_config;
    }

    /*
     * adds loaded definitions to cache array
     * @param array $def $definition
     * @param string format (output fileextension)
     */

    protected function _addDone($def, $format) {
        if ($def['family']) {
            $this->_definitions[$def['family']][$def['weight']][$def['style']]['urls'][$format] = $def['url'];
            foreach ($def['names'] as $name) {
                if (!@in_array($name, $this->_definitions[$def['family']][$def['weight']][$def['style']]['names'])) {
                    $this->_definitions[$def['family']][$def['weight']][$def['style']]['names'][] = $name;
                }
            }
        }
    }

    /*
     * download the queued Fonts
     * @trows exception on empty queue
     */

    public function download($callback = false) {
        if (!empty($this->_request)) {
            $this->_definitions = array();
            foreach ($this->_request as $fontFamily => $styles) {
                $this->_createDir($fontFamily);
                try {
                    foreach ($styles as $style => $weights) {
                        foreach ($weights as $weight => $data) {
                            foreach ($data['formats'] as $format) {
                                try {
                                    $def = $this->_getDefinition(str_replace(' ', '+', $fontFamily), $style, $weight, $format);
                                } catch (\Exception $e) {
                                    $this->_handleError($e, 'error', true);
                                }
                                $this->_addDone($def, $format);
                            }
                            try {
                                $this->_fetchFiles($fontFamily, $style, $weight);
                            } catch (\Exception $e) {
                                $this->_handleError($e, 'error', true);
                            }
                            //$cssString = $this->_createFontCssFile($fontFamily, $style, $weight);
                          //  $this->_doCallback($fontFamily, $style, $weight, $callback, $cssString);
                        }
                    }
                } catch (\Exception $e) {
                    $this->_handleError($e, 'error', true);
                }
            }
           // $this->createFamilyCssFiles();
            return $this->_definitions;
        } else {
            $this->_handleError(new \Exception('Nothing to do'), 'warning');
        }
    }

    /*
     * formats callback and invokes callback function for downloader
     * @param string $fontFamily
     * @param string $style
     * @param int $weight
     * @param mixed $callback function or false
     * @param $cssString the font's css definition
     */

    protected function _doCallback($fontFamily, $style, $weight, $callback, $cssString) {
        if ($callback && $cssString) {
            $cb = [
                'font-family' => $fontFamily,
                'italic' => $style == 'italic',
                'bold' => $weight == 700 && $style != 'italic',
                'bolditalic' => $weight == 700 && $style == 'italic',
                'path' => $this->_getConfig('output') . str_replace(' ', '_', $fontFamily),
                'css' => $cssString
            ];
            $callback($cb);
        }
    }

    /*
     * creates single font css File
     * @param string $fontFamily
     * @param string $fontStyle
     * @param int $fontWeight
     * @param array $subsets
     */

    function _createFontCssFile($fontFamily, $fontStyle = 'normal', $fontWeight = 400, $subsets = false) {

        $cssString = '';
        $data = $this->_definitions[$fontFamily][$fontWeight][$fontStyle];
        if (!empty($data['urls'])) {
            $name = str_replace(' ', '_', $fontFamily) . '_' . $fontWeight . ($fontStyle == 'italic' ? 'i' : '');
            $cssString .= "@font-face {
	font-family: '$fontFamily';
	font-style: $fontStyle;
	font-weight: $fontWeight;
	src:\r\n";
            if (is_array($data['names'])) {
                foreach ($data['names'] as $local) {
                    $cssString .= "\t\tlocal('$local'),\r\n";
                }
            }
            $add = array();
            foreach ($data['urls'] as $format => $url) {
                $add[] = "\t\t/* from $url */\r\n\t\turl('$name.$format') format('$format')";
            }
            $cssString .= implode(",\r\n", $add) . ";\r\n";
            $cssString .= "}\r\n";
            if (!@file_put_contents($this->_getConfig('output') . str_replace(' ', '_', $fontFamily) . DIRECTORY_SEPARATOR . $name . '.css', $cssString)) {
                $fpCError = error_get_last();
                $this->_handleError(new \Exception('cant create file ' . $fpCError['message'], 1), 'error', true);
            } else {
                chmod($this->_getConfig('output') . str_replace(' ', '_', $fontFamily) . DIRECTORY_SEPARATOR . $name . '.css', 0755);
            }
            return $cssString;
        } else {
            $this->_handleError(new \Exception($name . ' could not be downloaded', 1), 'error', true);
        }
    }

    /*
     * retrieves font Files
     * @param string $fontFamily
     * @param string $fontStyle
     * @param int $fontWeight
     * @param array $subsets
     */

    protected function _fetchFiles($fontFamily, $fontStyle = 'normal', $fontWeight = 400, $subsets = false) {
        $data = $this->_definitions[$fontFamily][$fontWeight][$fontStyle];
        $name = str_replace(' ', '_', $fontFamily) . '_' . $fontWeight . ($fontStyle == 'italic' ? 'i' : '');
        foreach ($data['urls'] as $format => $url) {
            if ($url != '') {
                if (!@file_put_contents($this->_getConfig('output') . str_replace(' ', '_', $fontFamily) . DIRECTORY_SEPARATOR . $name . '.' . $format, $this->_getFile($url, $this->_formats[$format]))) {
                    $fpCError = error_get_last();
                    $this->_handleError(new \Exception('cant create file ' . $fpCError['message'], 1), 'error', false);
                    unset($this->_definitions[$fontFamily][$fontWeight][$fontStyle]['urls'][$format]);
                } else {
                    chmod($this->_getConfig('output') . str_replace(' ', '_', $fontFamily) . DIRECTORY_SEPARATOR . $name . '.' . $format, 0755);
                }
            }
        }
    }

    /*
     * removes old and creates new \directory for fonts to download
     */

    protected function _createDir($fontName) {
        if (trim($fontName) != '') {
            $fontName = str_replace(' ', '_', $fontName);
            if (is_dir($this->_getConfig('output') . $fontName)) {
                $this->_rmDir($this->_getConfig('output') . $fontName);
            }

            if (!@mkdir($this->_getConfig('output') . $fontName, 0755, true)) {
                $mkdirErrorArray = error_get_last();
                $this->_handleError(new \Exception('cant create directory ' . $mkdirErrorArray['message'], 1), 'error', true);
            }
        }
    }

    /*
     * creates stylesheet for downloaded fonts
     * public so it can be run on its own to create family files
     * for successful downloads in case of unrecoverable error
     */

    public function createFamilyCssFiles() {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->_getConfig('output')));
        $files = array();
        foreach ($iterator as $file) {
            if ($file->isDir()) {
                continue;
            }
            if (end(explode('.', $file->getPathname())) == 'css' && end(explode(DIRECTORY_SEPARATOR, $file->getPathname())) != 'font.css') {
                $filesContents[trim(str_replace($this->_getConfig('output'), '', $file->getPath()), DIRECTORY_SEPARATOR)][] = file_get_contents($file->getPathName());
            }
        }
        foreach ($filesContents as $Font => $contents) {
            $fileName = $this->_getConfig('output') . $Font . DIRECTORY_SEPARATOR . 'Font.css';
            if (file_exists($fileName)) {
                unlink($fileName);
            }
            if (!@file_put_contents($fileName, implode("\r\n\r\n", $contents))) {
                $fpCError = error_get_last();
                $this->_handleError(new \Exception('cant create file ' . $fpCError['message'], 1), 'error', false);
            } else {
                chmod($fileName, 0755);
            }
        }
    }

    /*
     * removes Directory including files in it
     * @param string $dir the directory to remove
     */

    protected function _rmDir($dir) {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir($dir . DIRECTORY_SEPARATOR . $file)) ? $this->_rmDir($dir . DIRECTORY_SEPARATOR . $file) : unlink($dir . DIRECTORY_SEPARATOR . $file);
        }
        return rmdir($dir);
    }

    protected function _getDefinition($fontFamily, $style, $weight, $format) {
        $url = "https://fonts.googleapis.com/css?family=$fontFamily:$weight" . ($style == 'italic' ? 'i' : '');
        $css = $this->_getFile($url, $this->_formats[$format]);
        return $this->_parseCss($css);
    }

    /*
     * retrieve file
     * @param string $url URL of file to retrieve
     * @param string $userAgent Useragent to be used (defaults to $this->_defaultUA
     * @return string result
     * @throws Exception on error
     */

    protected function _getFile($url, $userAgent = false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, trim($url));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_USERAGENT, ($userAgent ? $userAgent : $this->_defaultUA));
        $result = curl_exec($ch);
        if (!$result) {
            if ($err = curl_error($ch)) {
                curl_close($ch);
                $this->_handleError(new \Exception($err . ' for "' . $url . '"'), 'error', true);
            }
            $this->_handleError(new \Exception('ERROR: GOOGLE_API_UNREACHABLE'), 'error', true);
        } else {
            curl_close($ch);
            return $result;
        }
    }

    /*
     * extracts font url from css
     * @param string $string css content
     */

    protected function _getUrl($string) {
        $exp = explode('url(', $string);
        $exp2 = explode(')', $exp[1]);
        return trim($exp2[0]);
    }

    /*
     * extracts font family from css
     * @param string $string css content
     */

    protected function _getFamily($string) {
        $exp = explode("font-family: '", $string);
        $exp2 = explode("'", $exp[1]);
        return trim($exp2[0]);
    }

    /*
     * extracts font style from css
     * @param string $string css content
     */

    protected function _getStyle($string) {
        $exp = explode("font-style: ", $string);
        $exp2 = explode(";", $exp[1]);
        return trim($exp2[0]);
    }

    /*
     * extracts font weight from css
     * @param string $string css content
     */

    protected function _getWeight($string) {
        $exp = explode("font-weight: ", $string);
        $exp2 = explode(";", $exp[1]);
        return trim($exp2[0]);
    }

    /*
     * extracts fonts local names from css
     * @param string $string css content
     */

    protected function _getLocalNames($string) {
        $names = array();
        $exp = explode("local('", $string);
        unset($exp[0]);
        foreach ($exp as $line) {
            $exp2 = explode("')", $line);
            $names[] = trim($exp2[0]);
        }
        return $names;
    }

    protected function _parseCss($raw) {
        $return['family'] = $this->_getFamily($raw);
        $return['style'] = $this->_getStyle($raw);
        $return['weight'] = $this->_getWeight($raw);
        $return['url'] = $this->_getUrl($raw);
        $return['names'] = $this->_getLocalNames($raw);
        $return['raw'] = $raw;
        return $return;
    }

    protected $_logger = null;

    public function setLogger(LoggerInterface $logger) {
        $this->_logger = $logger;
    }

    protected function _handleError(\Exception $ex, $level = 'error', $recoverable = false) {
        $config = $this->_getConfig();
        $action = $config['onRecoverableError'];
        if ($this->_logger) {
            $this->_logger->log($level, strtoupper($level) . ': ', array('exception' => $ex));
        }
        if (!$recoverable || $action != 'recover') {
            throw $ex;
        }
    }

}
