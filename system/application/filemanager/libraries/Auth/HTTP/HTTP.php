<?php
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Martin Jansen <mj@php.net>                                  |
// |          Rui Hirokawa <hirokawa@php.net>                             |
// |          David Costa  <gurugeek@php.net>                             |
// +----------------------------------------------------------------------+
//
//  $Id: Auth_HTTP.php,v 1.27 2005/04/04 12:48:33 hirokawa Exp $ 
//

require_once "Auth/Auth.php";

define('AUTH_HTTP_NONCE_TIME_LEN', 16);
define('AUTH_HTTP_NONCE_HASH_LEN', 32);

// {{{ class Auth_HTTP

/**
 * PEAR::Auth_HTTP
 *
 * The PEAR::Auth_HTTP class provides methods for creating an
 * HTTP authentication system based on RFC-2617 using PHP.
 *
 * Instead of generating an HTML driven form like PEAR::Auth
 * does, this class sends header commands to the clients which
 * cause them to present a login box like they are e.g. used
 * in Apache's .htaccess mechanism.
 *
 * This class requires the PEAR::Auth package.
 *
 * @notes The HTTP Digest Authentication part is based on
 *  authentication class written by Tom Pike <tom.pike@xiven.com>
 *
 * @author  Martin Jansen <mj@php.net>
 * @author  Rui Hirokawa <hirokawa@php.net>
 * @author  David Costa <gurugeek@php.net>
 * @package Auth_HTTP
 * @extends Auth
 * @version $Revision: 1.27 $
 */
class Auth_HTTP extends Auth
{
   
    // {{{ properties

    /**
     * Authorization method: 'basic' or 'digest'
     *
     * @access public
     * @var    string
     */
    var $authType = 'basic';
 
    /**
     * Name of the realm for Basic Authentication
     *
     * @access public
     * @var    string
     * @see    drawLogin()
     */
    var $realm = "protected area";

    /**
     * Text to send if user hits cancel button
     *
     * @access public
     * @var    string
     * @see    drawLogin()
     */
    var $CancelText = "Error 401 - Access denied";

    /**
     * option array
     *
     * @access public
     * @var    array
     */
    var $options = array();

    /**
     * flag to indicate the nonce was stale. 
     *
     * @access public
     * @var    bool
     */
    var $stale = false;

    /**
     * opaque string for digest authentication
     *
     * @access public
     * @var    string
     */
    var $opaque = 'dummy';

    /**
     * digest URI
     *
     * @access public
     * @var    string
     */
    var $uri = '';

    /**
     * authorization info returned by the client
     *
     * @access public
     * @var    array
     */
    var $auth = array();

    /**
     * next nonce value
     *
     * @access public
     * @var    string
     */
    var $nextNonce = '';

    /**
     * nonce value
     *
     * @access public
     * @var    string
     */
    var $nonce = '';

    /**
     * Holds a reference to the global server variable
     * @var array
     */
    var $server;

    /**
     * Holds a reference to the global post variable
     * @var array
     */
    var $post;

    /**
     * Holds a reference to the global cookie variable
     * @var array
     */
    var $cookie;


    // }}}
    // {{{ Constructor

    /**
     * Constructor
     *
     * @param string    Type of the storage driver
     * @param mixed     Additional options for the storage driver
     *                  (example: if you are using DB as the storage
     *                   driver, you have to pass the dsn string here)
     *
     * @return void
     */
    function Auth_HTTP($storageDriver, $options = '') 
    {
        /* set default values for options */
        $this->options = array('cryptType' => 'md5',
                               'algorithm' => 'MD5',
                               'qop' => 'auth-int,auth',
                               'opaquekey' => 'moo',
                               'noncekey' => 'moo',
                               'digestRealm' => 'protected area',
                               'forceDigestOnly' => false,
                               'nonceLife' => 300,
                               'sessionSharing' => true,
                               );
		
        if (!empty($options['authType'])) {
            $this->authType = strtolower($options['authType']);
        }
		
        if (is_array($options)) {
            foreach($options as $key => $value) {
                if (array_key_exists( $key, $this->options)) {
                    $this->options[$key] = $value;
                }
            }
		
            if (!empty($this->options['opaquekey'])) {
                $this->opaque = md5($this->options['opaquekey']);
            }
        }
		
		$this->Auth($storageDriver, $options);
	}
	
