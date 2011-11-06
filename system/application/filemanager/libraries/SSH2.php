<?php
// ensure this file is being included by a parent file
if( ! defined( '_JEXEC' ) && ! defined( '_VALID_MOS' ) )
	die( 'Restricted access' ) ;
/**
 * @version $Id: init.php 143 2009-04-01 18:48:16Z soeren $
 * @package eXtplorer
 * @copyright soeren 2009
 * @author bas weermann (http://www.php.net/manual/de/function.ssh2-sftp.php#83174)
 * 
 * @license
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 * 
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 * 
 * Alternatively, the contents of this file may be used under the terms
 * of the GNU General Public License Version 2 or later (the "GPL"), in
 * which case the provisions of the GPL are applicable instead of
 * those above. If you wish to allow use of your version of this file only
 * under the terms of the GPL and not to allow others to use
 * your version of this file under the MPL, indicate your decision by
 * deleting  the provisions above and replace  them with the notice and
 * other provisions required by the GPL.  If you do not delete
 * the provisions above, a recipient may use your version of this file
 * under either the MPL or the GPL."
 * 
 * This file handles SFTP (ssh2) connections
 */
require_once dirname( __FILE__ ) . '/PEAR.php' ;

class SFTPConnection extends PEAR {
	var $connection ;
	var $sftp ;
	var $pwd = '';
	
	function connect( $host, $port = 22 ) {
		$this->connection = @ssh2_connect( $host, $port ) ;
		if( ! $this->connection ) {
			return $this->raiseError( "Could not connect to $host on port $port." ) ;
		}
	}
	
	function login( $username, $password ) {
		$auth_methods = ssh2_auth_none($this->connection, $username);
		if (!in_array('password', $auth_methods)) {
		  	return $this->raiseError( "The SSH Server does not support password based authentication" );
		}
		if( !@ssh2_auth_password( $this->connection, $username, $password ) ) {
			return $this->raiseError( "Could not authenticate with username $username " . "(using a password)." ) ;
		}

		$this->sftp = @ssh2_sftp( $this->connection ) ;
		if( ! $this->sftp ) {
			return $this->raiseError( "Could not initialize SFTP subsystem." ) ;
		}
	}
	
	function put( $local_file, $remote_file ) {
		$sftp = $this->sftp ;
		$stream = @fopen( "ssh2.sftp://$sftp$remote_file", 'w' ) ;
		if( ! $stream ) {
			return $this->raiseError( "Could not open file: $remote_file" ) ;
		}
		$data_to_send = @file_get_contents( $local_file ) ;
		if( $data_to_send === false ) {
			return $this->raiseError( "Could not open local file: $local_file." ) ;
		}
		if( @fwrite( $stream, $data_to_send ) === false ) {
			return $this->raiseError( "Could not send data from file: $local_file." ) ;
		}
		@fclose( $stream ) ;
	}
	/**
	 * returns the current working dir
	 *
	 * @return string
	 */
	function pwd() {
		return $this->pwd;
	}
	function is_readable($file) {
		$sftp = $this->sftp ;
		$pwd = $this->pwd();
		$file =$file['name']; 
		
		return is_readable("ssh2.sftp://$sftp$pwd/$file");
	}
	/**
	 * Returns the directory contents of the directory specified by $remote_file
	 *
	 * @param string $remote_file
	 * @return array
	 */
	function ls( $remote_file ) {
		$sftp = $this->sftp ;
		if( empty($remote_file) || $remote_file == '.') {
			$remote_file = '/';
		}
		
		$this->pwd = $remote_file;
		$dir = "ssh2.sftp://$sftp$remote_file" ;
		$tempArray = array() ;
		$handle = @opendir( $dir ) ;
		if( !$handle) return $tempArray;
		// List all the files
		while( false !== ($file =  readdir( $handle )) ) {
			if( substr( "$file", 0, 1 ) != "." ) {
				if( is_dir( $file ) ) {
					//                $tempArray[$file] = $this->ls("$dir/$file");
				} else {
					$info = stat($dir.'/'.$file);
					$tempArray[] = array_merge( $info, 
																	array('name' => $file,
																				'is_dir' => (string)is_dir($dir.'/'.$file))
																	) ;
				}
			}
		}
		closedir( $handle ) ;
		
		return $tempArray ;
	}
	function file_get_contents($remote_file) {
		$sftp = $this->sftp ;
		return file_get_contents("ssh2.sftp://$sftp".str_replace('\\','/',$remote_file));
	}
	function file_put_contents($remote_file, $data) {
		$sftp = $this->sftp ;
		return file_put_contents("ssh2.sftp://$sftp".str_replace('\\','/',$remote_file), $data);
	}
	function get( $remote_file, $local_file ) {
		$sftp = $this->sftp ;
		$stream = @fopen( "ssh2.sftp://$sftp$remote_file", 'rb' ) ;
		if( !is_resource($stream) ) {
			return $this->raiseError( "Could not open file: $remote_file" ) ;
		}
		
    	stream_set_blocking($stream, FALSE );
    	stream_set_timeout($stream, 10);
    	$info = stream_get_meta_data($stream);
		while (!feof($stream) && !$info['timed_out'] ) {
			$content = fread($stream, 4096);
			if( empty( $content )) break;
		 	file_put_contents( $local_file, $content, FILE_APPEND);
		 	$info = stream_get_meta_data($stream);
		}
		
		@fclose( $stream ) ;
	}
	function fget($remote_file, $local_file ) {
		return $this->get($remote_file, $local_file) ;
	}
	
	function rm( $remote_file ) {
		$sftp = $this->sftp ;
		unlink( "ssh2.sftp://$sftp$remote_file" ) ;
	}
	function mkdir($dir, $recursive=false) {
		
		$sftp = $this->sftp ;
		mkdir( "ssh2.sftp://$sftp$dir" ) ;
	}
	function rename( $from_filename, $to_filename ) {
		$sftp = $this->sftp ;
		rename( "ssh2.sftp://$sftp$from_filename", "ssh2.sftp://$sftp$to_filename" ) ;
	}
	function size($remote_file) {
			$sftp = $this->sftp ;
			$info = stat( "ssh2.sftp://$sftp$remote_file" ) ;
			if( $info ) {
				return $info[7]; // this array index holds the size
			}
			return 0;
	}
	
	function chmod($file, $mode) {
		ssh2_exec ( $this->connection, 'chmod '.(int)$mode.' ' . $file );
	}
	function chmodRecursive($file, $mode) {
		ssh2_exec ( $this->connection, 'chmod -R '.(int)$mode.' ' . $file );
	}
	
	function cd($dir) {	}
	function disconnect() {}
}