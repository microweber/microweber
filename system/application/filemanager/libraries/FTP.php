<?php
/** ensure this file is being included by a parent file */
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Net_FTP main file.
 *
 * This file must be included to use the Net_FTP package.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category  Networking
 * @package   FTP
 * @author    Tobias Schlitt <toby@php.net>
 * @author    Jorrit Schippers <jschippers@php.net>
 * @copyright 1997-2008 The PHP Group
 * @license   http://www.php.net/license/3_0.txt PHP License 3.0
 * @version   CVS: $Id: FTP.php 176 2010-11-06 08:44:55Z soeren $
 * @link      http://pear.php.net/package/Net_FTP
 * @since     File available since Release 0.0.1
 */

/**
 * Include PEAR.php to obtain the PEAR base class
 */
require_once 'PEAR.php';

/**
 * Option to let the ls() method return only files.
 *
 * @since 1.3
 * @name NET_FTP_FILES_ONLY
 * @see Net_FTP::ls()
 */
define('NET_FTP_FILES_ONLY', 0, true);

/**
 * Option to let the ls() method return only directories.
 *
 * @since 1.3
 * @name NET_FTP_DIRS_ONLY
 * @see Net_FTP::ls()
 */
define('NET_FTP_DIRS_ONLY', 1, true);

/**
 * Option to let the ls() method return directories and files (default).
 *
 * @since 1.3
 * @name NET_FTP_DIRS_FILES
 * @see Net_FTP::ls()
 */
define('NET_FTP_DIRS_FILES', 2, true);

/**
 * Option to let the ls() method return the raw directory listing from ftp_rawlist()
 *
 * @since 1.3
 * @name NET_FTP_RAWLIST
 * @see Net_FTP::ls()
 */
define('NET_FTP_RAWLIST', 3, true);

/**
 * Error code to indicate a failed connection
 * This error code indicates, that the connection you tryed to set up
 * could not be established. Check your connection settings (host & port)!
 *
 * @since 1.3
 * @name NET_FTP_ERR_CONNECT_FAILED
 * @see Net_FTP::connect()
 */
define('NET_FTP_ERR_CONNECT_FAILED', -1);

/**
 * Error code to indicate a failed login
 * This error code indicates, that the login to the FTP server failed. Check
 * your user data (username & password).
 *
 * @since 1.3
 * @name NET_FTP_ERR_LOGIN_FAILED
 * @see Net_FTP::login()
 */
define('NET_FTP_ERR_LOGIN_FAILED', -2);

/**
 * Error code to indicate a failed directory change
 * The cd() method failed. Ensure that the directory you wanted to access exists.
 *
 * @since 1.3
 * @name NET_FTP_ERR_DIRCHANGE_FAILED
 * @see Net_FTP::cd()
 */
define('NET_FTP_ERR_DIRCHANGE_FAILED', 2); // Compatibillity reasons!

/**
 * Error code to indicate that Net_FTP could not determine the current path
 * The cwd() method failed and could not determine the path you currently reside
 * in on the FTP server.
 *
 * @since 1.3
 * @name NET_FTP_ERR_DETERMINEPATH_FAILED
 * @see Net_FTP::pwd()
 */
define('NET_FTP_ERR_DETERMINEPATH_FAILED', 4); // Compatibillity reasons!

/**
 * Error code to indicate that the creation of a directory failed
 * The directory you tryed to create could not be created. Check the
 * access rights on the parent directory!
 *
 * @since 1.3
 * @name NET_FTP_ERR_CREATEDIR_FAILED
 * @see Net_FTP::mkdir()
 */
define('NET_FTP_ERR_CREATEDIR_FAILED', -4);

/**
 * Error code to indicate that the EXEC execution failed.
 * The execution of a command using EXEC failed. Ensure, that your
 * FTP server supports the EXEC command.
 *
 * @since 1.3
 * @name NET_FTP_ERR_EXEC_FAILED
 * @see Net_FTP::execute()
 */
define('NET_FTP_ERR_EXEC_FAILED', -5);

/**
 * Error code to indicate that the SITE command failed.
 * The execution of a command using SITE failed. Ensure, that your
 * FTP server supports the SITE command.
 *
 * @since 1.3
 * @name NET_FTP_ERR_SITE_FAILED
 * @see Net_FTP::site()
 */
define('NET_FTP_ERR_SITE_FAILED', -6);

/**
 * Error code to indicate that the CHMOD command failed.
 * The execution of CHMOD failed. Ensure, that your
 * FTP server supports the CHMOD command and that you have the appropriate
 * access rights to use CHMOD.
 *
 * @since 1.3
 * @name NET_FTP_ERR_CHMOD_FAILED
 * @see Net_FTP::chmod()
 */
define('NET_FTP_ERR_CHMOD_FAILED', -7);

/**
 * Error code to indicate that a file rename failed
 * The renaming of a file on the server failed. Ensure that you have the
 * appropriate access rights to rename the file.
 *
 * @since 1.3
 * @name NET_FTP_ERR_RENAME_FAILED
 * @see Net_FTP::rename()
 */
define('NET_FTP_ERR_RENAME_FAILED', -8);

/**
 * Error code to indicate that the MDTM command failed
 * The MDTM command is not supported for directories. Ensure that you gave
 * a file path to the mdtm() method, not a directory path.
 *
 * @since 1.3
 * @name NET_FTP_ERR_MDTMDIR_UNSUPPORTED
 * @see Net_FTP::mdtm()
 */
define('NET_FTP_ERR_MDTMDIR_UNSUPPORTED', -9);

/**
 * Error code to indicate that the MDTM command failed
 * The MDTM command failed. Ensure that your server supports the MDTM command.
 *
 * @since 1.3
 * @name NET_FTP_ERR_MDTM_FAILED
 * @see Net_FTP::mdtm()
 */
define('NET_FTP_ERR_MDTM_FAILED', -10);

/**
 * Error code to indicate that a date returned by the server was misformated
 * A date string returned by your server seems to be missformated and could not be
 * parsed. Check that the server is configured correctly. If you're sure, please
 * send an email to the auhtor with a dumped output of 
 * $ftp->ls('./', NET_FTP_RAWLIST); to get the date format supported.
 *
 * @since 1.3
 * @name NET_FTP_ERR_DATEFORMAT_FAILED
 * @see Net_FTP::mdtm(), Net_FTP::ls()
 */
define('NET_FTP_ERR_DATEFORMAT_FAILED', -11);

/**
 * Error code to indicate that the SIZE command failed
 * The determination of the filesize of a file failed. Ensure that your server
 * supports the SIZE command.
 *
 * @since 1.3
 * @name NET_FTP_ERR_SIZE_FAILED
 * @see Net_FTP::size()
 */
define('NET_FTP_ERR_SIZE_FAILED', -12);

/**
 * Error code to indicate that a local file could not be overwritten
 * You specified not to overwrite files. Therefore the local file has not been
 * overwriten. If you want to get the file overwriten, please set the option to
 * do so.
 *
 * @since 1.3
 * @name NET_FTP_ERR_OVERWRITELOCALFILE_FORBIDDEN
 * @see Net_FTP::get(), Net_FTP::getRecursive()
 */
define('NET_FTP_ERR_OVERWRITELOCALFILE_FORBIDDEN', -13);

/**
 * Error code to indicate that a local file could not be overwritten
 * Also you specified to overwrite the local file you want to download to,
 * it has not been possible to do so. Check that you have the appropriate access
 * rights on the local file to overwrite it.
 *
 * @since 1.3
 * @name NET_FTP_ERR_OVERWRITELOCALFILE_FAILED
 * @see Net_FTP::get(), Net_FTP::getRecursive()
 */
define('NET_FTP_ERR_OVERWRITELOCALFILE_FAILED', -14);

/**
 * Error code to indicate that the file you wanted to upload does not exist
 * The file you tried to upload does not exist. Ensure that it exists.
 *
 * @since 1.3
 * @name NET_FTP_ERR_LOCALFILENOTEXIST
 * @see Net_FTP::put(), Net_FTP::putRecursive()
 */
define('NET_FTP_ERR_LOCALFILENOTEXIST', -15);

/**
 * Error code to indicate that a remote file could not be overwritten
 * You specified not to overwrite files. Therefore the remote file has not been
 * overwriten. If you want to get the file overwriten, please set the option to
 * do so.
 *
 * @since 1.3
 * @name NET_FTP_ERR_OVERWRITEREMOTEFILE_FORBIDDEN
 * @see Net_FTP::put(), Net_FTP::putRecursive()
 */
define('NET_FTP_ERR_OVERWRITEREMOTEFILE_FORBIDDEN', -16);

/**
 * Error code to indicate that the upload of a file failed
 * The upload you tried failed. Ensure that you have appropriate access rights
 * to upload the desired file.
 *
 * @since 1.3
 * @name NET_FTP_ERR_UPLOADFILE_FAILED
 * @see Net_FTP::put(), Net_FTP::putRecursive()
 */
define('NET_FTP_ERR_UPLOADFILE_FAILED', -17);

