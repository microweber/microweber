<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Zend_Gdata_Feed
 */
require_once 'Zend/Gdata/Feed.php';

/**
 * Zend_Gdata_Http_Client
 */
require_once 'Zend/Http/Client.php';

/**
 * Zend_Version
 */
require_once 'Zend/Version.php';

/**
 * Zend_Gdata_App_MediaSource
 */
require_once 'Zend/Gdata/App/MediaSource.php';

/**
 * Provides Atom Publishing Protocol (APP) functionality.  This class and all
 * other components of Zend_Gdata_App are designed to work independently from
 * other Zend_Gdata components in order to interact with generic APP services.
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Gdata_App
{

    /**
     * Client object used to communicate
     *
     * @var Zend_Http_Client
     */
    protected $_httpClient;

    /**
     * Client object used to communicate in static context
     *
     * @var Zend_Http_Client
     */
    protected static $_staticHttpClient = null;

    /**
     * Override HTTP PUT and DELETE request methods?
     *
     * @var boolean
     */
    protected static $_httpMethodOverride = false;

    /**
     * Default URI to which to POST.
     *
     * @var string
     */
    protected $_defaultPostUri = null;

    /**
     * Packages to search for classes when using magic __call method, in order.
     *
     * @var array
     */
    protected $_registeredPackages = array(
            'Zend_Gdata_App_Extension',
            'Zend_Gdata_App');

    /**
     * Maximum number of redirects to follow during HTTP operations
     *
     * @var int
     */
    protected static $_maxRedirects = 5;

    /**
     * Create Gdata object
     *
     * @param Zend_Http_Client $client
     * @param string $applicationId
     */
    public function __construct($client = null, $applicationId = 'MyCompany-MyApp-1.0')
    {
        $this->setHttpClient($client, $applicationId);
    }

    /**
     * Adds a Zend Framework package to the $_registeredPackages array.
     * This array is searched when using the magic __call method below
     * to instantiante new objects.
     *
     * @param string $name The name of the package (eg Zend_Gdata_App)
     * @return void
     */
    public function registerPackage($name)
    {
        array_unshift($this->_registeredPackages, $name);
    }

    /**
     * Retreive feed object
     *
     * @param string $uri The uri from which to retrieve the feed
     * @param string $className The class which is used as the return type
     * @return Zend_Gdata_App_Feed
     */
    public function getFeed($uri, $className='Zend_Gdata_App_Feed')
    {
        return $this->import($uri, $this->_httpClient, $className);
    }

    /**
     * Retreive entry object
     *
     * @param string $uri
     * @param string $className The class which is used as the return type
     * @return Zend_Gdata_App_Entry
     */
    public function getEntry($uri, $className='Zend_Gdata_App_Entry')
    {
        return $this->import($uri, $this->_httpClient, $className);
    }

    /**
     * Get the Zend_Http_Client object used for communication
     *
     * @return Zend_Http_Client
     */
    public function getHttpClient()
    {
        return $this->_httpClient;
    }

    /**
     * Set the Zend_Http_Client object used for communication
     *
     * @param Zend_Http_Client $client The client to use for communication
     * @throws Zend_Gdata_App_HttpException
     * @return Zend_Gdata_App Provides a fluent interface
     */
    public function setHttpClient($client, $applicationId = 'MyCompany-MyApp-1.0')
    {
        if ($client === null) {
            $client = new Zend_Http_Client();
        }
        if (!$client instanceof Zend_Http_Client) {
            require_once 'Zend/Gdata/App/HttpException.php';
            throw new Zend_Gdata_App_HttpException('Argument is not an instance of Zend_Http_Client.');
        }
        $useragent = $applicationId . ' Zend_Framework_Gdata/' . Zend_Version::VERSION;
        $client->setConfig(array(
            'strictredirects' => true,
            'useragent' => $useragent
            )
        );
        $this->_httpClient = $client;
        Zend_Gdata::setStaticHttpClient($client);
        return $this;
    }


    /**
     * Set the static HTTP client instance
     *
     * Sets the static HTTP client object to use for retrieving the feed.
     *
     * @param  Zend_Http_Client $httpClient
     * @return void
     */
    public static function setStaticHttpClient(Zend_Http_Client $httpClient)
    {
        self::$_staticHttpClient = $httpClient;
    }


    /**
     * Gets the HTTP client object. If none is set, a new Zend_Http_Client will be used.
     *
     * @return Zend_Http_Client
     */
    public static function getStaticHttpClient()
    {
        if (!self::$_staticHttpClient instanceof Zend_Http_Client) {
            $client = new Zend_Http_Client();
            $useragent = 'Zend_Framework_Gdata/' . Zend_Version::VERSION;
            $client->setConfig(array(
                'strictredirects' => true,
                'useragent' => $useragent
                )
            );
            self::$_staticHttpClient = $client;
        }
        return self::$_staticHttpClient;
    }

    /**
     * Toggle using POST instead of PUT and DELETE HTTP methods
     *
     * Some feed implementations do not accept PUT and DELETE HTTP
     * methods, or they can't be used because of proxies or other
     * measures. This allows turning on using POST where PUT and
     * DELETE would normally be used; in addition, an
     * X-Method-Override header will be sent with a value of PUT or
     * DELETE as appropriate.
     *
     * @param  boolean $override Whether to override PUT and DELETE with POST.
     * @return void
     */
    public static function setHttpMethodOverride($override = true)
    {
        self::$_httpMethodOverride = $override;
    }

    /**
     * Get the HTTP override state
     *
     * @return boolean
     */
    public static function getHttpMethodOverride()
    {
        return self::$_httpMethodOverride;
    }

    /**
     * Set the maximum number of redirects to follow during HTTP operations
     *
     * @param int $maxRedirects Maximum number of redirects to follow
     * @return void
     */
    public static function setMaxRedirects($maxRedirects)
    {
        self::$_maxRedirects = $maxRedirects;
    }

    /**
     * Get the maximum number of redirects to follow during HTTP operations
     *
     * @return int Maximum number of redirects to follow
     */
    public static function getMaxRedirects()
    {
        return self::$_maxRedirects;
    }

    /**
     * Imports a feed located at $uri.
     *
     * @param  string $uri
     * @param  Zend_Http_Client $client The client used for communication
     * @param  string $className The class which is used as the return type
     * @throws Zend_Gdata_App_Exception
     * @return Zend_Gdata_App_Feed
     */
    public static function import($uri, $client = null, $className='Zend_Gdata_App_Feed')
    {
        $client->resetParameters();
        $client->setHeaders('x-http-method-override', null);
        $client->setUri($uri);
        $client->setConfig(array('maxredirects' => self::getMaxRedirects()));
        $response = $client->request('GET');
        if ($response->getStatus() !== 200) {
            require_once 'Zend/Gdata/App/HttpException.php';
            $exception = new Zend_Gdata_App_HttpException('Expected response code 200, got ' . $response->getStatus());
            $exception->setResponse($response);
            throw $exception;
        }
        $feedContent = $response->getBody();
        $feed = self::importString($feedContent, $className);
        if ($client != null) {
            $feed->setHttpClient($client);
        } else {
            $feed->setHttpClient(self::getStaticHttpClient());
        }
        return $feed;
    }


    /**
     * Imports a feed represented by $string.
     *
     * @param  string $string
     * @param  string $className The class which is used as the return type
     * @throws Zend_Gdata_App_Exception
     * @return Zend_Gdata_App_Feed
     */
    public static function importString($string, $className='Zend_Gdata_App_Feed')
    {
        // Load the feed as an XML DOMDocument object
        @ini_set('track_errors', 1);
        $doc = new DOMDocument();
        $success = @$doc->loadXML($string);
        @ini_restore('track_errors');

        if (!$success) {
            require_once 'Zend/Gdata/App/Exception.php';
            throw new Zend_Gdata_App_Exception("DOMDocument cannot parse XML: $php_errormsg");
        }
        $feed = new $className($string);
        $feed->setHttpClient(self::getstaticHttpClient());
        return $feed;
    }


    /**
     * Imports a feed from a file located at $filename.
     *
     * @param  string $filename
     * @param  string $className The class which is used as the return type
     * @param  string $useIncludePath Whether the include_path should be searched
     * @throws Zend_Gdata_App_Exception
     * @return Zend_Gdata_Feed
     */
    public static function importFile($filename,
            $className='Zend_Gdata_App_Feed', $useIncludePath = false)
    {
        @ini_set('track_errors', 1);
        $feed = @file_get_contents($filename, $useIncludePath);
        @ini_restore('track_errors');
        if ($feed === false) {
            require_once 'Zend/Gdata/App/Exception.php';
            throw new Zend_Gdata_App_Exception("File could not be loaded: $php_errormsg");
        }
        return self::importString($feed, $className);
    }

    /**
     * GET a uri using client object
     *
     * @param  string $uri
     * @throws Zend_Gdata_App_HttpException
     * @return Zend_Http_Response
     */
    public function get($uri)
    {
        $client->setConfig(array('maxredirects' => self::getMaxRedirects()));
        $client->resetParameters();
        $client->setHeaders('x-http-method-override', null);
        $client->setUri($uri);
        $response = $client->request('GET');
        if ($response->getStatus() !== 200) {
            require_once 'Zend/Gdata/App/HttpException.php';
            $exception = new Zend_Gdata_App_HttpException('Expected response code 200, got ' . $response->getStatus());
            $exception->setResponse($response);
            throw $exception;
        }
        return $response;
    }

    /**
     * POST data with client object
     *
     * @param mixed $data The Zend_Gdata_App_Entry or XML to post
     * @param string $uri POST URI
     * @param array $headers Additional HTTP headers to insert.
     * @param string $contentType Content-type of the data
     * @param array $extraHaders Extra headers to add tot he request
     * @return Zend_Http_Response
     * @throws Zend_Gdata_App_Exception
     * @throws Zend_Gdata_App_HttpException
     * @throws Zend_Gdata_App_InvalidArgumentException
     */
    public function post($data, $uri = null, $remainingRedirects = null,
            $contentType = null, $extraHeaders = null)
    {
        require_once 'Zend/Http/Client/Exception.php';
        if ($remainingRedirects === null) {
            $remainingRedirects = self::getMaxRedirects();
        }
        if ($extraHeaders === null) {
            $extraHeaders = array();
        }
        if (is_string($data)) {
            $rawData = $data;
        } elseif ($data instanceof Zend_Gdata_App_MediaEntry) {
            $rawData = $data->encode();
            if ($data->getMediaSource() !== null) {
                $contentType = 'multipart/related; boundary="' . $data->getBoundary() . '"';
                $headers = array('MIME-version' => '1.0');
                $extraHeaders['Slug'] = $data->getMediaSource()->getSlug();
            } else {
                $contentType = 'application/atom+xml';
            }
        } elseif ($data instanceof Zend_Gdata_App_Entry) {
            $rawData = $data->saveXML();
            $contentType = 'application/atom+xml';
        } elseif ($data instanceof Zend_Gdata_App_MediaSource) {
            $rawData = $data->encode();
            if ($data->getSlug() !== null) {
                $extraHeaders['Slug'] = $data->getSlug();
            }
            if ($contentType === null) {
                $contentType = $data->getContentType();
            }
        } else {
            require_once 'Zend/Gdata/App/InvalidArgumentException.php';
            throw new Zend_Gdata_App_InvalidArgumentException(
                    'You must specify the data to post as either a string or a child of Zend_Gdata_App_Entry');
        }
        if ($uri === null) {
            $uri = $this->_defaultPostUri;
        }
        if ($uri === null) {
            require_once 'Zend/Gdata/App/InvalidArgumentException.php';
            throw new Zend_Gdata_App_InvalidArgumentException('You must specify an URI to which to post.');
        }
        if ($contentType === null) {
            $contentType = 'application/atom+xml';
        }
        $headers = array('x-http-method-override' => null);
        if ($extraHeaders !== null) {
            $headers = array_merge($headers, $extraHeaders);
        }
        $this->_httpClient->resetParameters();
        $this->_httpClient->setHeaders($headers);
        $this->_httpClient->setUri($uri);
        $this->_httpClient->setConfig(array('maxredirects' => 0));
        $this->_httpClient->setRawData($rawData, $contentType);
        try {
            $response = $this->_httpClient->request('POST');
        } catch (Zend_Http_Client_Exception $e) {
            require_once 'Zend/Gdata/App/HttpException.php';
            throw new Zend_Gdata_App_HttpException($e->getMessage(), $e);
        }
        /**
         * set "S" cookie to avoid future redirects.
         * TEMPORARILY removed until Zend_Http_Client has a method to set
         * raw cookie data
        if($cookie = $response->getHeader('Set-cookie')) {
            list($cookieName, $cookieValue) = explode('=', $cookie, 2);
            $this->_httpClient->setCookie($cookieName, $cookieValue);
        }
         */
        if ($response->isRedirect()) {
            if ($remainingRedirects > 0) {
                $newUri = $response->getHeader('Location');
                $response = $this->post($data, $newUri, $remainingRedirects - 1, $contentType, $extraHeaders);
            } else {
                require_once 'Zend/Gdata/App/HttpException.php';
                throw new Zend_Gdata_App_HttpException(
                        'No more redirects allowed', null, $response);
            }
        }
        if (!$response->isSuccessful()) {
            require_once 'Zend/Gdata/App/HttpException.php';
            $exception = new Zend_Gdata_App_HttpException('Expected response code 200, got ' . $response->getStatus());
            $exception->setResponse($response);
            throw $exception;
        }
        return $response;
    }

    /**
     * PUT data with client object
     *
     * @param mixed $data The Zend_Gdata_App_Entry or XML to post
     * @param string $uri PUT URI
     * @param array $headers Additional HTTP headers to insert.
     * @param string $contentType Content-type of the data
     * @param array $extraHaders Extra headers to add tot he request
     * @return Zend_Http_Response
     * @throws Zend_Gdata_App_Exception
     * @throws Zend_Gdata_App_HttpException
     * @throws Zend_Gdata_App_InvalidArgumentException
     */
    public function put($data, $uri = null, $remainingRedirects = null,
            $contentType = null, $extraHeaders = null)
    {
        require_once 'Zend/Http/Client/Exception.php';
        if ($remainingRedirects === null) {
            $remainingRedirects = self::getMaxRedirects();
        }
        if ($extraHeaders === null) {
            $extraHeaders = array();
        }
        if (is_string($data)) {
            $rawData = $data;
        } elseif ($data instanceof Zend_Gdata_App_MediaEntry) {
            $rawData = $data->encode();
            if ($data->getMediaSource() !== null) {
                $contentType = 'multipart/related; boundary="' . $data->getBoundary() . '"';
                $headers = array('MIME-version' => '1.0');
                $extraHeaders['Slug'] = $data->getMediaSource()->getSlug();
            } else {
                $contentType = 'application/atom+xml';
            }
        } elseif ($data instanceof Zend_Gdata_App_Entry) {
            $rawData = $data->saveXML();
        } elseif ($data instanceof Zend_Gdata_App_MediaSource) {
            $rawData = $data->encode();
            if ($data->getSlug() !== null) {
                $extraHeaders['Slug'] = $data->getSlug();
            }
            if ($contentType === null) {
                $contentType = $data->getContentType();
            }
        } else {
            require_once 'Zend/Gdata/App/InvalidArgumentException.php';
            throw new Zend_Gdata_App_InvalidArgumentException(
                    'You must specify the data to post as either a string or a child of Zend_Gdata_App_Entry');
        }
        if ($uri === null) {
            if ($data instanceof Zend_Gdata_App_Entry) {
                $editLink = $data->getEditLink();
                if ($editLink != null) {
                    $uri = $editLink->getHref();
                }
            }
        }
        if ($uri === null) {
            require_once 'Zend/Gdata/App/InvalidArgumentException.php';
            throw new Zend_Gdata_App_InvalidArgumentException('You must specify an URI to which to put.');
        }
        if ($contentType === null) {
            $contentType = 'application/atom+xml';
        }
        $headers = array('x-http-method-override' => null);
        if ($extraHeaders !== null) {
            $headers = array_merge($headers, $extraHeaders);
        }
        $this->_httpClient->resetParameters();
        $this->_httpClient->setUri($uri);
        $this->_httpClient->setConfig(array('maxredirects' => 0));
        $this->_httpClient->setRawData($rawData, $contentType);
        try {
            if (Zend_Gdata_App::getHttpMethodOverride()) {
                $this->_httpClient->setHeaders(array('X-HTTP-Method-Override: PUT',
                    'Content-Type: ' . $contentType));
                $response = $this->_httpClient->request('POST');
            } else {
                $this->_httpClient->setHeaders('Content-Type', $contentType);
                $response = $this->_httpClient->request('PUT');
            }
        } catch (Zend_Http_Client_Exception $e) {
            require_once 'Zend/Gdata/App/HttpException.php';
            throw new Zend_Gdata_App_HttpException($e->getMessage(), $e);
        }
        /**
         * set "S" cookie to avoid future redirects.
         * TEMPORARILY removed until Zend_Http_Client has a method to set
         * raw cookie data
        if($cookie = $response->getHeader('Set-cookie')) {
            list($cookieName, $cookieValue) = explode('=', $cookie, 2);
            $this->_httpClient->setCookie($cookieName, $cookieValue);
        }
         */
        if ($response->isRedirect()) {
            if ($remainingRedirects > 0) {
                $newUri = $response->getHeader('Location');
                $response = $this->put($data, $newUri, $remainingRedirects - 1, $contentType, $extraHeaders);
            } else {
                require_once 'Zend/Gdata/App/HttpException.php';
                throw new Zend_Gdata_App_HttpException(
                        'No more redirects allowed', null, $response);
            }
        }
        if (!$response->isSuccessful()) {
            require_once 'Zend/Gdata/App/HttpException.php';
            $exception = new Zend_Gdata_App_HttpException('Expected response code 200, got ' . $response->getStatus());
            $exception->setResponse($response);
            throw $exception;
        }
        return $response;
    }

    /**
     * DELETE entry with client object
     *
     * @param mixed $data The Zend_Gdata_App_Entry or URL to delete
     * @return void
     * @throws Zend_Gdata_App_Exception
     * @throws Zend_Gdata_App_HttpException
     * @throws Zend_Gdata_App_InvalidArgumentException
     */
    public function delete($data, $remainingRedirects = null)
    {
        require_once 'Zend/Http/Client/Exception.php';
        if ($remainingRedirects === null) {
            $remainingRedirects = self::getMaxRedirects();
        }
        if (is_string($data)) {
            $uri = $data;
        } elseif ($data instanceof Zend_Gdata_App_Entry) {
            $editLink = $data->getEditLink();
            if ($editLink != null) {
                $uri = $editLink->getHref();
            }
        } else {
            require_once 'Zend/Gdata/App/InvalidArgumentException.php';
            throw new Zend_Gdata_App_InvalidArgumentException(
                    'You must specify the data to post as either a string or a child of Zend_Gdata_App_Entry');
        }
        if ($uri === null) {
            require_once 'Zend/Gdata/App/InvalidArgumentException.php';
            throw new Zend_Gdata_App_InvalidArgumentException('You must specify an URI which needs deleted.');
        }
        $this->_httpClient->resetParameters();
        $this->_httpClient->setHeaders('x-http-method-override', null);
        $this->_httpClient->setUri($uri);
        $this->_httpClient->setConfig(array('maxredirects' => 0));
        try {
            if (Zend_Gdata_App::getHttpMethodOverride()) {
                $this->_httpClient->setHeaders(array('X-HTTP-Method-Override: DELETE'));
                $this->_httpClient->setRawData('');
                $response = $this->_httpClient->request('POST');
            } else {
                $response = $this->_httpClient->request('DELETE');
            }
        } catch (Zend_Http_Client_Exception $e) {
            require_once 'Zend/Gdata/App/HttpException.php';
            throw new Zend_Gdata_App_HttpException($e->getMessage(), $e);
        }
        if ($response->isRedirect()) {
            if ($remainingRedirects > 0) {
                $newUri = $response->getHeader('Location');
                $response = $this->delete($newUri, $remainingRedirects - 1);
            } else {
                require_once 'Zend/Gdata/App/HttpException.php';
                throw new Zend_Gdata_App_HttpException(
                        'No more redirects allowed', null, $response);
            }
        }
        if (!$response->isSuccessful()) {
            require_once 'Zend/Gdata/App/HttpException.php';
            $exception = new Zend_Gdata_App_HttpException('Expected response code 200, got ' . $response->getStatus());
            $exception->setResponse($response);
            throw $exception;
        }
        return $response;
    }

    /**
     * Inserts an entry to a given URI and returns the response as a fully formed Entry.
     * @param mixed  $data The Zend_Gdata_App_Entry or XML to post
     * @param string $uri POST URI
     * @param string $className The class of entry to be returned.
     * @return Zend_Gdata_App_Entry The entry returned by the service after insertion.
     */
    public function insertEntry($data, $uri, $className='Zend_Gdata_App_Entry')
    {
        $response = $this->post($data, $uri);

        $returnEntry = new $className($response->getBody());
        $returnEntry->setHttpClient(self::getstaticHttpClient());
        return $returnEntry;
    }

    /**
     * Update an entry
     *
     * TODO Determine if App should call Entry to Update or the opposite.
     * Suspecect opposite would mkae more sense.  Also, this possibly should
     * take an optional URL to override URL used in the entry, or if an
     * edit URI/ID is not present in the entry
     *
     * @param mixed $data Zend_Gdata_App_Entry or XML (w/ID and link rel='edit')
     * @return Zend_Gdata_App_Entry The entry returned from the server
     * @throws Zend_Gdata_App_Exception
     */
    public function updateEntry($data, $uri = null, $className = null)
    {
        // [FIXME] This will blow up if $data is not an instance of
        //         Zend_Gdata_App_Entry and $className is null.
        //         --tjohns 2007-08-24
        if ($className === null && $data instanceof Zend_Gdata_App_Entry) {
            $className = get_class($data);
        } elseif ($className === null) {
            $className = 'Zend_Gdata_App_Entry';
        }
        
        $response = $this->put($data, $uri);
        $returnEntry = new $className($response->getBody());
        $returnEntry->setHttpClient(self::getstaticHttpClient());
        return $returnEntry;
    }

    /**
     * Provides a magic factory method to instantiate new objects with
     * shorter syntax than would otherwise be required by the Zend Framework
     * naming conventions.  For instance, to construct a new
     * Zend_Gdata_Calendar_Extension_Color, a developer simply needs to do
     * $gCal->newColor().  For this magic constructor, packages are searched
     * in the same order as which they appear in the $_registeredPackages
     * array
     *
     * @param string $method The method name being called
     * @param array $args The arguments passed to the call
     * @throws Zend_Gdata_App_Exception
     */
    public function __call($method, $args)
    {
        if (preg_match('/^new(\w+)/', $method, $matches)) {
            $class = $matches[1];
            $foundClassName = null;
            foreach ($this->_registeredPackages as $name) {
                 try {
                     @Zend_Loader::loadClass("${name}_${class}");
                     $foundClassName = "${name}_${class}";
                     break;
                 } catch (Zend_Exception $e) {
                     // package wasn't here- continue searching
                 }
            }
            if ($foundClassName != null) {
                $reflectionObj = new ReflectionClass($foundClassName);
                return $reflectionObj->newInstanceArgs($args);
            } else {
                require_once 'Zend/Gdata/App/Exception.php';
                throw new Zend_Gdata_App_Exception(
                        "Unable to find '${class}' in registered packages");
            }
        } else {
            require_once 'Zend/Gdata/App/Exception.php';
            throw new Zend_Gdata_App_Exception("No such method ${method}");
        }
    }

    /**
     * Retrieve all entries for a feed, iterating through pages as necessary.
     * Be aware that calling this function on a large dataset will take a 
     * significant amount of time to complete. In some cases this may cause 
     * execution to timeout without proper precautions in place.
     *
     * @param $feed The feed to iterate through.
     * @return mixed A new feed of the same type as the one originally 
     *          passed in, containing all relevent entries.
     */
    public function retrieveAllEntriesForFeed($feed) {
        $feedClass = get_class($feed);
        $reflectionObj = new ReflectionClass($feedClass);
        $result = $reflectionObj->newInstance();
        do {
            foreach ($feed as $entry) {
                $result->addEntry($entry);
            }
            
            $next = $feed->getLink('next');
            if ($next !== null) {
                $feed = $this->getFeed($next->href, $feedClass);
            } else {
                $feed = null;
            }
        }
        while ($feed != null);
        return $result;
    }

    /**
     * This method enables logging of requests by changing the 
     * Zend_Http_Client_Adapter used for performing the requests.  
     * NOTE: This will not work if you have customized the adapter
     * already to use a proxy server or other interface.
     * 
     * @param $logfile The logfile to use when logging the requests
     */
    public function enableRequestDebugLogging($logfile) 
    {
        $this->_httpClient->setConfig(array(
            'adapter' => 'Zend_Gdata_App_LoggingHttpClientAdapterSocket',
            'logfile' => $logfile
            ));
    }
}
