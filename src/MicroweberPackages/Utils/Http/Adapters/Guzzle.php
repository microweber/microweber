<?php

namespace MicroweberPackages\Utils\Http\Adapters;

use GuzzleHttp\Client;

class Guzzle
{
    public $url = '';
    public $debug = false;
    public $timeout = 60;

    public function __construct()
    {
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function get()
    {
        $client = new Client([ 'verify' => false ]);

        $response = $client->get($this->url, [
            'timeout' => $this->timeout,
            'verify'=>false
           // 'verify'=>__DIR__.DS.'cacert.pem.txt'
        ]);

      	return $response->getBody()->getContents();

    }

    public function download($save_to_file, $post_data = array())
    {
        // DOWNLOAD USES CURL AS GUZZLE DOWNLOAD DOES NOT WORK
        // http://stackoverflow.com/questions/16939794/copy-remote-file-using-guzzle
        // https://gist.github.com/romainneutron/5340930

        if ($save_to_file != false) {
            $dn = dirname($save_to_file);
            if (!is_dir($dn)) {
                mkdir_recursive($dn);
            }
            if (is_dir($dn)) {
                $url = $this->url;
                if (function_exists('set_time_limit')) {
                    @set_time_limit(0);
                }

                $fp = fopen($save_to_file, 'w+');//This is the file where we save the    information
                $ch = curl_init(str_replace(' ', '%20', $url));//Here is the file we are downloading, replace spaces with %20
                curl_setopt($ch, CURLOPT_TIMEOUT, 120);
                curl_setopt($ch, CURLOPT_FILE, $fp); // write curl response to file
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_CAINFO, __DIR__.DS.'cacert.pem.txt');
                curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS | CURLPROTO_HTTP);
                curl_setopt($ch, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTPS | CURLPROTO_HTTP);

                curl_exec($ch); // get curl response
                curl_close($ch);
                fclose($fp);

                return true;
            }
        }

        return false;
    }

    public function post($data = false)
    {
        $client = new Client([ 'verify' => false ]);

        $form_params  = [
            'form_params' => $data,
            'timeout' => $this->timeout,
            'verify'=>false
            //'verify'=>__DIR__.DS.'cacert.pem.txt'
        ];

        $response = $client->post($this->url,$form_params );





        $r = '';
        $body = $response->getBody();

        if (is_object($body) and method_exists($body, 'eof')) {
            return (string) $body;
//            while (!$body->eof()) {
            // has bug, it does not return full resp
//                $r .= $body->read(1024);
//            }
        } else {
            return (string) $body;
        }

        return $r;
    }
}