/**
 * Error code to indicate that you specified an incorrect directory path
 * The remote path you specified seems not to be a directory. Ensure that
 * the path you specify is a directory and that the path string ends with
 * a /.
 *
 * @since 1.3
 * @name NET_FTP_ERR_REMOTEPATHNODIR
 * @see Net_FTP::putRecursive(), Net_FTP::getRecursive()
 */
define('NET_FTP_ERR_REMOTEPATHNODIR', -18);

/**
 * Error code to indicate that you specified an incorrect directory path
 * The local path you specified seems not to be a directory. Ensure that
 * the path you specify is a directory and that the path string ends with
 * a /.
 *
 * @since 1.3
 * @name NET_FTP_ERR_LOCALPATHNODIR
 * @see Net_FTP::putRecursive(), Net_FTP::getRecursive()
 */
define('NET_FTP_ERR_LOCALPATHNODIR', -19);

/**
 * Error code to indicate that a local directory failed to be created
 * You tried to create a local directory through getRecursive() method,
 * which has failed. Ensure that you have the appropriate access rights
 * to create it.
 *
 * @since 1.3
 * @name NET_FTP_ERR_CREATELOCALDIR_FAILED
 * @see Net_FTP::getRecursive()
 */
define('NET_FTP_ERR_CREATELOCALDIR_FAILED', -20);

/**
 * Error code to indicate that the provided hostname was incorrect
 * The hostname you provided was invalid. Ensure to provide either a
 * full qualified domain name or an IP address.
 *
 * @since 1.3
 * @name NET_FTP_ERR_HOSTNAMENOSTRING
 * @see Net_FTP::setHostname()
 */
define('NET_FTP_ERR_HOSTNAMENOSTRING', -21);

/**
 * Error code to indicate that the provided port was incorrect
 * The port number you provided was invalid. Ensure to provide either a
 * a numeric port number greater zero.
 *
 * @since 1.3
 * @name NET_FTP_ERR_PORTLESSZERO
 * @see Net_FTP::setPort()
 */
define('NET_FTP_ERR_PORTLESSZERO', -22);

/**
 * Error code to indicate that you provided an invalid mode constant
 * The mode constant you provided was invalid. You may only provide
 * FTP_ASCII or FTP_BINARY.
 *
 * @since 1.3
 * @name NET_FTP_ERR_NOMODECONST
 * @see Net_FTP::setMode()
 */
define('NET_FTP_ERR_NOMODECONST', -23);

/**
 * Error code to indicate that you provided an invalid timeout
 * The timeout you provided was invalid. You have to provide a timeout greater
 * or equal to zero.
 *
 * @since 1.3
 * @name NET_FTP_ERR_TIMEOUTLESSZERO
 * @see Net_FTP::Net_FTP(), Net_FTP::setTimeout()
 */
define('NET_FTP_ERR_TIMEOUTLESSZERO', -24);

/**
 * Error code to indicate that you provided an invalid timeout
 * An error occured while setting the timeout. Ensure that you provide a
 * valid integer for the timeount and that your PHP installation works
 * correctly.
 *
 * @since 1.3
 * @name NET_FTP_ERR_SETTIMEOUT_FAILED
 * @see Net_FTP::Net_FTP(), Net_FTP::setTimeout()
 */
define('NET_FTP_ERR_SETTIMEOUT_FAILED', -25);

/**
 * Error code to indicate that the provided extension file doesn't exist
 * The provided extension file does not exist. Ensure to provided an
 * existant extension file.
 *
 * @since 1.3
 * @name NET_FTP_ERR_EXTFILENOTEXIST
 * @see Net_FTP::getExtensionsFile()
 */
define('NET_FTP_ERR_EXTFILENOTEXIST', -26);

/**
 * Error code to indicate that the provided extension file is not readable
 * The provided extension file is not readable. Ensure to have sufficient
 * access rights for it.
 *
 * @since 1.3
 * @name NET_FTP_ERR_EXTFILEREAD_FAILED
 * @see Net_FTP::getExtensionsFile()
 */
define('NET_FTP_ERR_EXTFILEREAD_FAILED', -27);

/**
 * Error code to indicate that the deletion of a file failed
 * The specified file could not be deleted. Ensure to have sufficient
 * access rights to delete the file.
 *
 * @since 1.3
 * @name NET_FTP_ERR_EXTFILEREAD_FAILED
 * @see Net_FTP::rm()
 */
define('NET_FTP_ERR_DELETEFILE_FAILED', -28);

/**
 * Error code to indicate that the deletion of a directory faild
 * The specified file could not be deleted. Ensure to have sufficient
 * access rights to delete the file.
 *
 * @since 1.3
 * @name NET_FTP_ERR_EXTFILEREAD_FAILED
 * @see Net_FTP::rm()
 */
define('NET_FTP_ERR_DELETEDIR_FAILED', -29);

/**
 * Error code to indicate that the directory listing failed
 * PHP could not list the directory contents on the server. Ensure
 * that your server is configured appropriate.
 *
 * @since 1.3
 * @name NET_FTP_ERR_RAWDIRLIST_FAILED
 * @see Net_FTP::ls()
 */
define('NET_FTP_ERR_RAWDIRLIST_FAILED', -30);

/**
 * Error code to indicate that the directory listing failed
 * The directory listing format your server uses seems not to
 * be supported by Net_FTP. Please send the output of the
 * call ls('./', NET_FTP_RAWLIST); to the author of this
 * class to get it supported.
 *
 * @since 1.3
 * @name NET_FTP_ERR_DIRLIST_UNSUPPORTED
 * @see Net_FTP::ls()
 */
define('NET_FTP_ERR_DIRLIST_UNSUPPORTED', -31);

/**
 * Error code to indicate failed disconnecting
 * This error code indicates, that disconnection was not possible.
 *
 * @since 1.3
 * @name NET_FTP_ERR_DISCONNECT_FAILED
 * @see Net_FTP::disconnect()
 */
define('NET_FTP_ERR_DISCONNECT_FAILED', -32);

/**
 * Error code to indicate that the username you provided was invalid.
 * Check that you provided a non-empty string as the username.
 *
 * @since 1.3
 * @name NET_FTP_ERR_USERNAMENOSTRING
 * @see Net_FTP::setUsername()
 */
define('NET_FTP_ERR_USERNAMENOSTRING', -33);

/**
 * Error code to indicate that the username you provided was invalid.
 * Check that you provided a non-empty string as the username.
 *
 * @since 1.3
 * @name NET_FTP_ERR_PASSWORDNOSTRING
 * @see Net_FTP::setPassword()
 */
define('NET_FTP_ERR_PASSWORDNOSTRING', -34);

/**
 * Error code to indicate that the provided extension file is not loadable
 * The provided extension file is not loadable. Ensure to have a correct file
 * syntax.
 *
 * @since 1.3.3
 * @name NET_FTP_ERR_EXTFILELOAD_FAILED
 * @see Net_FTP::getExtensionsFile()
 */
define('NET_FTP_ERR_EXTFILELOAD_FAILED', -35);

/**
 * Class for comfortable FTP-communication
 *
 * This class provides comfortable communication with FTP-servers. You may do
 * everything enabled by the PHP-FTP-extension and further functionalities, like
 * recursive-deletion, -up- and -download. Another feature is to create directories
 * recursively.
 *
 * @category  Networking
 * @package   FTP
 * @author    Tobias Schlitt <toby@php.net>
 * @author    Jorrit Schippers <jschippers@php.net>
 * @copyright 1997-2008 The PHP Group
 * @license   http://www.php.net/license/3_0.txt PHP License 3.0
 * @version   Release: 1.3.7
 * @link      http://pear.php.net/package/Net_FTP
 * @since     0.0.1
 * @access    public
 */
class Net_FTP extends PEAR
{
    /**
     * The host to connect to
     *
     * @access  private
     * @var     string
     */
    var $_hostname;

    /**
     * The port for ftp-connection (standard is 21)
     *
     * @access  private
     * @var     int
     */
    var $_port = 21;

    /**
     * The username for login
     *
     * @access  private
     * @var     string
     */
    var $_username;

    /**
     * The password for login
     *
     * @access  private
     * @var     string
     */
    var $_password;

    /**
     * Determine whether to use passive-mode (true) or active-mode (false)
     *
     * @access  private
     * @var     bool
     */
    var $_passv;

    /**
     * The standard mode for ftp-transfer
     *
     * @access  private
     * @var     int
     */
    var $_mode = FTP_BINARY;

    /**
     * This holds the handle for the ftp-connection
     *
     * @access  private
     * @var     resource
     */
    var $_handle;

    /**
     * Contains the timeout for FTP operations
     *
     * @access  private
     * @var     int
     * @since   1.3
     */
    var $_timeout = 90;
        
    /**
     * Saves file-extensions for ascii- and binary-mode
     *
     * The array contains 2 sub-arrays ("ascii" and "binary"), which both contain
     * file-extensions without the "." (".php" = "php").
     *
     * @access  private
     * @var     array
     */
    var $_file_extensions;

