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
 * @package    Zend_Config
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Xml.php 10148 2008-07-16 22:46:07Z doctorrock83 $
 */


/**
 * @see Zend_Config
 */
require_once 'Zend/Config.php';


/**
 * @category   Zend
 * @package    Zend_Config
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Config_Xml extends Zend_Config
{
    /**
     * Loads the section $section from the config file $filename for
     * access facilitated by nested object properties.
     *
     * Sections are defined in the XML as children of the root element.
     *
     * In order to extend another section, a section defines the "extends"
     * attribute having a value of the section name from which the extending
     * section inherits values.
     *
     * Note that the keys in $section will override any keys of the same
     * name in the sections that have been included via "extends".
     *
     * @param  string  $filename
     * @param  mixed   $section
     * @param  boolean $allowModifications
     * @throws Zend_Config_Exception
     * @return void
     */
    public function __construct($filename, $section = null, $allowModifications = false)
    {
        if (empty($filename)) {
            /**
             * @see Zend_Config_Exception
             */
            require_once 'Zend/Config/Exception.php';
            throw new Zend_Config_Exception('Filename is not set');
        }
        $old_error_handler = set_error_handler(array(__CLASS__, 'simplexmlLoadFileFileErrorHandler'));
        $config = simplexml_load_file($filename);
        restore_error_handler();

        if (null === $section) {
            $dataArray = array();
            foreach ($config as $sectionName => $sectionData) {
                $dataArray[$sectionName] = $this->_processExtends($config, $sectionName);
            }
            parent::__construct($dataArray, $allowModifications);
        } elseif (is_array($section)) {
            $dataArray = array();
            foreach ($section as $sectionName) {
                if (!isset($config->$sectionName)) {
                    /**
                     * @see Zend_Config_Exception
                     */
                    require_once 'Zend/Config/Exception.php';
                    throw new Zend_Config_Exception("Section '$sectionName' cannot be found in $filename");
                }
                $dataArray = array_merge($this->_processExtends($config, $sectionName), $dataArray);
            }
            parent::__construct($dataArray, $allowModifications);
        } else {
            if (!isset($config->$section)) {
                /**
                 * @see Zend_Config_Exception
                 */
                require_once 'Zend/Config/Exception.php';
                throw new Zend_Config_Exception("Section '$section' cannot be found in $filename");
            }
            $dataArray = $this->_processExtends($config, $section);
            if(!is_array($dataArray)) {
                // section in the XML file contains just one top level string
                $dataArray = array($section=>$dataArray);
            }
            parent::__construct($dataArray, $allowModifications);
        }

        $this->_loadedSection = $section;
    }


    /**
     * Helper function to process each element in the section and handle
     * the "extends" inheritance attribute.
     *
     * @param  SimpleXMLElement $element
     * @param  string           $section
     * @param  array            $config
     * @throws Zend_Config_Exception
     * @return array
     */
    protected function _processExtends($element, $section, $config = array())
    {
        if (!$element->$section) {
            /**
             * @see Zend_Config_Exception
             */
            require_once 'Zend/Config/Exception.php';
            throw new Zend_Config_Exception("Section '$section' cannot be found");
        }

        $thisSection = $element->$section;

        if (isset($thisSection['extends'])) {
            $extendedSection = (string) $thisSection['extends'];
            $this->_assertValidExtend($section, $extendedSection);
            $config = $this->_processExtends($element, $extendedSection, $config);
        }

        $config = $this->_arrayMergeRecursive($config, $this->_toArray($thisSection));

        return $config;
    }


    /**
     * Returns a string or an associative and possibly multidimensional array from
     * a SimpleXMLElement.
     *
     * @param  SimpleXMLElement $xmlObject
     * @return array|string
     */
    protected function _toArray($xmlObject)
    {
        
        $config = array();
        if (count($xmlObject->children())) {
            foreach ($xmlObject->children() as $key => $value) {
                if ($value->children()) {
                    $value = $this->_toArray($value);
                } else {
                    $value = (string) $value;
                }
                if (array_key_exists($key, $config)) {
                    if (!is_array($config[$key]) || !array_key_exists(0, $config[$key])) {
                        $config[$key] = array($config[$key]);
                    }
                    $config[$key][] = $value;
                } else {
                    $config[$key] = $value;
                }
            }
        } elseif (!isset($xmlObject['extends'])) {
            // object has no children and doesn't use the extends attribute: it's a string
            $config = (string) $xmlObject;
        }
        return $config;
    }

    /**
     * Merge two arrays recursively, overwriting keys of the same name
     * in $array1 with the value in $array2.
     *
     * @param  array $array1
     * @param  array $array2
     * @return array
     */
    protected function _arrayMergeRecursive($array1, $array2)
    {
        if (is_array($array1) && is_array($array2)) {
            foreach ($array2 as $key => $value) {
                if (isset($array1[$key])) {
                    $array1[$key] = $this->_arrayMergeRecursive($array1[$key], $value);
                } else {
                    $array1[$key] = $value;
                }
            }
        } else {
            $array1 = $array2;
        }
        return $array1;
    }

    /**
     * Handle any errors from simplexml_load_file
     *
     * @param integer $errno
     * @param string $errstr
     * @param string $errfile
     * @param integer $errline
     */
    public static function simplexmlLoadFileFileErrorHandler($errno, $errstr, $errfile, $errline)
    { 
        /**
         * @see Zend_Config_Exception
         */
        require_once 'Zend/Config/Exception.php';
        throw new Zend_Config_Exception($errstr);
    }
}
