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
 * Zend_Gdata_YouTube
 */
require_once('Zend/Gdata/YouTube.php');

/**
 * Zend_Gdata_Query
 */
require_once('Zend/Gdata/Query.php');

/**
 * Assists in constructing queries for YouTube videos
 *
 * @link http://code.google.com/apis/youtube/
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Gdata_YouTube_VideoQuery extends Zend_Gdata_Query
{

    /**
     * Create Gdata_YouTube_VideoQuery object
     */
    public function __construct($url = null)
    {   
        parent::__construct($url);
    }

    /**
     * Sets the type of feed this query should be used to search
     *
     * @param string $feedType The type of feed
     * @param string $videoId The ID of the video associated with this query
     * @param string $entry The ID of the entry associated with this query
     */
    public function setFeedType($feedType, $videoId = null, $entry = null)
    {
        switch ($feedType) {
        case 'top rated':
            $this->_url = Zend_Gdata_YouTube::STANDARD_TOP_RATED_URI;
            break;
        case 'most viewed':
            $this->_url = Zend_Gdata_YouTube::STANDARD_MOST_VIEWED_URI;
            break;
        case 'recently featured':
            $this->_url = Zend_Gdata_YouTube::STANDARD_RECENTLY_FEATURED_URI;
            break;
        case 'mobile':
            $this->_url = Zend_Gdata_YouTube::STANDARD_WATCH_ON_MOBILE_URI;
            break;
        case 'related':
            if ($videoId === null) {
                require_once 'Zend/Gdata/App/Exception.php';
                throw new Zend_Gdata_App_Exception('Video ID must be set for feed of type: ' . $feedType);
            } else {
                $this->_url = Zend_Gdata_YouTube::VIDEO_URI . '/' . $videoId . '/related';
            }
            break;
        case 'responses':
            if ($videoId === null) {
                require_once 'Zend/Gdata/App/Exception.php';
                throw new Zend_Gdata_App_Exception('Video ID must be set for feed of type: ' . $feedType);
            } else {
                $this->_url = Zend_Gdata_YouTube::VIDEO_URI . '/' . $videoId . 'responses';
            }
            break;
        case 'comments':
            if ($videoId === null) {
                require_once 'Zend/Gdata/App/Exception.php';
                throw new Zend_Gdata_App_Exception('Video ID must be set for feed of type: ' . $feedType);
            } else {
                $this->_url = Zend_Gdata_YouTube::VIDEO_URI . '/' . $videoId . 'comments';
                if ($entry !== null) {
                    $this->_url .= '/' . $entry;
                }
            }
            break;
        default:
            require_once 'Zend/Gdata/App/Exception.php';
            throw new Zend_Gdata_App_Exception('Unknown feed type');
            break;
        }
    }

    
    /**
     * Sets the time period over which this query should apply
     *
     * @param string $value
     * @return Zend_Gdata_YouTube_VideoQuery Provides a fluent interface
     */
    public function setTime($value = null)
    {
        switch ($value) {
            case 'today':
                $this->_params['time'] = 'today';
                break;
            case 'this_week':
                $this->_params['time'] = 'this_week';
                break;
            case 'this_month':
                $this->_params['time'] = 'this_month';
                break;
            case 'all_time':
                $this->_params['time'] = 'all_time';
                break;
            case null:
                unset($this->_params['time']);
            default:
                require_once 'Zend/Gdata/App/Exception.php';
                throw new Zend_Gdata_App_Exception('Unknown time value');
                break;
        }
        return $this;
    }

    /**
     * Sets the formatted video query (vq) URL param value
     *
     * @param string $value
     * @return Zend_Gdata_YouTube_VideoQuery Provides a fluent interface
     */
    public function setVideoQuery($value = null)
    {
        if ($value != null) {
            $this->_params['vq'] = $value;
        } else {
            unset($this->_params['vq']);
        }
        return $this;
    }

    /**
     * Sets the param to return videos of a specific format
     *
     * @param string $value
     * @return Zend_Gdata_YouTube_VideoQuery Provides a fluent interface
     */
    public function setFormat($value = null)
    {
        if ($value != null) {
            $this->_params['format'] = $value;
        } else {
            unset($this->_params['format']);
        }
        return $this;
    }

    /**
     * Sets whether or not to include racy videos in the search results
     *
     * @param string $value
     * @return Zend_Gdata_YouTube_VideoQuery Provides a fluent interface
     */
    public function setRacy($value = null)
    {
        switch ($value) {
            case 'include':
                $this->_params['racy'] = $value;
                break;
            case 'exclude':
                $this->_params['racy'] = $value;
                break;
            case null:
                unset($this->_params['racy']);
                break;
        }
        return $this;
    }

    /**
     * @param string $value
     * @return Zend_Gdata_YouTube_Query Provides a fluent interface
     */
    public function setOrderBy($value)
    {
        if ($value != null) {
            $this->_params['orderby'] = $value;
        } else {
            unset($this->_params['orderby']);
        }
        return $this;
    }

    /**
     * Whether or not to include racy videos in the search results
     *
     * @return string racy 
     */
    public function getRacy()
    {
        if (array_key_exists('racy', $this->_params)) {
            return $this->_params['racy'];
        } else {
            return null;
        }
    }

    /**
     * The format used for videos
     *
     * @return string format 
     */
    public function getFormat()
    {
        if (array_key_exists('format', $this->_params)) {
            return $this->_params['format'];
        } else {
            return null;
        }
    }

    /**
     * The video query
     *
     * @return string video query
     */
    public function getVideoQuery()
    {
        if (array_key_exists('vq', $this->_params)) {
            return $this->_params['vq'];
        } else {
            return null;
        }
    }

    /**
     * The time
     *
     * @return string time 
     */
    public function getTime()
    {
        if (array_key_exists('time', $this->_params)) {
            return $this->_params['time'];
        } else {
            return null;
        }
    }

    /**
     * @return string orderby
     */
    public function getOrderBy()
    {
        if (array_key_exists('orderby', $this->_params)) {
            return $this->_params['orderby'];
        } else {
            return null;
        }
    }

    /**
     * Returns the generated full query URL
     *
     * @return string The URL
     */
    public function getQueryUrl()
    {
        if (isset($this->_url)) {
            $url = $this->_url;
        } else {
            $url = Zend_Gdata_YouTube::VIDEO_URI;
        }
        if ($this->getCategory() !== null) {
            $url .= '/-/' . $this->getCategory();
        }
        $url = $url . $this->getQueryString();
        return $url;
    }

}