    /**
     * ls match
     * Matches the ls entries against a regex and maps the resulting array to
     * speaking names
     *
     * The values are set in the constructor because of line length constaints.
     *
     * Typical lines for the Windows format:
     * 07-05-07  08:40AM                 4701 SomeFile.ext
     * 04-29-07  10:28PM       <DIR>          SomeDir
     *
     * @access  private
     * @var     array
     * @since   1.3
     */
    var $_ls_match = null;
    
    /**
     * matcher
     * Stores the matcher for the current connection
     *
     * @access  private
     * @var     array
     * @since   1.3
     */
    var $_matcher = null;
    
    /**
     * Holds all Net_FTP_Observer objects 
     * that wish to be notified of new messages.
     *
     * @var     array
     * @access  private
     * @since   1.3
     */
    var $_listeners = array();

    /**
     * This generates a new FTP-Object. The FTP-connection will not be established,
     * yet.
     * You can leave $host and $port blank, if you want. The $host will not be set
     * and the $port will be left at 21. You have to set the $host manualy before
     * trying to connect or with the connect() method.
     *
     * @param string $host    (optional) The hostname 
     * @param int    $port    (optional) The port
     * @param int    $timeout (optional) Sets the standard timeout
     *
     * @access public
     * @return void
     * @see Net_FTP::setHostname(), Net_FTP::setPort(), Net_FTP::connect()
     */
    function Net_FTP($host = null, $port = null, $timeout = 90)
    {
        $this->PEAR();
        if (isset($host)) {
            $this->setHostname($host);
        }
        if (isset($port)) {
            $this->setPort($port);
        }
        $this->_timeout                     = $timeout;
        $this->_file_extensions[FTP_ASCII]  = array();
        $this->_file_extensions[FTP_BINARY] = array();
        
        $this->_ls_match = array(
            'unix'    => array(
                'pattern' => '/(?:(d)|.)([rwxts-]{9})\s+(\w+)\s+([\w\d-()?.]+)\s+'.
                             '([\w\d-()?.]+)\s+(\w+)\s+(\S+\s+\S+\s+\S+)\s+(.+)/',
                'map'     => array(
                    'is_dir'        => 1,
                    'rights'        => 2,
                    'files_inside'  => 3,
                    'user'          => 4,
                    'group'         => 5,
                    'size'          => 6,
                    'date'          => 7,
                    'name'          => 8,
                )
            ),
            'windows' => array(
                'pattern' => '/([0-9\-]+)\s+([0-9:APM]+)\s+((<DIR>)|\d+)\s+(.+)/',
                'map'     => array(
                    'date'   => 1,
                    'time'   => 2,
                    'size'   => 3,
                    'is_dir' => 4,
                    'name'   => 5,
                )
            )
        );
    }

    /**
     * This function generates the FTP-connection. You can optionally define a
     * hostname and/or a port. If you do so, this data is stored inside the object.
     *
     * @param string $host (optional) The Hostname
     * @param int    $port (optional) The Port
     *
     * @access public
     * @return mixed True on success, otherwise PEAR::Error
     * @see NET_FTP_ERR_CONNECT_FAILED
     */
    function connect($host = null, $port = null)
    {
        $this->_matcher = null;
        if (isset($host)) {
            $this->setHostname($host);
        }
        if (isset($port)) {
            $this->setPort($port);
        }
        $handle = @ftp_connect($this->getHostname(), $this->getPort(),
                               $this->_timeout);
        if (!$handle) {
            return $this->raiseError("Connection to host failed",
                                     NET_FTP_ERR_CONNECT_FAILED);
        } else {
            $this->_handle =& $handle;
            return true;
        }
    }

    /**
     * This function close the FTP-connection
     *
     * @access public
     * @return bool|PEAR_Error Returns true on success, PEAR_Error on failure
     */
    function disconnect()
    {
        $res = @ftp_close($this->_handle);
        if (!$res) {
            return PEAR::raiseError('Disconnect failed.',
                                    NET_FTP_ERR_DISCONNECT_FAILED);
        }
        return true;
    }

    /**
     * This logs you into the ftp-server. You are free to specify username and
     * password in this method. If you specify it, the values will be taken into 
     * the corresponding attributes, if do not specify, the attributes are taken.
     *
     * @param string $username (optional) The username to use 
     * @param string $password (optional) The password to use
     *
     * @access public
     * @return mixed True on success, otherwise PEAR::Error
     * @see NET_FTP_ERR_LOGIN_FAILED
     */
    function login($username = null, $password = null)
    {
        if (!isset($username)) {
            $username = $this->getUsername();
        } else {
            $this->setUsername($username);
        }

        if (!isset($password)) {
            $password = $this->getPassword();
        } else {
            $this->setPassword($password);
        }

        $res = @ftp_login($this->_handle, $username, $password);

        if (!$res) {
            return $this->raiseError("Unable to login", NET_FTP_ERR_LOGIN_FAILED);
        } else {
            return true;
        }
    }

    /**
     * This changes the currently used directory. You can use either an absolute
     * directory-path (e.g. "/home/blah") or a relative one (e.g. "../test").
     *
     * @param string $dir The directory to go to.
     *
     * @access public
     * @return mixed True on success, otherwise PEAR::Error
     * @see NET_FTP_ERR_DIRCHANGE_FAILED
     */
    function cd($dir)
    {
        $erg = @ftp_chdir($this->_handle, $dir);
        if (!$erg) {
            return $this->raiseError("Directory change failed",
                                     NET_FTP_ERR_DIRCHANGE_FAILED);
        } else {
            return true;
        }
    }

    /**
     * Show's you the actual path on the server
     * This function questions the ftp-handle for the actual selected path and
     * returns it.
     *
     * @access public
     * @return mixed The actual path or PEAR::Error
     * @see NET_FTP_ERR_DETERMINEPATH_FAILED
     */
    function pwd()
    {
        $res = @ftp_pwd($this->_handle);
        if (!$res) {
            return $this->raiseError("Could not determine the actual path.",
                                     NET_FTP_ERR_DETERMINEPATH_FAILED);
        } else {
            return $res;
        }
    }

    /**
     * This works similar to the mkdir-command on your local machine. You can either
     * give it an absolute or relative path. The relative path will be completed
     * with the actual selected server-path. (see: pwd())
     *
     * @param string $dir       Absolute or relative dir-path
     * @param bool   $recursive (optional) Create all needed directories
     *
     * @access public
     * @return mixed True on success, otherwise PEAR::Error
     * @see NET_FTP_ERR_CREATEDIR_FAILED
     */
    function mkdir($dir, $recursive = false)
    {
        $dir     = $this->_constructPath($dir);
        $savedir = $this->pwd();
        $this->pushErrorHandling(PEAR_ERROR_RETURN);
        $e = $this->cd($dir);
        $this->popErrorHandling();
        if ($e === true) {
            $this->cd($savedir);
            return true;
        }
        $this->cd($savedir);
        if ($recursive === false) {
            $res = @ftp_mkdir($this->_handle, $dir);
            if (!$res) {
                return $this->raiseError("Creation of '$dir' failed",
                                         NET_FTP_ERR_CREATEDIR_FAILED);
            } else {
                return true;
            }
        } else {
            // do not look at the first character, as $dir is absolute,
            // it will always be a /
            if (strpos(substr($dir, 1), '/') === false) {
                return $this->mkdir($dir, false);
            }
            if (substr($dir, -1) == '/') {
                $dir = substr($dir, 0, -1);
            }
            $parent = substr($dir, 0, strrpos($dir, '/'));
            $res    = $this->mkdir($parent, true);
            if ($res === true) {
                $res = $this->mkdir($dir, false);
            }
            if ($res !== true) {
                return $res;
            }
            return true;
        }
    }

    /**
     * This method tries executing a command on the ftp, using SITE EXEC.
     *
     * @param string $command The command to execute
     *
     * @access public
     * @return mixed The result of the command (if successfull), otherwise
     *               PEAR::Error
     * @see NET_FTP_ERR_EXEC_FAILED
     */
    function execute($command)
    {
        $res = @ftp_exec($this->_handle, $command);
        if (!$res) {
            return $this->raiseError("Execution of command '$command' failed.",
                                     NET_FTP_ERR_EXEC_FAILED);
        } else {
            return $res;
        }
    }

    /**
     * Execute a SITE command on the server
     * This method tries to execute a SITE command on the ftp server.
     *
     * @param string $command The command with parameters to execute
     *
     * @access public
     * @return mixed True if successful, otherwise PEAR::Error
     * @see NET_FTP_ERR_SITE_FAILED
     */
    function site($command)
    {
        $res = @ftp_site($this->_handle, $command);
        if (!$res) {
            return $this->raiseError("Execution of SITE command '$command' failed.",
                                     NET_FTP_ERR_SITE_FAILED);
        } else {
            return $res;
        }
    }

