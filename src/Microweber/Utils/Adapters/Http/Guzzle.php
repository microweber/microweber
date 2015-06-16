<?php
namespace Microweber\Utils\Adapters\Http;


use GuzzleHttp\Client;


class Guzzle
{

    public $url = "";
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
        $client = new Client();
        $response = $client->get($this->url, ['timeout' => $this->timeout]);
        $body = $response->getBody();
        $r = '';
        $body = $response->getBody();
        while (!$body->eof()) {
            $r .= $body->read(1024);
        }

        return $r;

    }

    public function download($save_to_file, $post_data = false)
    {
        if ($save_to_file != false) {
            $dn = dirname($save_to_file);
            if (!is_dir($dn)) {
                mkdir_recursive($dn);
            }
            if (is_dir($dn)) {
                if ($post_data != false) {
                    $ex = $this->post($post_data);
                    @file_put_contents($save_to_file, $ex);
                    return $ex;
                } else {
                    $ex = $this->get();
                    @file_put_contents($save_to_file, $ex);
                    return $ex;

                }
            }
        }
    }


    public function post($data = false)
    {
        $client = new Client();

        $response = $client->post($this->url, [
            'body' => $data,
            'timeout' => $this->timeout
        ]);


        $r = '';
        $body = $response->getBody();


        while (!$body->eof()) {
            $r .= $body->read(1024);
        }


        return $r;
    }

}
