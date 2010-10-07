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
 * @package    Zend_Service
 * @subpackage Audioscrobbler
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Audioscrobbler.php 9125 2008-04-03 21:22:31Z thomas $
 */


/**
 * @see Zend_Http_Client
 */
require_once 'Zend/Http/Client.php';


/**
 * @category   Zend
 * @package    Zend_Service
 * @subpackage Audioscrobbler
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Service_Audioscrobbler
{
    /**
     * Zend_Http_Client Object
     *
     * @var     Zend_Http_Client
     * @access  protected
     */
    protected $_client;

    /**
     * Array that contains parameters being used by the webservice
     *
     * @var     array
     * @access  protected
     */
    protected $_params;

    /**
     * Flag if we're doing testing or not
     *
     * @var     boolean
     * @access  protected
     */
    protected $_testing;

    /**
     * Http response used for testing purposes
     *
     * @var     string
     * @access  protected
     */
    protected $_testingResponse;

    /**
     * Holds error information (e.g., for handling simplexml_load_string() warnings)
     *
     * @var     array
     * @access  protected
     */
    protected $_error = null;


    //////////////////////////////////////////////////////////
    ///////////////////  CORE METHODS  ///////////////////////
    //////////////////////////////////////////////////////////


    /**
     * Sets up character encoding, instantiates the HTTP client, and assigns the web service version
     * and testing parameters (if provided).
     *
     * @param  boolean $testing
     * @param  string  $testingResponse
     * @return void
     */
    public function __construct($testing = false, $testingResponse = null)
    {
        $this->set('version', '1.0');

        iconv_set_encoding('output_encoding', 'UTF-8');
        iconv_set_encoding('input_encoding', 'UTF-8');
        iconv_set_encoding('internal_encoding', 'UTF-8');

        $this->_client = new Zend_Http_Client();

        $this->_testing          = (boolean) $testing;
        $this->_testingResponse  = (string) $testingResponse;
    }


    /**
     * Returns a field value, or false if the named field does not exist
     *
     * @param  string $field
     * @return string|false
     */
    public function get($field)
    {
        if (array_key_exists($field, $this->_params)) {
            return $this->_params[$field];
        } else {
            return false;
        }
    }

    /**
     * Generic set action for a field in the parameters being used
     *
     * @param  string $field name of field to set
     * @param  string $value value to assign to the named field
     * @return Zend_Service_Audioscrobbler Provides a fluent interface
     */
    public function set($field, $value)
    {
        $this->_params[$field] = urlencode($value);

        return $this;
    }

    /**
     * Protected method that queries REST service and returns SimpleXML response set
     *
     * @param  string $service name of Audioscrobbler service file we're accessing
     * @param  string $params  parameters that we send to the service if needded
     * @throws Zend_Http_Client_Exception
     * @throws Zend_Service_Exception
     * @return SimpleXMLElement result set
     * @access protected
     */
    protected function _getInfo($service, $params = null)
    {
        $service = (string) $service;
        $params  = (string) $params;

        if ($params === '') {
            $this->_client->setUri("http://ws.audioscrobbler.com{$service}");
        } else {
            $this->_client->setUri("http://ws.audioscrobbler.com{$service}?{$params}");
        }

        if ($this->_testing) {
            /**
             * @see Zend_Http_Client_Adapter_Test
             */
            require_once 'Zend/Http/Client/Adapter/Test.php';
            $adapter = new Zend_Http_Client_Adapter_Test();

            $this->_client->setConfig(array('adapter' => $adapter));

            $adapter->setResponse($this->_testingResponse);
        }

        $response     = $this->_client->request();
        $responseBody = $response->getBody();

        if (preg_match('/No such path/', $responseBody)) {
            /**
             * @see Zend_Http_Client_Exception
             */
            require_once 'Zend/Http/Client/Exception.php';
            throw new Zend_Http_Client_Exception('Could not find: ' . $this->_client->getUri());
        } elseif (preg_match('/No user exists with this name/', $responseBody)) {
            /**
             * @see Zend_Http_Client_Exception
             */
            require_once 'Zend/Http/Client/Exception.php';
            throw new Zend_Http_Client_Exception('No user exists with this name');
        } elseif (!$response->isSuccessful()) {
            /**
             * @see Zend_Http_Client_Exception
             */
            require_once 'Zend/Http/Client/Exception.php';
            throw new Zend_Http_Client_Exception('The web service ' . $this->_client->getUri() . ' returned the following status code: ' . $response->getStatus());
        }

        set_error_handler(array($this, '_errorHandler'));

        if (!$simpleXmlElementResponse = simplexml_load_string($responseBody)) {
            restore_error_handler();
            /**
             * @see Zend_Service_Exception
             */
            require_once 'Zend/Service/Exception.php';
            $exception = new Zend_Service_Exception('Response failed to load with SimpleXML');
            $exception->error    = $this->_error;
            $exception->response = $responseBody;
            throw $exception;
        }

        restore_error_handler();

        return $simpleXmlElementResponse;
    }

    //////////////////////////////////////////////////////////
    ///////////////////////  USER  ///////////////////////////
    //////////////////////////////////////////////////////////

    /**
    * Utility function to get Audioscrobbler profile information (eg: Name, Gender)
    * @return array containing information
    */
    public function userGetProfileInformation()
    {
        $service = "/{$this->get('version')}/user/{$this->get('user')}/profile.xml";
        return $this->_getInfo($service);
    }

    /**
     * Utility function get this user's 50 most played artists
     * @return array containing info
    */
    public function userGetTopArtists()
    {
        $service = "/{$this->get('version')}/user/{$this->get('user')}/topartists.xml";
        return $this->_getInfo($service);
    }

    /**
     * Utility function to get this user's 50 most played albums
     * @return SimpleXML object containing result set
    */
    public function userGetTopAlbums()
    {
        $service = "/{$this->get('version')}/user/{$this->get('user')}/topalbums.xml";
        return $this->_getInfo($service);
    }

    /**
     * Utility function to get this user's 50 most played tracks
     * @return SimpleXML object containing resut set
    */
    public function userGetTopTracks()
    {
        $service = "/{$this->get('version')}/user/{$this->get('user')}/toptracks.xml";
        return $this->_getInfo($service);
    }

    /**
     * Utility function to get this user's 50 most used tags
     * @return SimpleXML object containing result set
     */
    public function userGetTopTags()
    {
        $service = "/{$this->get('version')}/user/{$this->get('user')}/tags.xml";
        return $this->_getInfo($service);
    }

    /**
     * Utility function that returns the user's top tags used most used on a specific artist
     * @return SimpleXML object containing result set
     *
     */
    public function userGetTopTagsForArtist()
    {
        $service = "/{$this->get('version')}/user/{$this->get('user')}/artisttags.xml";
        $params = "artist={$this->get('artist')}";
        return $this->_getInfo($service, $params);
    }

    /**
     * Utility function that returns this user's top tags for an album
     * @return SimpleXML object containing result set
     *
     */
    public function userGetTopTagsForAlbum()
    {
        $service = "/{$this->get('version')}/user/{$this->get('user')}/albumtags.xml";
        $params = "artist={$this->get('artist')}&album={$this->get('album')}";
        return $this->_getInfo($service, $params);
    }

    /**
     * Utility function that returns this user's top tags for a track
     * @return SimpleXML object containing result set
     *
     */
    public function userGetTopTagsForTrack()
    {
        $service = "/{$this->get('version')}/user/{$this->get('user')}/tracktags.xml";
        $params = "artist={$this->get('artist')}&track={$this->get('track')}";
        return $this->_getInfo($service, $params);
    }

    /**
     * Utility function that retrieves this user's list of friends
     * @return SimpleXML object containing result set
     */
    public function userGetFriends()
    {
        $service = "/{$this->get('version')}/user/{$this->get('user')}/friends.xml";
        return $this->_getInfo($service);
    }

    /**
     * Utility function that returns a list of people with similar listening preferences to this user
     * @return SimpleXML object containing result set
     *
     */
    public function userGetNeighbours()
    {
        $service = "/{$this->get('version')}/user/{$this->get('user')}/neighbours.xml";
        return $this->_getInfo($service);
    }

    /**
     * Utility function that returns a list of the 10 most recent tracks played by this user
     * @return SimpleXML object containing result set
     *
     */
    public function userGetRecentTracks()
    {
        $service = "/{$this->get('version')}/user/{$this->get('user')}/recenttracks.xml";
        return $this->_getInfo($service);
    }

    /**
     * Utility function that returns a list of the 10 tracks most recently banned by this user
     * @return SimpleXML object containing result set
     *
     */
    public function userGetRecentBannedTracks()
    {
        $service = "/{$this->get('version')}/user/{$this->get('user')}/recentbannedtracks.xml";
        return $this->_getInfo($service);
    }

    /**
     * Utility function that returns a list of the 10 tracks most recently loved by this user
     * @return SimpleXML object containing result set
     *
     */
    public function userGetRecentLovedTracks()
    {
        $service = "/{$this->get('version')}/user/{$this->get('user')}/recentlovedtracks.xml";
        return $this->_getInfo($service);
    }

    /**
     * Utility function that returns a list of dates of available weekly charts for a this user
     * Should actually be named userGetWeeklyChartDateList() but we have to follow audioscrobbler's naming
     * @return SimpleXML object containing result set
     *
     */
    public function userGetWeeklyChartList()
    {
        $service = "/{$this->get('version')}/user/{$this->get('user')}/weeklychartlist.xml";
        return $this->_getInfo($service);
    }


    /**
     * Utility function that returns weekly album chart data for this user
     * @return SimpleXML object containing result set
     *
     * @param integer $from optional UNIX timestamp for start of date range
     * @param integer $to optional UNIX timestamp for end of date range
     */
    public function userGetWeeklyAlbumChart($from = NULL, $to = NULL)
    {
        $params = "";

        if ($from != NULL && $to != NULL) {
            $from = (int)$from;
            $to = (int)$to;
            $params = "from={$from}&to={$to}";
        }

        $service = "/{$this->get('version')}/user/{$this->get('user')}/weeklyalbumchart.xml";
        return $this->_getInfo($service, $params);
    }

    /**
     * Utility function that returns weekly artist chart data for this user
     * @return SimpleXML object containing result set
     *
     * @param integer $from optional UNIX timestamp for start of date range
     * @param integer $to optional UNIX timestamp for end of date range
     */
    public function userGetWeeklyArtistChart($from = NULL, $to = NULL)
    {
        $params = "";

        if ($from != NULL && $to != NULL) {
            $from = (int)$from;
            $to = (int)$to;
            $params = "from={$from}&to={$to}";
        }

        $service = "/{$this->get('version')}/user/{$this->get('user')}/weeklyartistchart.xml";
        return $this->_getInfo($service, $params);
    }

    /**
     * Utility function that returns weekly track chart data for this user
     * @return SimpleXML object containing result set
     *
     * @param integer $from optional UNIX timestamp for start of date range
     * @param integer $to optional UNIX timestamp for end of date range
     */
    public function userGetWeeklyTrackChart($from = NULL, $to = NULL)
    {
        $params = "";

        if ($from != NULL && $to != NULL) {
            $from = (int)$from;
            $to = (int)$to;
            $params = "from={$from}&to={$to}";
        }

        $service = "/{$this->get('version')}/user/{$this->get('user')}/weeklytrackchart.xml";
        return $this->_getInfo($service, $params);
    }


    //////////////////////////////////////////////////////////
    ///////////////////////  ARTIST  /////////////////////////
    //////////////////////////////////////////////////////////

    /**
     * Public functions for retrieveing artist-specific information
     *
     */


    /**
     * Utility function that returns a list of artists similiar to this artist
     * @return SimpleXML object containing result set
     *
     */
    public function artistGetRelatedArtists()
    {
        $service = "/{$this->get('version')}/artist/{$this->get('artist')}/similar.xml";
        return $this->_getInfo($service);
    }

    /**
     * Utility function that returns a list of this artist's top listeners
     * @return SimpleXML object containing result set
     *
     */
    public function artistGetTopFans()
    {
        $service = "/{$this->get('version')}/artist/{$this->get('artist')}/fans.xml";
        return $this->_getInfo($service);
    }

    /**
     * Utility function that returns a list of this artist's top-rated tracks
     * @return SimpleXML object containing result set
     *
     */
    public function artistGetTopTracks()
    {
        $service = "/{$this->get('version')}/artist/{$this->get('artist')}/toptracks.xml";
        return $this->_getInfo($service);
    }

    /**
     * Utility function that returns a list of this artist's top-rated albums
     * @return SimpleXML object containing result set
     *
     */
    public function artistGetTopAlbums()
    {
        $service = "/{$this->get('version')}/artist/{$this->get('artist')}/topalbums.xml";
        return $this->_getInfo($service);
    }

    /**
     * Utility function that returns a list of this artist's top-rated tags
     * @return SimpleXML object containing result set
     *
     */
    public function artistGetTopTags()
    {
        $service = "/{$this->get('version')}/artist/{$this->get('artist')}/toptags.xml";
        return $this->_getInfo($service);
    }

    //////////////////////////////////////////////////////////
    ///////////////////////  ALBUM  //////////////////////////
    //////////////////////////////////////////////////////////

    public function albumGetInfo()
    {
        $service = "/{$this->get('version')}/album/{$this->get('artist')}/{$this->get('album')}/info.xml";
        return $this->_getInfo($service);
    }

    //////////////////////////////////////////////////////////
    ///////////////////////  TRACKS //////////////////////////
    //////////////////////////////////////////////////////////

    public function trackGetTopFans()
    {
        $service = "/{$this->get('version')}/track/{$this->get('artist')}/{$this->get('track')}/fans.xml";
        return $this->_getInfo($service);
    }

    public function trackGetTopTags()
    {
        $service = "/{$this->get('version')}/track/{$this->get('artist')}/{$this->get('track')}/toptags.xml";
        return $this->_getInfo($service);
    }

    //////////////////////////////////////////////////////////
    ///////////////////////  TAGS   //////////////////////////
    //////////////////////////////////////////////////////////

    public function tagGetTopTags()
    {
        $service = "/{$this->get('version')}/tag/toptags.xml";
        return $this->_getInfo($service);
    }

    public function tagGetTopAlbums()
    {
        $service = "/{$this->get('version')}/tag/{$this->get('tag')}/topalbums.xml";
        return $this->_getInfo($service);
    }

    public function tagGetTopArtists()
    {
        $service = "/{$this->get('version')}/tag/{$this->get('tag')}/topartists.xml";
        return $this->_getInfo($service);
    }

    public function tagGetTopTracks()
    {
        $service = "/{$this->get('version')}/tag/{$this->get('tag')}/toptracks.xml";
        return $this->_getInfo($service);
    }

    //////////////////////////////////////////////////////////
    /////////////////////// GROUPS  //////////////////////////
    //////////////////////////////////////////////////////////

    public function groupGetWeeklyChartList()
    {
        $service = "/{$this->get('version')}/group/{$this->get('group')}/weeklychartlist.xml";
        return $this->_getInfo($service);
    }

    public function groupGetWeeklyArtistChartList($from = NULL, $to = NULL)
    {

        if ($from != NULL && $to != NULL) {
            $from = (int)$from;
            $to = (int)$to;
            $params = "from={$from}&$to={$to}";
        } else {
            $params = "";
        }

        $service = "/{$this->get('version')}/group/{$this->get('group')}/weeklyartistchart.xml";
        return $this->_getInfo($service, $params);
    }

    public function groupGetWeeklyTrackChartList($from = NULL, $to = NULL)
    {
        if ($from != NULL && $to != NULL) {
            $from = (int)$from;
            $to = (int)$to;
            $params = "from={$from}&to={$to}";
        } else {
            $params = "";
        }

        $service = "/{$this->get('version')}/group/{$this->get('group')}/weeklytrackchart.xml";
        return $this->_getInfo($service, $params);
    }

    public function groupGetWeeklyAlbumChartList($from = NULL, $to = NULL)
    {
        if ($from != NULL && $to != NULL) {
            $from = (int)$from;
            $to = (int)$to;
            $params = "from={$from}&to={$to}";
        } else {
            $params = "";
        }

        $service = "/{$this->get('version')}/group/{$this->get('group')}/weeklyalbumchart.xml";
        return $this->_getInfo($service, $params);
    }

    /**
     * Saves the provided error information to this instance
     *
     * @param  integer $errno
     * @param  string  $errstr
     * @param  string  $errfile
     * @param  integer $errline
     * @param  array   $errcontext
     * @return void
     */
    protected function _errorHandler($errno, $errstr, $errfile, $errline, array $errcontext)
    {
        $this->_error = array(
            'errno'      => $errno,
            'errstr'     => $errstr,
            'errfile'    => $errfile,
            'errline'    => $errline,
            'errcontext' => $errcontext
            );
    }
}