    /**
     * This method will try to chmod the file specified on the server
     * Currently, you must give a number as the the permission argument (777 or
     * similar). The file can be either a relative or absolute path.
     * NOTE: Some servers do not support this feature. In that case, you will
     * get a PEAR error object returned. If successful, the method returns true
     *
     * @param mixed   $target      The file or array of files to set permissions for
     * @param integer $permissions The mode to set the file permissions to
     *
     * @access public
     * @return mixed True if successful, otherwise PEAR::Error
     * @see NET_FTP_ERR_CHMOD_FAILED
     */
    function chmod($target, $permissions)
    {
        // If $target is an array: Loop through it.
        if (is_array($target)) {

            for ($i = 0; $i < count($target); $i++) {
                $res = $this->chmod($target[$i], $permissions);
                if (PEAR::isError($res)) {
                    return $res;
                } // end if isError
            } // end for i < count($target)

        } else {

            $res = $this->site("CHMOD " . $permissions . " " . $target);
            if (!$res) {
                return PEAR::raiseError("CHMOD " . $permissions . " " . $target .
                                        " failed", NET_FTP_ERR_CHMOD_FAILED);
            } else {
                return $res;
            }

        } // end if is_array

    } // end method chmod

    /**
     * This method will try to chmod a folder and all of its contents
     * on the server. The target argument must be a folder or an array of folders
     * and the permissions argument have to be an integer (i.e. 777).
     * The file can be either a relative or absolute path.
     * NOTE: Some servers do not support this feature. In that case, you
     * will get a PEAR error object returned. If successful, the method
     * returns true
     *
     * @param mixed   $target      The folder or array of folders to
     *                             set permissions for
     * @param integer $permissions The mode to set the folder
     *                             and file permissions to
     *
     * @access public
     * @return mixed True if successful, otherwise PEAR::Error
     * @see NET_FTP_ERR_CHMOD_FAILED, NET_FTP_ERR_DETERMINEPATH_FAILED,
     *      NET_FTP_ERR_RAWDIRLIST_FAILED, NET_FTP_ERR_DIRLIST_UNSUPPORTED
     */
    function chmodRecursive($target, $permissions)
    {
        static $dir_permissions;

        if (!isset($dir_permissions)) { // Making directory specific permissions
            $dir_permissions = $this->_makeDirPermissions($permissions);
        }

        // If $target is an array: Loop through it
        if (is_array($target)) {

            for ($i = 0; $i < count($target); $i++) {
                $res = $this->chmodRecursive($target[$i], $permissions);
                if (PEAR::isError($res)) {
                    return $res;
                } // end if isError
            } // end for i < count($target)

        } else {

            $remote_path = $this->_constructPath($target);

            // Chmod the directory itself
            $result = $this->chmod($remote_path, $dir_permissions);

            if (PEAR::isError($result)) {
                return $result;
            }

            // If $remote_path last character is not a slash, add one
            if (substr($remote_path, strlen($remote_path)-1) != "/") {

                $remote_path .= "/";
            }

            $dir_list = array();
            $mode     = NET_FTP_DIRS_ONLY;
            $dir_list = $this->ls($remote_path, $mode);
            foreach ($dir_list as $dir_entry) {
                if ($dir_entry['name'] == '.' || $dir_entry['name'] == '..') {
                    continue;
                }
                
                $remote_path_new = $remote_path.$dir_entry["name"]."/";

                // Chmod the directory we're about to enter
                $result = $this->chmod($remote_path_new, $dir_permissions);

                if (PEAR::isError($result)) {
                    return $result;
                }

                $result = $this->chmodRecursive($remote_path_new, $permissions);

                if (PEAR::isError($result)) {
                    return $result;
                }

            } // end foreach dir_list as dir_entry

            $file_list = array();
            $mode      = NET_FTP_FILES_ONLY;
            $file_list = $this->ls($remote_path, $mode);

            foreach ($file_list as $file_entry) {

                $remote_file = $remote_path.$file_entry["name"];

                $result = $this->chmod($remote_file, $permissions);

                if (PEAR::isError($result)) {
                    return $result;
                }

            } // end foreach $file_list

        } // end if is_array

        return true; // No errors

    } // end method chmodRecursive

    /**
     * Rename or move a file or a directory from the ftp-server
     *
     * @param string $remote_from The remote file or directory original to rename or
     *                            move
     * @param string $remote_to   The remote file or directory final to rename or
     *                            move
     *
     * @access public
     * @return bool $res True on success, otherwise PEAR::Error
     * @see NET_FTP_ERR_RENAME_FAILED
     */
    function rename ($remote_from, $remote_to) 
    {
        $res = @ftp_rename($this->_handle, $remote_from, $remote_to);
        if (!$res) {
            return $this->raiseError("Could not rename ".$remote_from." to ".
                                     $remote_to." !", NET_FTP_ERR_RENAME_FAILED);
        }
        return true;
    }

    /**
     * This will return logical permissions mask for directory.
     * if directory has to be readable it have also be executable
     *
     * @param string $permissions File permissions in digits for file (i.e. 666)
     *
     * @access private
     * @return string File permissions in digits for directory (i.e. 777)
     */
    function _makeDirPermissions($permissions)
    {
        $permissions = (string)$permissions;
        
        // going through (user, group, world)
        for ($i = 0; $i < strlen($permissions); $i++) {
            // Read permission is set but execute not yet
            if ((int)$permissions{$i} & 4 and !((int)$permissions{$i} & 1)) {
                // Adding execute flag
                (int)$permissions{$i} = (int)$permissions{$i} + 1;
            }
        }

        return (string)$permissions;
    }

    /**
     * This will return the last modification-time of a file. You can either give
     * this function a relative or an absolute path to the file to check.
     * NOTE: Some servers will not support this feature and the function works
     * only on files, not directories! When successful,
     * it will return the last modification-time as a unix-timestamp or, when
     * $format is specified, a preformated timestring.
     *
     * @param string $file   The file to check
     * @param string $format (optional) The format to give the date back 
     *                       if not set, it will return a Unix timestamp
     *
     * @access public
     * @return mixed Unix timestamp, a preformated date-string or PEAR::Error
     * @see NET_FTP_ERR_MDTMDIR_UNSUPPORTED, NET_FTP_ERR_MDTM_FAILED,
     *      NET_FTP_ERR_DATEFORMAT_FAILED
     */
    function mdtm($file, $format = null)
    {
        $file = $this->_constructPath($file);
        if ($this->_checkDir($file)) {
            return $this->raiseError("Filename '$file' seems to be a directory.",
                                     NET_FTP_ERR_MDTMDIR_UNSUPPORTED);
        }
        $res = @ftp_mdtm($this->_handle, $file);
        if ($res == -1) {
            return $this->raiseError("Could not get last-modification-date of '".
                                     $file."'.", NET_FTP_ERR_MDTM_FAILED);
        }
        if (isset($format)) {
            $res = date($format, $res);
            if (!$res) {
                return $this->raiseError("Date-format failed on timestamp '".$res.
                                         "'.", NET_FTP_ERR_DATEFORMAT_FAILED);
            }
        }
        return $res;
    }

    /**
     * This will return the size of a given file in bytes. You can either give this
     * function a relative or an absolute file-path. NOTE: Some servers do not
     * support this feature!
     *
     * @param string $file The file to check
     *
     * @access public
     * @return mixed Size in bytes or PEAR::Error
     * @see NET_FTP_ERR_SIZE_FAILED
     */
    function size($file)
    {
        $file = $this->_constructPath($file);
        $res  = @ftp_size($this->_handle, $file);
        if ($res == -1) {
            return $this->raiseError("Could not determine filesize of '$file'.",
                                     NET_FTP_ERR_SIZE_FAILED);
        } else {
            return $res;
        }
    }

