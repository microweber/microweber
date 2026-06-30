<?php

namespace MicroweberPackages\Http\Adapters;

use MicroweberPackages\Http\HttpClientFactory;

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
        $client = HttpClientFactory::guzzle(['timeout' => $this->timeout]);

        $response = $client->get($this->url);

      	return $response->getBody()->getContents();
    }

    public function download($save_to_file, $post_data = array())
    {
        if ($save_to_file != false) {
            $dn = dirname($save_to_file);
            if (!is_dir($dn)) {
                if (function_exists('mkdir_recursive')) {
                    mkdir_recursive($dn);
                } else {
                    @mkdir($dn, 0755, true);
                }
            }
            if (is_dir($dn)) {
                $url = $this->url;
                if (function_exists('set_time_limit')) {
                    @set_time_limit(0);
                }

                $fp = fopen($save_to_file, 'w+');
                $ch = curl_init(str_replace(' ', '%20', $url));
                curl_setopt($ch, CURLOPT_TIMEOUT, 120);
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                HttpClientFactory::applySslOptions($ch);

                curl_exec($ch);
                curl_close($ch);
                fclose($fp);

                return true;
            }
        }

        return false;
    }

    public function post($data = false)
    {
        $client = HttpClientFactory::guzzle(['timeout' => $this->timeout]);

        $response = $client->post($this->url, [
            'form_params' => $data,
        ]);

        $body = $response->getBody();

        return (string) $body;
    }
}