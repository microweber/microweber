<?php
/**
 * PhpThumb Library Definition File
 * 
 * This file contains the definitions for the PhpThumb class.
 * 
 * PHP Version 5 with GD 2.0+
 * PhpThumb : PHP Thumb Library <http://phpthumb.gxdlabs.com>
 * Copyright (c) 2009, Ian Selby/Gen X Design
 * 
 * Author(s): Ian Selby <ian@gen-x-design.com>
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @author Ian Selby <ian@gen-x-design.com>
 * @copyright Copyright (c) 2009 Gen X Design
 * @link http://phpthumb.gxdlabs.com
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @version 3.0
 * @package PhpThumb
 * @filesource
 */



/**
 * PhpThumb Object
 * 
 * This singleton object is essentially a function library that helps with core validation 
 * and loading of the core classes and plugins.  There isn't really any need to access it directly, 
 * unless you're developing a plugin and need to take advantage of any of the functionality contained 
 * within.
 * 
 * If you're not familiar with singleton patterns, here's how you get an instance of this class (since you 
 * can't create one via the new keyword):
 * <code>$pt = PhpThumb::getInstance();</code>
 * 
 * It's that simple!  Outside of that, there's no need to modify anything within this class, unless you're doing 
 * some crazy customization... then knock yourself out! :)
 * 
 * @package PhpThumb
 * @subpackage Core
 */
class PhpThumb
{
	/**
	 * Instance of self
	 * 
	 * @var object PhpThumb
	 */
	protected static $_instance;
	/**
	 * The plugin registry
	 * 
	 * This is where all plugins to be loaded are stored.  Data about the plugin is 
	 * provided, and currently consists of:
	 *  - loaded: true/false
	 *  - implementation: gd/imagick/both
	 * 
	 * @var array
	 */
	protected $_registry;
	/**
	 * What implementations are available
	 * 
	 * This stores what implementations are available based on the loaded 
	 * extensions in PHP, NOT whether or not the class files are present.
	 * 
	 * @var array
	 */
	protected $_implementations;
	
	/**
	 * Returns an instance of self
	 * 
	 * This is the usual singleton function that returns / instantiates the object
	 * 
	 * @return PhpThumb
	 */
	public static function getInstance ()
	{
		if(!(self::$_instance instanceof self))
		{
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	
	/**
	 * Class constructor
	 * 
	 * Initializes all the variables, and does some preliminary validation / checking of stuff
	 * 
	 */
	private function __construct ()
	{
		$this->_registry		= array();
		$this->_implementations	= array('gd' => false, 'imagick' => false);
		
		$this->getImplementations();
	}
	
	/**
	 * Finds out what implementations are available
	 * 
	 * This function loops over $this->_implementations and validates that the required extensions are loaded.
	 * 
	 * I had planned on attempting to load them dynamically via dl(), but that would provide more overhead than I 
	 * was comfortable with (and would probably fail 99% of the time anyway)
	 * 
	 */
	private function getImplementations ()
	{
		foreach($this->_implementations as $extension => $loaded)
		{
			if($loaded)
			{
				continue;
			}
			
			if(extension_loaded($extension))
			{
				$this->_implementations[$extension] = true;
			}
		}
	}
	
	/**
	 * Returns whether or not $implementation is valid (available)
	 * 
	 * If 'all' is passed, true is only returned if ALL implementations are available.
	 * 
	 * You can also pass 'n/a', which always returns true
	 * 
	 * @return bool 
	 * @param string $implementation
	 */
	public function isValidImplementation ($implementation)
	{
		if ($implementation == 'n/a')
		{
			return true;
		}
		
		if ($implementation == 'all')
		{
			foreach ($this->_implementations as $imp => $value)
			{
				if ($value == false)
				{
					return false;
				}
			}
			
			return true;
		}
		
		if (array_key_exists($implementation, $this->_implementations))
		{
			return $this->_implementations[$implementation];
		}
		
		return false;
	}
	
	/**
	 * Registers a plugin in the registry
	 * 
	 * Adds a plugin to the registry if it isn't already loaded, and if the provided 
	 * implementation is valid.  Note that you can pass the following special keywords 
	 * for implementation:
	 *  - all - Requires that all implementations be available
	 *  - n/a - Doesn't require any implementation
	 *  
	 * When a plugin is added to the registry, it's added as a key on $this->_registry with the value 
	 * being an array containing the following keys:
	 *  - loaded - whether or not the plugin has been "loaded" into the core class
	 *  - implementation - what implementation this plugin is valid for
	 * 
	 * @return bool
	 * @param string $pluginName
	 * @param string $implementation
	 */
	public function registerPlugin ($pluginName, $implementation)
	{
		if (!array_key_exists($pluginName, $this->_registry) && $this->isValidImplementation($implementation))
		{
			$this->_registry[$pluginName] = array('loaded' => false, 'implementation' => $implementation);
			return true;
		}
		
		return false;
	}
	
	/**
	 * Loads all the plugins in $pluginPath
	 * 
	 * All this function does is include all files inside the $pluginPath directory.  The plugins themselves 
	 * will not be added to the registry unless you've properly added the code to do so inside your plugin file.
	 * 
	 * @param string $pluginPath
	 */
	public function loadPlugins ($pluginPath)
	{
		// strip the trailing slash if present
		if (substr($pluginPath, strlen($pluginPath) - 1, 1) == '/')
		{
			$pluginPath = substr($pluginPath, 0, strlen($pluginPath) - 1);
		}
		
		if ($handle = opendir($pluginPath))
		{
			while (false !== ($file = readdir($handle)))
			{
				if ($file == '.' || $file == '..' || $file == '.svn')
				{
					continue;
				}
				
				include_once($pluginPath . '/' . $file);
			}
		}
	}
	
	/**
	 * Returns the plugin registry for the supplied implementation
	 * 
	 * @return array
	 * @param string $implementation
	 */
	public function getPluginRegistry ($implementation)
	{
		$returnArray = array();
		
		foreach ($this->_registry as $plugin => $meta)
		{
			if ($meta['implementation'] == 'n/a' || $meta['implementation'] == $implementation)
			{
				$returnArray[$plugin] = $meta;
			}
		}
		
		return $returnArray;
	}
}