    /**
     * This method returns a directory-list of the current directory or given one.
     * To display the current selected directory, simply set the first parameter to
     * null
     * or leave it blank, if you do not want to use any other parameters.
     * <br><br>
     * There are 4 different modes of listing directories. Either to list only
     * the files (using NET_FTP_FILES_ONLY), to list only directories (using
     * NET_FTP_DIRS_ONLY) or to show both (using NET_FTP_DIRS_FILES, which is
     * default).
     * <br><br>
     * The 4th one is the NET_FTP_RAWLIST, which returns just the array created by
     * the ftp_rawlist()-function build into PHP.
     * <br><br>
     * The other function-modes will return an array containing the requested data.
     * The files and dirs are listed in human-sorted order, but if you select
     * NET_FTP_DIRS_FILES the directories will be added above the files,
     * but although both sorted.
     * <br><br>
     * All elements in the arrays are associative arrays themselves. They have the
     * following structure:
     * <br><br>
     * Dirs:<br>
     *           ["name"]        =>  string The name of the directory<br>
     *           ["rights"]      =>  string The rights of the directory (in style
     *                               "rwxr-xr-x")<br>
     *           ["user"]        =>  string The owner of the directory<br>
     *           ["group"]       =>  string The group-owner of the directory<br>
     *           ["files_inside"]=>  string The number of files/dirs inside the
     *                               directory excluding "." and ".."<br>
     *           ["date"]        =>  int The creation-date as Unix timestamp<br>
     *           ["is_dir"]      =>  bool true, cause this is a dir<br>
     * <br><br>
     * Files:<br>
     *           ["name"]        =>  string The name of the file<br>
     *           ["size"]        =>  int Size in bytes<br>
     *           ["rights"]      =>  string The rights of the file (in style 
     *                               "rwxr-xr-x")<br>
     *           ["user"]        =>  string The owner of the file<br>
     *           ["group"]       =>  string The group-owner of the file<br>
     *           ["date"]        =>  int The creation-date as Unix timestamp<br>
     *           ["is_dir"]      =>  bool false, cause this is a file<br>
     *
     * @param string $dir  (optional) The directory to list or null, when listing
     *                     the current directory.
     * @param int    $mode (optional) The mode which types to list (files,
     *                     directories or both).
     *
     * @access public
     * @return mixed The directory list as described above or PEAR::Error on failure
     * @see NET_FTP_DIRS_FILES, NET_FTP_DIRS_ONLY, NET_FTP_FILES_ONLY,
     *      NET_FTP_RAWLIST, NET_FTP_ERR_DETERMINEPATH_FAILED,
     *      NET_FTP_ERR_RAWDIRLIST_FAILED, NET_FTP_ERR_DIRLIST_UNSUPPORTED
     */
    function ls($dir = null, $mode = NET_FTP_DIRS_FILES)
    {
        if (!isset($dir)) {
            $dir = @ftp_pwd($this->_handle);
            if (!$dir) {
                return $this->raiseError("Could not retrieve current directory",
                                         NET_FTP_ERR_DETERMINEPATH_FAILED);
            }
        }
        if (($mode != NET_FTP_FILES_ONLY) && ($mode != NET_FTP_DIRS_ONLY) &&
            ($mode != NET_FTP_RAWLIST)) {
            $mode = NET_FTP_DIRS_FILES;
        }

        switch ($mode) {
        case NET_FTP_DIRS_FILES:
            $res = $this->_lsBoth($dir);
            break;
        case NET_FTP_DIRS_ONLY:
            $res = $this->_lsDirs($dir);
            break;
        case NET_FTP_FILES_ONLY:
            $res = $this->_lsFiles($dir);
            break;
        case NET_FTP_RAWLIST:
            $res = @ftp_rawlist($this->_handle, $dir);
            break;
        }

        return $res;
    }

    /**
     * This method will delete the given file or directory ($path) from the server
     * (maybe recursive).
     *
     * Whether the given string is a file or directory is only determined by the
     * last sign inside the string ("/" or not).
     *
     * If you specify a directory, you can optionally specify $recursive as true,
     * to let the directory be deleted recursive (with all sub-directories and files
     * inherited).
     *
     * You can either give a absolute or relative path for the file / dir. If you
     * choose to use the relative path, it will be automatically completed with the
     * actual selected directory.
     *
     * @param string $path      The absolute or relative path to the file/directory.
     * @param bool   $recursive (optional)
     *
     * @access public
     * @return mixed True on success, otherwise PEAR::Error
     * @see NET_FTP_ERR_DELETEFILE_FAILED, NET_FTP_ERR_DELETEDIR_FAILED,
     *      NET_FTP_ERR_REMOTEPATHNODIR
     */
    function rm($path, $recursive = false)
    {
        $path = $this->_constructPath($path);
        if ($this->_checkDir($path)) {
            if ($recursive) {
                return $this->_rmDirRecursive($path);
            } else {
                return $this->_rmDir($path);
            }
        } else {
            return $this->_rmFile($path);
        }
    }

    /**
     * This function will download a file from the ftp-server. You can either
     * specify an absolute path to the file (beginning with "/") or a relative one,
     * which will be completed with the actual directory you selected on the server.
     * You can specify the path to which the file will be downloaded on the local
     * machine, if the file should be overwritten if it exists (optionally, default
     * is no overwriting) and in which mode (FTP_ASCII or FTP_BINARY) the file
     * should be downloaded (if you do not specify this, the method tries to
     * determine it automatically from the mode-directory or uses the default-mode,
     * set by you).
     * If you give a relative path to the local-file, the script-path is used as
     * basepath.
     *
     * @param string $remote_file The absolute or relative path to the file to
     *                            download
     * @param string $local_file  The local file to put the downloaded in
     * @param bool   $overwrite   (optional) Whether to overwrite existing file
     * @param int    $mode        (optional) Either FTP_ASCII or FTP_BINARY
     *
     * @access public
     * @return mixed True on success, otherwise PEAR::Error
     * @see NET_FTP_ERR_OVERWRITELOCALFILE_FORBIDDEN,
     *      NET_FTP_ERR_OVERWRITELOCALFILE_FAILED,
     *      NET_FTP_ERR_OVERWRITELOCALFILE_FAILED
     */
    function get($remote_file, $local_file, $overwrite = false, $mode = null)
    {
        if (!isset($mode)) {
            $mode = $this->checkFileExtension($remote_file);
        }

        $remote_file = $this->_constructPath($remote_file);

        if (@file_exists($local_file) && !$overwrite) {
            return $this->raiseError("Local file '".$local_file.
                                     "' exists and may not be overwriten.",
                                     NET_FTP_ERR_OVERWRITELOCALFILE_FORBIDDEN);
        }
        if (@file_exists($local_file) &&
            !@is_writeable($local_file) && $overwrite) {
            return $this->raiseError("Local file '".$local_file.
                                     "' is not writeable. Can not overwrite.",
                                     NET_FTP_ERR_OVERWRITELOCALFILE_FAILED);
        }

        if (@function_exists('ftp_nb_get')) {
            $res = @ftp_nb_get($this->_handle, $local_file, $remote_file, $mode);
            while ($res == FTP_MOREDATA) {
                $this->_announce('nb_get');
                $res = @ftp_nb_continue($this->_handle);
            }
        } else {
            $res = @ftp_get($this->_handle, $local_file, $remote_file, $mode);
        }
        if (!$res) {
            return $this->raiseError("File '".$remote_file.
                                     "' could not be downloaded to '$local_file'.",
                                     NET_FTP_ERR_OVERWRITELOCALFILE_FAILED);
        } else {
            return true;
        }
    }

    /**
     * This function will upload a file to the ftp-server. You can either specify a
     * absolute path to the remote-file (beginning with "/") or a relative one,
     * which will be completed with the actual directory you selected on the server.
     * You can specify the path from which the file will be uploaded on the local
     * maschine, if the file should be overwritten if it exists (optionally, default
     * is no overwriting) and in which mode (FTP_ASCII or FTP_BINARY) the file
     * should be downloaded (if you do not specify this, the method tries to
     * determine it automatically from the mode-directory or uses the default-mode,
     * set by you).
     * If you give a relative path to the local-file, the script-path is used as
     * basepath.
     *
     * @param string $local_file  The local file to upload
     * @param string $remote_file The absolute or relative path to the file to
     *                            upload to
     * @param bool   $overwrite   (optional) Whether to overwrite existing file
     * @param int    $mode        (optional) Either FTP_ASCII or FTP_BINARY
     *
     * @access public
     * @return mixed True on success, otherwise PEAR::Error
     * @see NET_FTP_ERR_LOCALFILENOTEXIST,
     *      NET_FTP_ERR_OVERWRITEREMOTEFILE_FORBIDDEN,
     *      NET_FTP_ERR_UPLOADFILE_FAILED
     */
    function put($local_file, $remote_file, $overwrite = false, $mode = null)
    {
        if (!isset($mode)) {
            $mode = $this->checkFileExtension($local_file);
        }
        $remote_file = $this->_constructPath($remote_file);

        if (!@file_exists($local_file)) {
            return $this->raiseError("Local file '$local_file' does not exist.",
                                     NET_FTP_ERR_LOCALFILENOTEXIST);
        }
        if ((@ftp_size($this->_handle, $remote_file) != -1) && !$overwrite) {
            return $this->raiseError("Remote file '".$remote_file.
                                     "' exists and may not be overwriten.",
                                     NET_FTP_ERR_OVERWRITEREMOTEFILE_FORBIDDEN);
        }

        if (function_exists('ftp_alloc')) {
            ftp_alloc($this->_handle, filesize($local_file));
        }
        if (function_exists('ftp_nb_put')) {
            $res = @ftp_nb_put($this->_handle, $remote_file, $local_file, $mode);
            while ($res == FTP_MOREDATA) {
                $this->_announce('nb_put');
                $res = @ftp_nb_continue($this->_handle);
            }

        } else {
            $res = @ftp_put($this->_handle, $remote_file, $local_file, $mode);
        }
        if (!$res) {
            return $this->raiseError("File '$local_file' could not be uploaded to '"
                                     .$remote_file."'.",
                                     NET_FTP_ERR_UPLOADFILE_FAILED);
        } else {
            return true;
        }
    }