	// }}}
    // {{{ assignData()

    /**
     * Assign values from $PHP_AUTH_USER and $PHP_AUTH_PW or 'Authorization' header
     * to internal variables and sets the session id based
     * on them
     *
     * @access public
     * @return void
     */
    function assignData()
    {
        if (method_exists($this, '_importGlobalVariable')) {
            $this->server = &$this->_importGlobalVariable('server');
        }
        
        
        if ($this->authType == 'basic') {
            if (!empty($this->server['PHP_AUTH_USER'])) {
                $this->username = $this->server['PHP_AUTH_USER'];
            }
            
            if (!empty($this->server['PHP_AUTH_PW'])) {
                $this->password = $this->server['PHP_AUTH_PW'];
            }
            
            /**
             * Try to get authentication information from IIS
             */
            if  (empty($this->username) && empty($this->password)) {
                if (!empty($this->server['HTTP_AUTHORIZATION'])) {
                    list($this->username, $this->password) = 
                        explode(':', base64_decode(substr($this->server['HTTP_AUTHORIZATION'], 6)));
                }
            }
        } elseif ($this->authType == 'digest') {
            $this->username = '';
            $this->password = '';

            $this->digest_header = null;
            if (!empty($this->server['PHP_AUTH_DIGEST'])) {
                $this->digest_header = substr($this->server['PHP_AUTH_DIGEST'],
                                              strpos($this->server['PHP_AUTH_DIGEST'],' ')+1);
            } else {
                $headers = getallheaders();
                if(isset($headers['Authorization']) && !empty($headers['Authorization'])) {
                    $this->digest_header = substr($headers['Authorization'],
                                                  strpos($headers['Authorization'],' ')+1);
                }
            }

            if($this->digest_header) {
                $authtemp = explode(',', $this->digest_header);
                $auth = array();
                foreach($authtemp as $key => $value) {
                    $value = trim($value);
                    if(strpos($value,'=') !== false) {
                        $lhs = substr($value,0,strpos($value,'='));
                        $rhs = substr($value,strpos($value,'=')+1);
                        if(substr($rhs,0,1) == '"' && substr($rhs,-1,1) == '"') {
                            $rhs = substr($rhs,1,-1);
                        }
                        $auth[$lhs] = $rhs;
                    }
                }
            }
            if (!isset($auth['uri']) || !isset($auth['realm'])) {
                return;
            }
            
            if ($this->selfURI() == $auth['uri']) {
                $this->uri = $auth['uri'];
                if (substr($headers['Authorization'],0,7) == 'Digest ') {
                    
                    $this->authType = 'digest';

                    if (!isset($auth['nonce']) || !isset($auth['username']) || 
                  !isset($auth['response']) || !isset($auth['qop']) || 
                  !isset($auth['nc']) || !isset($auth['cnonce'])){
                        return;
                    }

               if ($auth['qop'] != 'auth' && $auth['qop'] != 'auth-int') {
                        return;
               }
                    
                    $this->stale = $this->_judgeStale($auth['nonce']);

               if ($this->nextNonce == false) {
                  return;
               }

                    $this->username = $auth['username'];
                    $this->password = $auth['response'];
                    $this->auth['nonce'] = $auth['nonce'];
                    
               $this->auth['qop'] = $auth['qop'];
               $this->auth['nc'] = $auth['nc'];
               $this->auth['cnonce'] = $auth['cnonce'];

                    if (isset($auth['opaque'])) {
                        $this->auth['opaque'] = $auth['opaque'];
                    }
                    
                } elseif (substr($headers['Authorization'],0,6) == 'Basic ') {
                    if ($this->options['forceDigestOnly']) {
                        return; // Basic authentication is not allowed.
                    }
                    
                    $this->authType = 'basic';
                    list($username, $password) = 
                        explode(':',base64_decode(substr($headers['Authorization'],6)));
                    $this->username = $username;
                    $this->password = $password;
                }
            }
        } else {
            return PEAR::raiseError('authType is invalid.');
        }

        if ($this->options['sessionSharing'] && 
            isset($this->username) && isset($this->password)) {
            session_id(md5('Auth_HTTP' . $this->username . $this->password));
        }
        
        /**
         * set sessionName for AUTH, so that the sessionName is different 
         * for distinct realms 
         */
         $this->_sessionName = "_authhttp".md5($this->realm);
    }

