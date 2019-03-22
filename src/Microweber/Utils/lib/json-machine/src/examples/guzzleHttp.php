<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$client = new \GuzzleHttp\Client();
$respose = $client->request('GET', 'https://httpbin.org/anything?key=value');
// Gets PHP stream resource from Guzzle stream
$phpStream = \GuzzleHttp\Psr7\StreamWrapper::getResource($respose->getBody());
foreach (\JsonMachine\JsonMachine::fromStream($phpStream) as $key => $value) {
    var_dump([$key, $value]);
}