    /**
     * This functionality allows you to transfer a whole directory-structure from
     * the remote-ftp to your local host. You have to give a remote-directory
     * (ending with '/') and the local directory (ending with '/') where to put the
     * files you download.
     * The remote path is automatically completed with the current-remote-dir, if
     * you give a relative path to this function. You can give a relative path for
     * the $local_path, too. Then the script-basedir will be used for comletion of
     * the path.
     * The parameter $overwrite will determine, whether to overwrite existing files
     * or not. Standard for this is false. Fourth you can explicitly set a mode for
     * all transfer actions done. If you do not set this, the method tries to
     * determine the transfer mode by checking your mode-directory for the file
     * extension. If the extension is not inside the mode-directory, it will get
     * your default mode.
     *
     * @param string $remote_path The path to download
     * @param string $local_path  The path to download to
     * @param bool   $overwrite   (optional) Whether to overwrite existing files
     *                            (true) or not (false, standard).
     * @param int    $mode        (optional) The transfermode (either FTP_ASCII or
     * FTP_BINARY).
     *
     * @access public
     * @return mixed True on succes, otherwise PEAR::Error
     * @see NET_FTP_ERR_OVERWRITELOCALFILE_FORBIDDEN,
     * NET_FTP_ERR_OVERWRITELOCALFILE_FAILED, NET_FTP_ERR_OVERWRITELOCALFILE_FAILED,
     * NET_FTP_ERR_REMOTEPATHNODIR, NET_FTP_ERR_LOCALPATHNODIR,
     * NET_FTP_ERR_CREATELOCALDIR_FAILED
     */
    function getRecursive($remote_path, $local_path, $overwrite = false,
                          $mode = null)
    {
        $remote_path = $this->_constructPath($remote_path);
        if (!$this->_checkDir($remote_path)) {
            return $this->raiseError("Given remote-path '".$remote_path.
                                     "' seems not to be a directory.",
                                     NET_FTP_ERR_REMOTEPATHNODIR);
        }
        if (!$this->_checkDir($local_path)) {
            return $this->raiseError("Given local-path '".$local_path.
                                     "' seems not to be a directory.",
                                     NET_FTP_ERR_LOCALPATHNODIR);
        }

        if (!@is_dir($local_path)) {
            $res = @mkdir($local_path);
            if (!$res) {
                return $this->raiseError("Could not create dir '$local_path'",
                                         NET_FTP_ERR_CREATELOCALDIR_FAILED);
            }
        }
        $dir_list = array();
        $dir_list = $this->ls($remote_path, NET_FTP_DIRS_ONLY);
        if (PEAR::isError($dir_list)) {
            return $dir_list;
        }
        foreach ($dir_list as $dir_entry) {
            if ($dir_entry['name'] != '.' && $dir_entry['name'] != '..') {
                $remote_path_new = $remote_path.$dir_entry["name"]."/";
                $local_path_new  = $local_path.$dir_entry["name"]."/";
                $result          = $this->getRecursive($remote_path_new,
                                   $local_path_new, $overwrite, $mode);
                if ($this->isError($result)) {
                    return $result;
                }
            }
        }
        $file_list = array();
        $file_list = $this->ls($remote_path, NET_FTP_FILES_ONLY);
        if (PEAR::isError($file_list)) {
            return $file_list;
        }
        foreach ($file_list as $file_entry) {
            $remote_file = $remote_path.$file_entry["name"];
            $local_file  = $local_path.$file_entry["name"];
            $result      = $this->get($remote_file, $local_file, $overwrite, $mode);
            if ($this->isError($result)) {
                return $result;
            }
        }
        return true;
    }

    /**
     * This functionality allows you to transfer a whole directory-structure from
     * your local host to the remote-ftp. You have to give a remote-directory
     * (ending with '/') and the local directory (ending with '/') where to put the
     * files you download. The remote path is automatically completed with the
     * current-remote-dir, if you give a relative path to this function. You can
     * give a relative path for the $local_path, too. Then the script-basedir will
     * be used for comletion of the path.
     * The parameter $overwrite will determine, whether to overwrite existing files
     * or not.
     * Standard for this is false. Fourth you can explicitly set a mode for all
     * transfer actions done. If you do not set this, the method tries to determine
     * the transfer mode by checking your mode-directory for the file-extension. If
     * the extension is not inside the mode-directory, it will get your default
     * mode.
     *
     * @param string $local_path  The path to download to
     * @param string $remote_path The path to download
     * @param bool   $overwrite   (optional) Whether to overwrite existing files
     *                            (true) or not (false, standard).
     * @param int    $mode        (optional) The transfermode (either FTP_ASCII or
     *                            FTP_BINARY).
     *
     * @access public
     * @return mixed True on succes, otherwise PEAR::Error
     * @see NET_FTP_ERR_LOCALFILENOTEXIST,
     *      NET_FTP_ERR_OVERWRITEREMOTEFILE_FORBIDDEN,
     *      NET_FTP_ERR_UPLOADFILE_FAILED, NET_FTP_ERR_LOCALPATHNODIR,
     *      NET_FTP_ERR_REMOTEPATHNODIR
     */
    function putRecursive($local_path, $remote_path, $overwrite = false,
                          $mode = null)
    {
        $remote_path = $this->_constructPath($remote_path);
        if (!file_exists($local_path) || !is_dir($local_path)) {
            return $this->raiseError("Given local-path '".$local_path.
                                     "' seems not to be a directory.",
                                     NET_FTP_ERR_LOCALPATHNODIR);
        }
        if (!$this->_checkDir($remote_path)) {
            return $this->raiseError("Given remote-path '".$remote_path.
                                     "' seems not to be a directory.",
                                     NET_FTP_ERR_REMOTEPATHNODIR);
        }
        $old_path = $this->pwd();
        if ($this->isError($this->cd($remote_path))) {
            $res = $this->mkdir($remote_path);
            if ($this->isError($res)) {
                return $res;
            }
        }
        $this->cd($old_path);
        $dir_list = $this->_lsLocal($local_path);
        foreach ($dir_list["dirs"] as $dir_entry) {
            // local directories do not have arrays as entry
            $remote_path_new = $remote_path.$dir_entry."/";
            $local_path_new  = $local_path.$dir_entry."/";
            $result          = $this->putRecursive($local_path_new,
                               $remote_path_new, $overwrite, $mode);
            if ($this->isError($result)) {
                return $result;
            }
        }

        foreach ($dir_list["files"] as $file_entry) {
            $remote_file = $remote_path.$file_entry;
            $local_file  = $local_path.$file_entry;
            $result      = $this->put($local_file, $remote_file, $overwrite, $mode);
            if ($this->isError($result)) {
                return $result;
            }
        }
        return true;
    }

    /**
     * This checks, whether a file should be transfered in ascii- or binary-mode
     * by it's file-extension. If the file-extension is not set or
     * the extension is not inside one of the extension-dirs, the actual set
     * transfer-mode is returned.
     *
     * @param string $filename The filename to be checked
     *
     * @access public
     * @return int Either FTP_ASCII or FTP_BINARY
     */
    function checkFileExtension($filename)
    {
        if (($pos = strrpos($filename, '.')) === false) {
            return $this->_mode;
        } else {
            $ext = substr($filename, $pos + 1);
        }
        
        if (isset($this->_file_extensions[$ext])) {
            return $this->_file_extensions[$ext];
        }
        
        return $this->_mode;
    }

    /**
     * Set the hostname
     *
     * @param string $host The hostname to set
     *
     * @access public
     * @return bool True on success, otherwise PEAR::Error
     * @see NET_FTP_ERR_HOSTNAMENOSTRING
     */
    function setHostname($host)
    {
        if (!is_string($host)) {
            return PEAR::raiseError("Hostname must be a string.",
                                    NET_FTP_ERR_HOSTNAMENOSTRING);
        }
        $this->_hostname = $host;
        return true;
    }

    /**
     * Set the Port
     *
     * @param int $port The port to set
     *
     * @access public
     * @return bool True on success, otherwise PEAR::Error
     * @see NET_FTP_ERR_PORTLESSZERO
     */
    function setPort($port)
    {
        if (!is_int($port) || ($port < 0)) {
            PEAR::raiseError("Invalid port. Has to be integer >= 0",
                             NET_FTP_ERR_PORTLESSZERO);
        }
        $this->_port = $port;
        return true;
    }

    /**
     * Set the Username
     *
     * @param string $user The username to set
     *
     * @access public
     * @return mixed True on success, otherwise PEAR::Error
     * @see NET_FTP_ERR_USERNAMENOSTRING
     */
    function setUsername($user)
    {
        if (empty($user) || !is_string($user)) {
            return PEAR::raiseError('Username $user invalid.',
                   NET_FTP_ERR_USERNAMENOSTRING);
        }
        $this->_username = $user;
    }

    /**
     * Set the password
     *
     * @param string $password The password to set
     *
     * @access private
     * @return void
     * @see NET_FTP_ERR_PASSWORDNOSTRING
     */
    function setPassword($password)
    {
        if (empty($password) || !is_string($password)) {
            return PEAR::raiseError('Password xxx invalid.',
                                    NET_FTP_ERR_PASSWORDNOSTRING);
        }
        $this->_password = $password;
    }

    /**
     * Set the transfer-mode. You can use the predefined constants
     * FTP_ASCII or FTP_BINARY. The mode will be stored for any further transfers.
     *
     * @param int $mode The mode to set
     *
     * @access public
     * @return mixed True on success, otherwise PEAR::Error
     * @see NET_FTP_ERR_NOMODECONST
     */
    function setMode($mode)
    {
        if (($mode == FTP_ASCII) || ($mode == FTP_BINARY)) {
            $this->_mode = $mode;
            return true;
        } else {
            return $this->raiseError('FTP-Mode has either to be FTP_ASCII or'.
                                     'FTP_BINARY', NET_FTP_ERR_NOMODECONST);
        }
    }