    // }}}
    // {{{ login()

    /**
     * Login function
     *
     * @access private
     * @return void
     */
    function login() 
    {
        $login_ok = false;
        if (method_exists($this, '_loadStorage')) {
            $this->_loadStorage();
        }
        $this->storage->_auth_obj->_sessionName =& $this->_sessionName;

        /**
         * When the user has already entered a username,
         * we have to validate it.
         */
        if (!empty($this->username) && !empty($this->password)) {
            if ($this->authType == 'basic' && !$this->options['forceDigestOnly']) {
                if (true === $this->storage->fetchData($this->username, $this->password)) {
                    $login_ok = true;
                }
            } else { /* digest authentication */

                if (!$this->getAuth() || $this->getAuthData('a1') == null) {
                    /* 
                     * note:
                     *  - only PEAR::DB is supported as container.
                     *  - password should be stored in container as plain-text 
                     *    (if $options['cryptType'] == 'none') or 
                     *     A1 hashed form (md5('username:realm:password')) 
                     *    (if $options['cryptType'] == 'md5')
                     */
                    $dbs = $this->storage;
                    if (!DB::isConnection($dbs->db)) {
                        $dbs->_connect($dbs->options['dsn']);
                    }
                    
                    $query = 'SELECT '.$dbs->options['passwordcol']." FROM ".$dbs->options['table'].
                        ' WHERE '.$dbs->options['usernamecol']." = '".
                        $dbs->db->quoteString($this->username)."' ";
                    
                    $pwd = $dbs->db->getOne($query); // password stored in container.
                    
                    if (DB::isError($pwd)) {
                        return PEAR::raiseError($pwd->getMessage(), $pwd->getCode());
                    }
                    
                    if ($this->options['cryptType'] == 'none') {
                        $a1 = md5($this->username.':'.$this->options['digestRealm'].':'.$pwd);
                    } else {
                        $a1 = $pwd;
                    }
                    
                    $this->setAuthData('a1', $a1, true);
                } else {
                    $a1 = $this->getAuthData('a1');
                }
                
                $login_ok = $this->validateDigest($this->password, $a1);
                if ($this->nextNonce == false) {
                    $login_ok = false;
                }
            }
            
            if (!$login_ok && is_callable($this->loginFailedCallback)) {
                call_user_func($this->loginFailedCallback,$this->username, $this);
            }
        }
        
        if (!empty($this->username) && $login_ok) {
            $this->setAuth($this->username);
            if (is_callable($this->loginCallback)) {
                call_user_func($this->loginCallback,$this->username, $this);
            }
        }
        
        /**
         * If the login failed or the user entered no username,
         * output the login screen again.
         */
        if (!empty($this->username) && !$login_ok) {
            $this->status = AUTH_WRONG_LOGIN;
        }
        
        if ((empty($this->username) || !$login_ok) && $this->showLogin) {
            $this->drawLogin($this->storage->activeUser);
            return;
        }

      if (!empty($this->username) && $login_ok && $this->authType == 'digest'
         && $this->auth['qop'] == 'auth') { 
         $this->authenticationInfo();
      }
    }
    
    // }}}
    // {{{ drawLogin()

    /**
     * Launch the login box
     *
     * @param  string $username  Username
     * @return void
     * @access private
     */
    function drawLogin($username = "")
    {
        /**
         * Send the header commands
         */
        if ($this->authType == 'basic') {
            header("WWW-Authenticate: Basic realm=\"".$this->realm."\"");
            header('HTTP/1.0 401 Unauthorized');            
        } else if ($this->authType == 'digest') {
            $this->nonce = $this->_getNonce();

            $wwwauth = 'WWW-Authenticate: Digest ';
            $wwwauth .= 'qop="'.$this->options['qop'].'", ';
            $wwwauth .= 'algorithm='.$this->options['algorithm'].', ';
            $wwwauth .= 'realm="'.$this->options['digestRealm'].'", ';
            $wwwauth .= 'nonce="'.$this->nonce.'", ';
            if ($this->stale) {
                $wwwauth .= 'stale=true, ';
            }
            if (!empty($this->opaque)) {
                $wwwauth .= 'opaque="'.$this->opaque.'"' ;
            }
            $wwwauth .= "\r\n";
            if (!$this->options['forceDigestOnly']) {
                $wwwauth .= 'WWW-Authenticate: Basic realm="'.$this->realm.'"';
            }
            header($wwwauth);
            header('HTTP/1.0 401 Unauthorized');            
        }

        /**
         * This code is only executed if the user hits the cancel
         * button or if he enters wrong data 3 times.
         */
        if ($this->stale) {
            echo 'Stale nonce value, please re-authenticate.';
        } else {
            echo $this->CancelText;
        }
        exit;
    }

