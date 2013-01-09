<?php
/**
 * Aggregate autoloader that implements the technical interoperability
 * standards for PHP 5.3 namespaces and class names.
 *
 * http://groups.google.com/group/php-standards/web/final-proposal
 *
 * @author Tomasz Jędrzejewski <http://www.zyxist.com/>
 * @todo Replace 'Exception' with specialized exceptions, when deploying.
 */
class AggregateAutoloader
{
	/**
	 * The list of available libraries.
	 * @var array
	 */
	private $_libraries = array();
	
	/**
	 * The library extensions.
	 * @var array
	 */
	private $_extensions = array();

	/**
	 * The namespace separator
	 * @var string
	 */
	private $_namespaceSeparator = '\\';

	/**
	 * Registers a new library to match.
	 *
	 * @param string $library The library name to add.
	 * @param string $path The path to the library.
	 * @param string $extension The library file extension.
	 */
	public function addLibrary($library, $path, $extension = '.php')
	{
		if(isset($this->_libraries[(string)$library]))
		{
			throw new Exception('Library '.$library.' is already added.');
		}
		if($path[strlen($path) - 1] != '/')
		{
			$path .= '/';
		}
		$this->_libraries[(string)$library] = $path;
		$this->_extensions[(string)$library] = $extension;
	} // end addLibrary();

	/**
	 * Checks if the specified library is available.
	 *
	 * @param string $library The library name to check.
	 */
	public function hasLibrary($library)
	{
		return isset($this->_libraries[(string)$library]);
	} // end hasLibrary();
	
	/**
	 * Removes a recognized library.
	 *
	 * @param string $library The library name to remove.
	 */
	public function removeLibrary($library)
	{
		if(!isset($this->_libraries[(string)$library]))
		{
			throw new Exception('Library '.$library.' is not available.');
		}
		unset($this->_libraries[(string)$library]);
		unset($this->_extensions[(string)$library]);
	} // end removeLibrary();

	/**
	 * Sets the namespace separator used by classes in the namespace of this class loader.
	 * 
	 * @param string $sep The separator to use.
	 */
	public function setNamespaceSeparator($sep)
	{
		$this->_namespaceSeparator = $sep;
	} // end setNamespaceSeparator();

	/**
	 * Gets the namespace seperator used by classes in the namespace of this class loader.
	 *
	 * @return string
	 */
	public function getNamespaceSeparator()
	{
		return $this->_namespaceSeparator;
	} // end getNamespaceSeparator();

	/**
	 * Installs this class loader on the SPL autoload stack.
	 */
	public function register()
	{
		spl_autoload_register(array($this, 'loadClass'));
	} // end register();

	/**
	 * Uninstalls this class loader from the SPL autoloader stack.
	*/
	public function unregister()
	{
		spl_autoload_unregister(array($this, 'loadClass'));
	} // end unregister();
	
	/**
	 * Loads the given class or interface.
	 *
	 * @param string $className The name of the class to load.
	 * @return void
	 */
	public function loadClass($className)
	{
		$className = ltrim($className, $this->_namespaceSeparator);
		$match = strstr($className, $this->_namespaceSeparator, true);
		
		if(false !== $match || !isset($this->_libraries[$match]))
		{
			return false;
		}
		$rest = strrchr($className, $this->_namespaceSeparator);
		$replacement =
			str_replace($this->_namespaceSeparator, '/', substr($className, 0, strlen($className) - strlen($rest))).
			str_replace(array('_', $this->_namespaceSeparator), '/', $rest);

		require($this->_libraries[$match].$replacement.$this->_extensions[$match]);
		return true;
	} // end loadClass();
} // end AggregateAutoloader;

