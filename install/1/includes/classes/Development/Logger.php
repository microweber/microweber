<?php
/**
 * Logging Class. This can be used to log debug message to an external text file. By default, the text file is [Project Folder]/Logs/Development.log file.
 * Example: $Logger = new $Logger;
 * 			$Logger->log("My Debug Message");
 */
class Logger {
	private $log_file = '';
	private $handle = false;
	
	/**
	 * Constructor
	 * Argument: $log_file - The file to which all the log message must be saved to.
	 */
	function Logger($log_file = '') {
		global $config;
		
		$folder = joinPath($config['site_folder'],'Logs');
		if(!$log_file) { //Log file not specifed - use default.
			if(file_exists($folder)) $log_file = joinPath($folder, 'Development.log');
		
		} else { //Use user specified log file
			if(file_exists($folder)) $log_file = joinPath($folder, $log_file);
		}
		
		$this->log_file = $log_file;
		
		if($this->log_file and is_writable($folder)) {
			$this->handle = fopen($this->log_file, 'a');
		} 
		
		if(!$this->handle) print "Cannot enable logging: Log File '{$this->log_file}' not writable";
	}
	
	/**
	 * Append the log to the log file.
	 * Argument: $message - The text that should be logged.
	 */
	function log($message) {
		if($this->handle)
			fwrite($this->handle, $message . "\n");
	}
	
	/**
	 * Close the file handle. You will not be able to write anything after this function is called.
	 */
	function close() {
		if($this->handle) fclose($this->handle);
	}
}