    // }}}
    // {{{ setRealm()

    /**
     * Set name of the current realm
     *
     * @access public
     * @param  string $realm  Name of the realm
     * @param  string $digestRealm  Name of the realm for digest authentication
     * @return void
     */
    function setRealm($realm, $digestRealm = '')
    {
        $this->realm = $realm;
        if (!empty($digestRealm)) {
            $this->options['digestRealm'] = $digestRealm;
        }
    }

    // }}}
    // {{{ setCancelText()

    /**
     * Set the text to send if user hits the cancel button
     *
     * @access public
     * @param  string $text  Text to send
     * @return void
     */
    function setCancelText($text)
    {
        $this->CancelText = $text;
    }

    // }}}
    // {{{ validateDigest()
    
    /**
     * judge if the client response is valid.
     *
     * @access private
     * @param  string $response  client response
     * @param  string $a1 password or hashed password stored in container
     * @return bool true if success, false otherwise
     */
    function validateDigest($response, $a1)    
    {
        if (method_exists($this, '_importGlobalVariable')) {
            $this->server = &$this->_importGlobalVariable('server');
        }

        $a2unhashed = $this->server['REQUEST_METHOD'].":".$this->selfURI();
        if($this->auth['qop'] == 'auth-int') {
            if(isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
                // In PHP < 4.3 get raw POST data from this variable
                $body = $GLOBALS["HTTP_RAW_POST_DATA"];
            } else if($lines = @file('php://input')) {
                // In PHP >= 4.3 get raw POST data from this file
                $body = implode("\n", $lines);
            } else {
                if (method_exists($this, '_importGlobalVariable')) {
                    $this->post = &$this->_importGlobalVariable('post');
                }
                $body = '';
                foreach($this->post as $key => $value) {
                    if($body != '') $body .= '&';
                    $body .= rawurlencode($key) . '=' . rawurlencode($value);
                }
            }

            $a2unhashed .= ':'.md5($body);
        }
        
        $a2 = md5($a2unhashed);
        $combined = $a1.':'.
            $this->auth['nonce'].':'.
            $this->auth['nc'].':'.
            $this->auth['cnonce'].':'.
            $this->auth['qop'].':'.
            $a2;
        $expectedResponse = md5($combined);
        
        if(!isset($this->auth['opaque']) || $this->auth['opaque'] == $this->opaque) {
            if($response == $expectedResponse) { // password is valid
                if(!$this->stale) {
                    return true;
                } else {
                    $this->drawLogin();
                }
            }
        }
        
        return false;
    }
    
    // }}}
    // {{{ _judgeStale()
    
    /**
     * judge if nonce from client is stale.
     *
     * @access private
     * @param  string $nonce  nonce value from client
     * @return bool stale
     */
    function _judgeStale($nonce) 
    {
        $stale = false;
        
        if(!$this->_decodeNonce($nonce, $time, $hash_cli)) {
         $this->nextNonce = false;
         $stale = true;
            return $stale;
        }

        if ($time < time() - $this->options['nonceLife']) {
         $this->nextNonce = $this->_getNonce();
            $stale = true;
        } else {
         $this->nextNonce = $nonce;
      }

        return $stale;
    }
    
    // }}}
    // {{{ _nonceDecode()
    
