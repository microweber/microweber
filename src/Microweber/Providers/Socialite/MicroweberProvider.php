<?php

namespace Microweber\Providers\Socialite;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Microweber\Utils\Http;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\RequestException;

class MicroweberProvider extends AbstractProvider implements ProviderInterface
{
    protected $serverUrl = 'https://mwlogin.com';
    protected $scopes = [];

    protected function apiUrl($path)
    {
        $microweber_app_url = get_option('microweber_app_url', 'users');
        if (!empty($microweber_app_url)) {
            $this->serverUrl = $microweber_app_url;
        }

        return $this->serverUrl . $path;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        $url = $this->apiUrl('/oauth/authorize');


        return $this->buildAuthUrlFromBase($url, $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return $this->apiUrl('/oauth/token');
    }

    /**
     * Get the POST fields for the token request.
     *
     * @param  string $code
     * @return array
     */
    protected function getTokenFields($code)
    {
        return [
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'redirect_uri' => $this->redirectUrl,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {


        $response = $this->getHttpClient()->get($this->apiUrl('/api/user'), [
            'headers' => ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $token],


            'verify' => MW_PATH . 'Utils' . DS . 'Adapters' . DS . 'Http' . DS . 'cacert.pem.txt'
        ]);


        $body = $response->getBody();
        return json_decode($body, true);

    }

    /**
     * {@inheritdoc} 
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            //    'id' => $user['id'],
            'email' => $user['email'],
           // 'name' => $user['name'],
            'oauth_uid' => $user['id'],
            'oauth_provider' => 'microweber',
        ]);
    }


    /* protected function apiUrl($path)
     {
         return $this->serverUrl . $path;
     }

     protected function getAuthUrl($state)
     {
         $url = $this->buildAuthUrlFromBase($this->serverUrl . '/oauth/authorize', $state);

         return $url;
     }

     protected function buildAuthUrlFromBase($url, $state)
     {
         $session = $this->request->getSession();

         $redirectUrl = $this->redirectUrl;
         if (!starts_with($redirectUrl, 'http')) {
             $redirectUrl = 'http://' . $redirectUrl;
         }

         $go =  $url . '?' . http_build_query([
             'client_id' => $this->clientId,
             'client_secret' => $this->clientSecret,
             'redirect_uri' => $redirectUrl,
             'scope' => $this->formatScopes($this->scopes, ' '),
             'state' => $state,

             'response_type' => 'code',
         ]);




         return $go;
     }

     protected function getTokenUrl()
     {
         return $this->apiUrl('/oauth/token');
     }

     public function getAccessToken($code)
     {
         $query = $this->getTokenFields($code);
         $tokenUrl = $this->getTokenUrl() . '?grant_type=client_credentials';
         $tokenUrl = $this->getTokenUrl() . '?grant_type=authorization_code';
         //$response = $this->getHttpClient()->post($tokenUrl, ['body' => $query]);
         $redirectUrl = $this->redirectUrl;
         if (!starts_with($redirectUrl, 'http')) {
             $redirectUrl = 'http://' . $redirectUrl;
         }

         $http = new \GuzzleHttp\Client;
         try {
             $response = $http->post($tokenUrl, [
                 'headers' => ['Accept' => 'application/json'],

                 'form_params' => [
                  //   'grant_type' => 'client_credentials',
                     'grant_type' => 'authorization_code',
                     'client_id' => $this->clientId,
                     'client_secret' => $this->clientSecret,
                     'redirect_uri' => $redirectUrl,
                    'code' => $this->request->code,
                 ],
               'content-type' => 'application/json',
                 'timeout' => 30,
                 'verify' => MW_PATH . 'Utils' . DS . 'Adapters' . DS . 'Http' . DS . 'cacert.pem.txt'
             ]);

             return json_decode((string)$response->getBody(), true);

         } catch (ServerException $exception) {
             $responseBody = $exception->getResponse()->getBody(true);

             return false;

         }


 //
 //
 //
 //        $response = $client->get($this->url, []);
 //
 //        $body = $response->getBody();
 //
 //
 //        return json_decode((string)$response, true);
 //
 //
 //        return $this->parseAccessToken($response);
     }

     protected function parseAccessToken($response)
     {
         // access_token token_type(Bearer) expires expires_in
         $data = $response->json();

         return $data['access_token'];
     }

     protected function getUserByToken($token)
     {
         dd($token);
 //
         //  $response = $this->getHttpClient()->get($this->apiUrl('/api/user')
         $response = $this->getHttpClient()->get($this->apiUrl('/user'), [
             'headers' => ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $token['access_token']],

             'form_params' => [
                 'api_token' => $token['access_token'],

             ],
             'verify' => MW_PATH . 'Utils' . DS . 'Adapters' . DS . 'Http' . DS . 'cacert.pem.txt'
         ]);
         $body = $response->getBody();


         return json_decode($body, true);
     }

     protected function mapUserToObject(array $user)
     {
         return (new User())->setRaw($user)->map([
             'id' => $user['id'],
             'nickname' => null,
             'name' => $user['name'],
             'email' => isset($user['email']) ? $user['email'] : null,
         ]);
     }*/



}