    /**
     * Set the transfer-method to passive mode
     *
     * @access public
     * @return void
     */
    function setPassive()
    {
        $this->_passv = true;
        @ftp_pasv($this->_handle, true);
    }

    /**
     * Set the transfer-method to active mode
     *
     * @access public
     * @return void
     */
    function setActive()
    {
        $this->_passv = false;
        @ftp_pasv($this->_handle, false);
    }

    /**
     * Set the timeout for FTP operations
     *
     * Use this method to set a timeout for FTP operation. Timeout has to be an
     * integer.
     *
     * @param int $timeout the timeout to use
     *
     * @access public
     * @return bool True on success, otherwise PEAR::Error
     * @see NET_FTP_ERR_TIMEOUTLESSZERO, NET_FTP_ERR_SETTIMEOUT_FAILED
     */
    function setTimeout ( $timeout = 0 ) 
    {
        if (!is_int($timeout) || ($timeout < 0)) {
            return PEAR::raiseError('Timeout '.$timeout.
                                    ' is invalid, has to be an integer >= 0',
                                    NET_FTP_ERR_TIMEOUTLESSZERO);
        }
        $this->_timeout = $timeout;
        if (isset($this->_handle) && is_resource($this->_handle)) {
            $res = @ftp_set_option($this->_handle, FTP_TIMEOUT_SEC, $timeout);
        } else {
            $res = true;
        }
        if (!$res) {
            return PEAR::raiseError("Set timeout failed.",
                                    NET_FTP_ERR_SETTIMEOUT_FAILED);
        }
        return true;
    }        

    /**
     * Adds an extension to a mode-directory
     *
     * The mode-directory saves file-extensions coresponding to filetypes
     * (ascii e.g.: 'php', 'txt', 'htm',...; binary e.g.: 'jpg', 'gif', 'exe',...).
     * The extensions have to be saved without the '.'. And
     * can be predefined in an external file (see: getExtensionsFile()).
     *
     * The array is build like this: 'php' => FTP_ASCII, 'png' => FTP_BINARY
     *
     * To change the mode of an extension, just add it again with the new mode!
     *
     * @param int    $mode Either FTP_ASCII or FTP_BINARY
     * @param string $ext  Extension
     *
     * @access public
     * @return void
     */
    function addExtension($mode, $ext)
    {
        $this->_file_extensions[$ext] = $mode;
    }

    /**
     * This function removes an extension from the mode-directories 
     * (described above).
     *
     * @param string $ext The extension to remove
     *
     * @access public
     * @return void
     */
    function removeExtension($ext)
    {
        if (isset($this->_file_extensions[$ext])) {
            unset($this->_file_extensions[$ext]);
        }
    }

    /**
     * This get's both (ascii- and binary-mode-directories) from the given file.
     * Beware, if you read a file into the mode-directory, all former set values 
     * will be unset!
     * 
     * Example file contents:
     * [ASCII]
     * asc = 0
     * txt = 0
     * [BINARY]
     * bin = 1
     * jpg = 1
     *
     * @param string $filename The file to get from
     *
     * @access public
     * @return mixed True on success, otherwise PEAR::Error
     * @see NET_FTP_ERR_EXTFILENOTEXIST, NET_FTP_ERR_EXTFILEREAD_FAILED
     */
    function getExtensionsFile($filename)
    {
        if (!file_exists($filename)) {
            return $this->raiseError("Extensions-file '$filename' does not exist",
                                     NET_FTP_ERR_EXTFILENOTEXIST);
        }
        
        if (!is_readable($filename)) {
            return $this->raiseError("Extensions-file '$filename' is not readable",
                                     NET_FTP_ERR_EXTFILEREAD_FAILED);
        }
        
        $exts = @parse_ini_file($filename, true);
        if (!is_array($exts)) {
            return $this->raiseError("Extensions-file '$filename' could not be".
                "loaded", NET_FTP_ERR_EXTFILELOAD_FAILED);
        }
        
        $this->_file_extensions = array();
        
        if (isset($exts['ASCII'])) {
            foreach ($exts['ASCII'] as $ext => $bogus) {
                $this->_file_extensions[$ext] = FTP_ASCII;
            }
        }
        
        if (isset($exts['BINARY'])) {
            foreach ($exts['BINARY'] as $ext => $bogus) {
                $this->_file_extensions[$ext] = FTP_BINARY;
            }
        }
        
        return true;
    }

    /**
     * Returns the hostname
     *
     * @access public
     * @return string The hostname
     */
    function getHostname()
    {
        return $this->_hostname;
    }

    /**
     * Returns the port
     *
     * @access public
     * @return int The port
     */
    function getPort()
    {
        return $this->_port;
    }

    /**
     * Returns the username
     *
     * @access public
     * @return string The username
     */
    function getUsername()
    {
        return $this->_username;
    }

    /**
     * Returns the password
     *
     * @access public
     * @return string The password
     */
    function getPassword()
    {
        return $this->_password;
    }

    /**
     * Returns the transfermode
     *
     * @access public
     * @return int The transfermode, either FTP_ASCII or FTP_BINARY.
     */
    function getMode()
    {
        return $this->_mode;
    }

    /**
     * Returns, whether the connection is set to passive mode or not
     *
     * @access public
     * @return bool True if passive-, false if active-mode
     */
    function isPassive()
    {
        return $this->_passv;
    }

    /**
     * Returns the mode set for a file-extension
     *
     * @param string $ext The extension you wanna ask for
     *
     * @return int Either FTP_ASCII, FTP_BINARY or NULL (if not set a mode for it)
     * @access public
     */
    function getExtensionMode($ext)
    {
        return @$this->_file_extensions[$ext];
    }

    /**
     * Get the currently set timeout.
     * Returns the actual timeout set.
     *
     * @access public
     * @return int The actual timeout
     */
    function getTimeout()
    {
        return ftp_get_option($this->_handle, FTP_TIMEOUT_SEC);
    }    

    /**
     * Adds a Net_FTP_Observer instance to the list of observers 
     * that are listening for messages emitted by this Net_FTP instance.
     *
     * @param object &$observer The Net_FTP_Observer instance to attach 
     *                         as a listener.
     *
     * @return boolean True if the observer is successfully attached.
     * @access public
     * @since 1.3
     */
    function attach(&$observer)
    {
        if (!is_a($observer, 'Net_FTP_Observer')) {
            return false;
        }

        $this->_listeners[$observer->getId()] = &$observer;
        return true;
    }

    /**
     * Removes a Net_FTP_Observer instance from the list of observers.
     *
     * @param object $observer The Net_FTP_Observer instance to detach 
     *                         from the list of listeners.
     *
     * @return boolean True if the observer is successfully detached.
     * @access public
     * @since 1.3
     */
    function detach($observer)
    {
        if (!is_a($observer, 'Net_FTP_Observer') ||
            !isset($this->_listeners[$observer->getId()])) {
            return false;
        }

        unset($this->_listeners[$observer->getId()]);
        return true;
    }

    /**
     * Informs each registered observer instance that a new message has been
     * sent.                                                                
     *                                                                      
     * @param mixed $event A hash describing the net event.
     *  
     * @access private                                                     
     * @since 1.3      
     * @return void                                                   
     */
    function _announce($event)
    {
        foreach ($this->_listeners as $id => $listener) {
            $this->_listeners[$id]->notify($event);
        }
    }

    /**
     * Rebuild the path, if given relative
     *
     * This method will make a relative path absolute by prepending the current
     * remote directory in front of it.
     *
     * @param string $path The path to check and construct
     *
     * @access private
     * @return string The build path
     */
    function _constructPath($path)
    {
        if ((substr($path, 0, 1) != '/') && (substr($path, 0, 2) != './')) {
            $actual_dir = @ftp_pwd($this->_handle);
            if (substr($actual_dir, -1) != '/') {
                $actual_dir .= '/';
            }
            $path = $actual_dir.$path;
        }
        return $path;
    }

