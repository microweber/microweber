<?php

namespace MicroweberPackages\Utils\Http\Adapters;

/**
 * @original_author    Jason Michels https://thebizztech@github.com/thebizztech/Simple-Codeigniter-Curl-PHP-Class.git
 */
class Curl
{
    public $url = '';
    public $debug = false;
    public $timeout = 60;
    public $save_to_file = false; // path to save
    public $mimeTypes = array(
        'txt' => 'text/plain',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',

        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',

        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',

        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',

        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',

        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',

        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    );
    private $headers = array(); //Headers are built in set_headers() and passed in execute()
    private $uploads = array();
    private $post_data = '';
    private $fields_string = '';
    private $log = '';
    private $log_request_response = 1;
    private $http_headers = '';

    public function __construct()
    {
        //$this->log = new \Logging\Log();
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    //Headers can be modified depending on what you need cURL to accomplish

    public function setHttpHeaders($headers)
    {
        $this->http_headers = $headers;

        return $this;
    }

    public function get()
    {
        return $this->setHeaders()->execute();
    }

    //Set the headers and process curl via a GET

    public function download($save_to_file, $post_data = false)
    {
        if ($save_to_file != false) {
            $dn = dirname($save_to_file);
            if (!is_dir($dn)) {
                mkdir_recursive($dn);
            }
            if (is_dir($dn)) {
                $this->save_to_file = $save_to_file;
                if ($post_data != false) {
                    $ex = $this->post($post_data);
                    $this->save_to_file = false;

                    return $ex;
                } else {
                    $ex = $this->execute($post_data);
                    $this->save_to_file = false;

                    return $ex;
                }
            }
        }
        $this->save_to_file = false;
    }

    //Set the headers and process curl via a POST

    public function post($data = false)
    {
        $is_new_curl = class_exists('CurlFile', false);

        if (is_array($data)) {
            if ($is_new_curl) {
                if (is_array($data)) {
                    foreach ($data as $k => $v) {
                        if (is_string($v)) {
                            $one_char = substr($v, 0, 1);

                            if ($one_char == '@') {
                                $left = trim(substr($v, 1, strlen($v)));
                                if ($left != false) {
                                    $base = basename($left);
                                    $mime = $this->getMimeType($left);
                                    $this->uploads[$k] = new \CurlFile($left, $mime, $base);
                                }
                            }
                        }
                    }
                }
            }

            $this->post_data = $data;
            $this->buildPostString();

            return $this->setHeaders('post')->execute();
        } else {
            $this->fields_string = $data;

            return $this->setHeaders()->execute();
        }
    }

    private function getMimeType($fn)
    {
        $filename = $fn;
        $dots = explode('.', $filename);
        $ext = strtolower(end($dots));
        if (array_key_exists($ext, $this->mimeTypes)) {
            return $this->mimeTypes[$ext];
        } elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);

            return $mimetype;
        } else {
            return 'application/octet-stream';
        }
    }

    private function buildPostString()
    {
        $is_new_curl = class_exists('CurlFile', false);
        if (function_exists('curl_init')) {
            $this->fields_string = null;
            foreach ($this->post_data as $key => $value) {
                if (is_string($key) and is_string($value)) {
                    $this->fields_string .= $key.'='.$value.'&';
                } elseif (is_array($value)) {
                }
            }
            $this->fields_string = rtrim($this->fields_string, '&');

            return $this->fields_string;
        }
    }

    public function execute()
    {
        if (function_exists('curl_init')) {
            $ch = curl_init();
            if (is_array($this->headers) != false) {
            }

            curl_setopt($ch, CURLOPT_VERBOSE, $this->debug);
            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_CAINFO, __DIR__.DS.'cacert.pem.txt');
            curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS | CURLPROTO_HTTP);
            curl_setopt($ch, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTPS | CURLPROTO_HTTP);

            if ($this->timeout != false) {
                if (function_exists('set_time_limit')) {
                    @set_time_limit(600);
                }
            }

            $save_to = $this->save_to_file;
            $this->save_to_file = false;
            $dl = false;
            if ($save_to != false) {
                $save_to = trim($save_to);
                $save_to = sanitize_path($save_to);

                $save_to = normalize_path($save_to, false);

                if (file_exists($save_to)) {
                    $dl = true;
                    $from = filesize($save_to);
                    curl_setopt($ch, CURLOPT_RANGE, $from.'-');
                    $fp = fopen($save_to, 'a');
                } elseif ($save_to != false) {
                    $dl = true;
                    $fp = fopen($save_to, 'w+'); //This is the file where we save the    information
                }

                if (isset($fp) and $fp != false) {
                    $dl = true;
                    if (function_exists('set_time_limit')) {
                        @set_time_limit(600);
                    }

                    curl_setopt($ch, CURLOPT_TIMEOUT, 50);
                    curl_setopt($ch, CURLOPT_FILE, $fp); // write curl response to file
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS | CURLPROTO_HTTP);
                    curl_setopt($ch, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTPS | CURLPROTO_HTTP);
                }
            }
            if ($dl == false) {
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            }

            curl_setopt($ch, CURLOPT_VERBOSE, true);
            $is_new_curl = class_exists('CurlFile', false);
            if (is_array($this->post_data) and !empty($this->post_data)) {
                $str = http_build_query($this->post_data);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
            } elseif ($this->fields_string != false) {
                curl_setopt($ch, CURLOPT_POST, 1);
                if (isset($this->post_data) and is_array($this->post_data) and !empty($this->post_data)) {
                    if ($is_new_curl == false) {
                        $str = ($this->post_data);
                    } else {
                        $str = array_merge($this->post_data, $this->uploads);
                    }
                    if (is_array($str)) {
                        // //$str = http_build_query($str);
                    $str = $this->buildPostString($str);
                    }

                    curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
                } else {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->fields_string);
                }
            }

            // grab URL
            $result = curl_exec($ch);
            if ($dl != false) {
                fclose($fp);
            }
            $this->headers = array();
            curl_close($ch);
        } else {
            $data = $this->post_data;
            if (is_array($data)) {
                $data = http_build_query($data);

                $context = stream_context_create(array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => 'Content-Type: application/x-www-form-urlencoded',
                        'content' => $data,
                    ),
                ));

                $result = @file_get_contents($this->url, false, $context);
            } else {
                $result = @file_get_contents($this->url, false);
            }
        }

        return $result;
    }

    private function setHeaders($type = '')
    {
        if (function_exists('curl_init')) {
            $this->headers = array(
                CURLOPT_URL => $this->url,
                CURLOPT_VERBOSE => 1,
                CURLOPT_SSL_VERIFYPEER => false,
                //CURLOPT_TIMEOUT => 30,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CAINFO => __DIR__.DS.'cacert.pem.txt',

            );

            if ($type == 'post') {
                $this->headers[CURLOPT_POST] = true;
                $this->headers[CURLOPT_POSTFIELDS] = $this->fields_string;
            }

            if (is_array($this->http_headers)) {
                $this->headers[CURLOPT_HTTPHEADER] = $this->http_headers;
                //$this->headers[CURLINFO_HEADER_OUT] = TRUE;
                //$this->headers[CURLOPT_HEADER] = 1;
                $this->headers[CURLOPT_SSL_VERIFYHOST] = false;
            }
        }

        return $this;
    }

    //Starts curl and sets headers and returns the data in a string

    private function is_multidim_array($myarray)
    {
        if (count($myarray) == count($myarray, COUNT_RECURSIVE)) {
            return false;
        } else {
            return true;
        }
    }
}