    /**
     * decode nonce string
     *
     * @access private
     * @param  string $nonce nonce value from client
     * @param  string $time decoded time
     * @param  string $hash decoded hash
     * @return bool false if nonce is invalid
     */
    function _decodeNonce($nonce, &$time, &$hash) 
    {
        if (method_exists($this, '_importGlobalVariable')) {
            $this->server = &$this->_importGlobalVariable('server');
        }

        if (strlen($nonce) != AUTH_HTTP_NONCE_TIME_LEN + AUTH_HTTP_NONCE_HASH_LEN) {
            return false;
        }

        $time =  base64_decode(substr($nonce, 0, AUTH_HTTP_NONCE_TIME_LEN));
        $hash_cli = substr($nonce, AUTH_HTTP_NONCE_TIME_LEN, AUTH_HTTP_NONCE_HASH_LEN);

        $hash = md5($time . $this->server['HTTP_USER_AGENT'] . $this->options['noncekey']);

        if ($hash_cli != $hash) {
            return false;
        }
        
        return true;
    }

    // }}}
    // {{{ _getNonce()
    
    /**
     * return nonce to detect timeout
     *
     * @access private
     * @return string nonce value
     */
    function _getNonce() 
    {
        if (method_exists($this, '_importGlobalVariable')) {
            $this->server = &$this->_importGlobalVariable('server');
        }

        $time = time();
        $hash = md5($time . $this->server['HTTP_USER_AGENT'] . $this->options['noncekey']);

        return base64_encode($time) . $hash;  
    }

    // }}}
    // {{{ authenticationInfo()
    
    /**
     * output HTTP Authentication-Info header
     *
     * @notes md5 hash of contents is required if 'qop' is 'auth-int'
     *
     * @access private
     * @param string MD5 hash of content
     */
    function authenticationInfo($contentMD5 = '') {
        
        if($this->getAuth() && ($this->getAuthData('a1') != null)) {
            $a1 = $this->getAuthData('a1');

            // Work out authorisation response
            $a2unhashed = ":".$this->selfURI();
            if($this->auth['qop'] == 'auth-int') {
                $a2unhashed .= ':'.$contentMD5;
            }
            $a2 = md5($a2unhashed);
            $combined = $a1.':'.
                        $this->nonce.':'.
                        $this->auth['nc'].':'.
                        $this->auth['cnonce'].':'.
                        $this->auth['qop'].':'.
                        $a2;
            
            // Send authentication info
            $wwwauth = 'Authentication-Info: ';
            if($this->nonce != $this->nextNonce) {
                $wwwauth .= 'nextnonce="'.$this->nextNonce.'", ';
            }
            $wwwauth .= 'qop='.$this->auth['qop'].', ';
            $wwwauth .= 'rspauth="'.md5($combined).'", ';
            $wwwauth .= 'cnonce="'.$this->auth['cnonce'].'", ';
            $wwwauth .= 'nc='.$this->auth['nc'].'';
            header($wwwauth);
        }
    }
    // }}}
    // {{{ setOption()
    /**
     * set authentication option
     *
     * @access public
     * @param mixed $name key of option
     * @param mixed $value value of option
     * @return void
     */
    function setOption($name, $value = null) 
    {
        if (is_array($name)) {
            foreach($name as $key => $value) {
                if (array_key_exists( $key, $this->options)) {
                    $this->options[$key] = $value;
                }
            }
        } else {
            if (array_key_exists( $name, $this->options)) {
                    $this->options[$name] = $value;
            }
        }
    }

    // }}}
    // {{{ getOption()
    /**
     * get authentication option
     *
     * @access public
     * @param string $name key of option
     * @return mixed option value
     */
    function getOption($name) 
    {
        if (array_key_exists( $name, $this->options)) {
            return $this->options[$name];
        }
        if ($name == 'CancelText') {
            return $this->CancelText;
        }
        if ($name == 'Realm') {
            return $this->realm;
        }
        return false;
    }

    // }}}
    // {{{ selfURI()
    /**
     * get self URI
     *
     * @access public
     * @return string self URI
     */
    function selfURI() 
    {
        if (method_exists($this, '_importGlobalVariable')) {
            $this->server = &$this->_importGlobalVariable('server');
        }

        if (preg_match("/MSIE/",$this->server['HTTP_USER_AGENT'])) {
            // query string should be removed for MSIE
            $uri = preg_replace("/^(.*)\?/","\\1",$this->server['REQUEST_URI']);
        } else {
            $uri = $this->server['REQUEST_URI'];
        }
        return $uri;
    }

    // }}}

}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 */
?>