    /**
     * Checks, whether a given string is a directory-path (ends with "/") or not.
     *
     * @param string $path Path to check
     *
     * @access private
     * @return bool True if $path is a directory, otherwise false
     */
    function _checkDir($path)
    {
        if (!empty($path) && substr($path, (strlen($path) - 1), 1) == "/") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * This will remove a file
     *
     * @param string $file The file to delete
     *
     * @access private
     * @return mixed True on success, otherwise PEAR::Error
     * @see NET_FTP_ERR_DELETEFILE_FAILED
     */
    function _rmFile($file)
    {
        if (substr($file, 0, 1) != "/") {
            $actual_dir = @ftp_pwd($this->_handle);
            if (substr($actual_dir, (strlen($actual_dir) - 2), 1) != "/") {
                $actual_dir .= "/";
            }
            $file = $actual_dir.$file;
        }
        $res = @ftp_delete($this->_handle, $file);
        
        if (!$res) {
            return $this->raiseError("Could not delete file '$file'.",
                                     NET_FTP_ERR_DELETEFILE_FAILED);
        } else {
            return true;
        }
    }

    /**
     * This will remove a dir
     *
     * @param string $dir The dir to delete
     *
     * @access private
     * @return mixed True on success, otherwise PEAR::Error
     * @see NET_FTP_ERR_REMOTEPATHNODIR, NET_FTP_ERR_DELETEDIR_FAILED
     */
    function _rmDir($dir)
    {
        if (substr($dir, (strlen($dir) - 1), 1) != "/") {
            return $this->raiseError("Directory name '".$dir.
                                     "' is invalid, has to end with '/'",
                                     NET_FTP_ERR_REMOTEPATHNODIR);
        }
        $res = @ftp_rmdir($this->_handle, $dir);
        if (!$res) {
            return $this->raiseError("Could not delete directory '$dir'.",
                                     NET_FTP_ERR_DELETEDIR_FAILED);
        } else {
            return true;
        }
    }

    /**
     * This will remove a dir and all subdirs and -files
     *
     * @param string $dir The dir to delete recursively
     *
     * @access private
     * @return mixed True on success, otherwise PEAR::Error
     * @see NET_FTP_ERR_REMOTEPATHNODIR, NET_FTP_ERR_DELETEDIR_FAILED
     */
    function _rmDirRecursive($dir)
    {
        if (substr($dir, (strlen($dir) - 1), 1) != "/") {
            return $this->raiseError("Directory name '".$dir.
                                     "' is invalid, has to end with '/'",
                                     NET_FTP_ERR_REMOTEPATHNODIR);
        }
        $file_list = $this->_lsFiles($dir);
        foreach ($file_list as $file) {
            $file = $dir.$file["name"];
            $res  = $this->rm($file);
            if ($this->isError($res)) {
                return $res;
            }
        }
        $dir_list = $this->_lsDirs($dir);
        foreach ($dir_list as $new_dir) {
            if ($new_dir["name"] == '.' || $new_dir["name"] == '..') {
                continue;
            }
            $new_dir = $dir.$new_dir["name"]."/";
            $res     = $this->_rmDirRecursive($new_dir);
            if ($this->isError($res)) {
                return $res;
            }
        }
        $res = $this->_rmDir($dir);
        if (PEAR::isError($res)) {
            return $res;
        } else {
            return true;
        }
    }

    /**
     * Lists up files and directories
     *
     * @param string $dir The directory to list up
     *
     * @access private
     * @return array An array of dirs and files
     */
    function _lsBoth($dir)
    {
        $list_splitted = $this->_listAndParse($dir);
        if (PEAR::isError($list_splitted)) {
            return $list_splitted;
        }
        if (!is_array($list_splitted["files"])) {
            $list_splitted["files"] = array();
        }
        if (!is_array($list_splitted["dirs"])) {
            $list_splitted["dirs"] = array();
        }
        $res = array();
        @array_splice($res, 0, 0, $list_splitted["files"]);
        @array_splice($res, 0, 0, $list_splitted["dirs"]);
        return $res;
    }

    /**
     * Lists up directories
     *
     * @param string $dir The directory to list up
     *
     * @access private
     * @return array An array of dirs
     */
    function _lsDirs($dir)
    {
        $list = $this->_listAndParse($dir);
        if (PEAR::isError($list)) {
            return $list;
        }
        return $list["dirs"];
    }

    /**
     * Lists up files
     *
     * @param string $dir The directory to list up
     *
     * @access private
     * @return array An array of files
     */
    function _lsFiles($dir)
    {
        $list = $this->_listAndParse($dir);
        if (PEAR::isError($list)) {
            return $list;
        }
        return $list["files"];
    }

    /**
     * This lists up the directory-content and parses the items into well-formated
     * arrays.
     * The results of this array are sorted (dirs on top, sorted by name;
     * files below, sorted by name).
     *
     * @param string $dir The directory to parse
     *
     * @access private
     * @return array Lists of dirs and files
     * @see NET_FTP_ERR_RAWDIRLIST_FAILED
     */
    function _listAndParse($dir)
    {
        $dirs_list  = array();
        $files_list = array();
        $dir_list   = @ftp_rawlist($this->_handle, $dir);
        if (!is_array($dir_list)) {
            return PEAR::raiseError('Could not get raw directory listing.',
                                    NET_FTP_ERR_RAWDIRLIST_FAILED);
        }
        
        foreach ($dir_list AS $k=>$v) {
            if (strncmp($v, 'total: ', 7) == 0 && preg_match('/total: \d+/', $v)) {
                unset($dir_list[$k]);
                break; // usually there is just one line like this
            }
        }
        
        // Handle empty directories
        if (count($dir_list) == 0) {
            return array('dirs' => $dirs_list, 'files' => $files_list);
        }

        // Exception for some FTP servers seem to return this wiered result instead
        // of an empty list
        if (count($dirs_list) == 1 && $dirs_list[0] == 'total 0') {
            return array('dirs' => array(), 'files' => $files_list);
        }
        
        if (!isset($this->_matcher) || PEAR::isError($this->_matcher)) {
            $this->_matcher = $this->_determineOSMatch($dir_list);
            if (PEAR::isError($this->_matcher)) {
                return $this->_matcher;
            }
        }
        foreach ($dir_list as $entry) {
            if (!preg_match($this->_matcher['pattern'], $entry, $m)) {
                continue;
            }
            $entry = array();
            foreach ($this->_matcher['map'] as $key=>$val) {
                $entry[$key] = $m[$val];
            }
            $entry['stamp'] = $this->_parseDate($entry['date']);

            if ($entry['is_dir']) {
                $dirs_list[] = $entry;
            } else {
                $files_list[] = $entry;
            }
        }
        @usort($dirs_list, array("Net_FTP", "_natSort"));
        @usort($files_list, array("Net_FTP", "_natSort"));
        $res["dirs"]  = (is_array($dirs_list)) ? $dirs_list : array();
        $res["files"] = (is_array($files_list)) ? $files_list : array();
        return $res;
    }

    /**
     * Determine server OS
     * This determines the server OS and returns a valid regex to parse
     * ls() output.
     *
     * @param array &$dir_list The raw dir list to parse
     *
     * @access private
     * @return mixed An array of 'pattern' and 'map' on success, otherwise
     *               PEAR::Error
     * @see NET_FTP_ERR_DIRLIST_UNSUPPORTED
     */
    function _determineOSMatch(&$dir_list)
    {
        foreach ($dir_list as $entry) {
            foreach ($this->_ls_match as $os => $match) {
                $matches = array();
                if (preg_match($match['pattern'], $entry, $matches)) {
                    return $match;
                }
            }
        }
        $error = 'The list style of your server seems not to be supported. Please'.
                 'email a "$ftp->ls(NET_FTP_RAWLIST);" output plus info on the'.
                 'server to the maintainer of this package to get it supported!'.
                 'Thanks for your help!';
        return PEAR::raiseError($error, NET_FTP_ERR_DIRLIST_UNSUPPORTED);
    }

    /**
     * Lists a local directory
     *
     * @param string $dir_path The dir to list
     *
     * @access private
     * @return array The list of dirs and files
     */
    function _lsLocal($dir_path)
    {
        $dir       = dir($dir_path);
        $dir_list  = array();
        $file_list = array();
        while (false !== ($entry = $dir->read())) {
            if (($entry != '.') && ($entry != '..')) {
                if (is_dir($dir_path.$entry)) {
                    $dir_list[] = $entry;
                } else {
                    $file_list[] = $entry;
                }
            }
        }
        $dir->close();
        $res['dirs']  = $dir_list;
        $res['files'] = $file_list;
        return $res;
    }

    /**
     * Function for use with usort().
     * Compares the list-array-elements by name.
     *
     * @param string $item_1 first item to be compared
     * @param string $item_2 second item to be compared
     *
     * @access private
     * @return int < 0 if $item_1 is less than $item_2, 0 if equal and > 0 otherwise
     */
    function _natSort($item_1, $item_2)
    {
        return strnatcmp($item_1['name'], $item_2['name']);
    }

    /**
     * Parse dates to timestamps
     *
     * @param string $date Date
     *
     * @access private
     * @return int Timestamp
     * @see NET_FTP_ERR_DATEFORMAT_FAILED
     */
    function _parseDate($date)
    {
        // Sep 10 22:06 => Sep 10, <year> 22:06
        if (preg_match('/([A-Za-z]+)[ ]+([0-9]+)[ ]+([0-9]+):([0-9]+)/', $date,
                       $res)) {
            $year    = date('Y');
            $month   = $res[1];
            $day     = $res[2];
            $hour    = $res[3];
            $minute  = $res[4];
            $date    = "$month $day, $year $hour:$minute";
            $tmpDate = strtotime($date);
            if ($tmpDate > time()) {
                $year--;
                $date = "$month $day, $year $hour:$minute";
            }
        } elseif (preg_match('/^\d\d-\d\d-\d\d/', $date)) {
            // 09-10-04 => 09/10/04
            $date = str_replace('-', '/', $date);
        }
        $res = strtotime($date);
        if (!$res) {
            return $this->raiseError('Dateconversion failed.',
                                     NET_FTP_ERR_DATEFORMAT_FAILED);
        }
        return $res;
    }
}
?>
