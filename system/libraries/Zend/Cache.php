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
 * @package    Zend_Cache
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Cache.php 9198 2008-04-11 15:41:15Z darby $
 */


/**
 * @package    Zend_Cache
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class Zend_Cache
{

    /**
     * Standard frontends
     *
     * @var array
     */
    public static $standardFrontends = array('Core', 'Output', 'Class', 'File', 'Function', 'Page');

    /**
     * Standard backends
     *
     * @var array
     */
    public static $standardBackends = array('File', 'Sqlite', 'Memcached', 'Apc', 'ZendPlatform');

    /**
     * Only for backward compatibily (may be removed in next major release)
     *
     * @var array
     * @deprecated
     */
    public static $availableFrontends = array('Core', 'Output', 'Class', 'File', 'Function', 'Page');

    /**
     * Only for backward compatibily (may be removed in next major release)
     *
     * @var array
     * @deprecated
     */
    public static $availableBackends = array('File', 'Sqlite', 'Memcached', 'Apc', 'ZendPlatform');

    /**
     * Consts for clean() method
     */
    const CLEANING_MODE_ALL              = 'all';
    const CLEANING_MODE_OLD              = 'old';
    const CLEANING_MODE_MATCHING_TAG     = 'matchingTag';
    const CLEANING_MODE_NOT_MATCHING_TAG = 'notMatchingTag';

    /**
     * Factory
     *
     * @param string $frontend        frontend name
     * @param string $backend         backend name
     * @param array  $frontendOptions associative array of options for the corresponding frontend constructor
     * @param array  $backendOptions  associative array of options for the corresponding backend constructor
     * @throws Zend_Cache_Exception
     * @return Zend_Cache_Frontend
     */
    public static function factory($frontend, $backend, $frontendOptions = array(), $backendOptions = array())
    {

        // because lowercase will fail
        $frontend = self::_normalizeName($frontend);
        $backend  = self::_normalizeName($backend);

        // working on the frontend
        if (in_array($frontend, self::$availableFrontends)) {
            // we use a standard frontend
            // For perfs reasons, with frontend == 'Core', we can interact with the Core itself
            $frontendClass = 'Zend_Cache_' . ($frontend != 'Core' ? 'Frontend_' : '') . $frontend;
            // For perfs reasons, we do not use the Zend_Loader::loadClass() method
            // (security controls are explicit)
            require_once str_replace('_', DIRECTORY_SEPARATOR, $frontendClass) . '.php';
        } else {
            // we use a custom frontend
            $frontendClass = 'Zend_Cache_Frontend_' . $frontend;
            // To avoid security problems in this case, we use Zend_Loader to load the custom class
            require_once 'Zend/Loader.php';
            $file = str_replace('_', DIRECTORY_SEPARATOR, $frontendClass) . '.php';
            if (!(Zend_Loader::isReadable($file))) {
                self::throwException("file $file not found in include_path");
            }
            Zend_Loader::loadClass($frontendClass);
        }

        // working on the backend
        if (in_array($backend, Zend_Cache::$availableBackends)) {
            // we use a standard backend
            $backendClass = 'Zend_Cache_Backend_' . $backend;
            // For perfs reasons, we do not use the Zend_Loader::loadClass() method
            // (security controls are explicit)
            require_once str_replace('_', DIRECTORY_SEPARATOR, $backendClass) . '.php';
        } else {
            // we use a custom backend
            $backendClass = 'Zend_Cache_Backend_' . $backend;
            // To avoid security problems in this case, we use Zend_Loader to load the custom class
            require_once 'Zend/Loader.php';
            $file = str_replace('_', DIRECTORY_SEPARATOR, $backendClass) . '.php';
            if (!(Zend_Loader::isReadable($file))) {
                self::throwException("file $file not found in include_path");
            }
            Zend_Loader::loadClass($backendClass);
        }

        // Making objects
        $frontendObject = new $frontendClass($frontendOptions);
        $backendObject = new $backendClass($backendOptions);
        $frontendObject->setBackend($backendObject);
        return $frontendObject;

    }

    /**
     * Throw an exception
     *
     * Note : for perf reasons, the "load" of Zend/Cache/Exception is dynamic
     * @param  string $msg  Message for the exception
     * @throws Zend_Cache_Exception
     */
    public static function throwException($msg)
    {
        // For perfs reasons, we use this dynamic inclusion
        require_once 'Zend/Cache/Exception.php';
        throw new Zend_Cache_Exception($msg);
    }

    /**
     * Normalize frontend and backend names to allow multiple words TitleCased
     *
     * @param  string $name  Name to normalize
     * @return string
     */
    protected static function _normalizeName($name)
    {
        $name = ucfirst(strtolower($name));
        $name = str_replace(array('-', '_', '.'), ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);
        return $name;
    }

}
