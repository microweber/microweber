<?php
/**
 * HTTP Basic authentication backend class
 *
 * This class can be used by authentication objects wishing to use HTTP Basic
 * Most of the digest logic is handled, implementors just need to worry about
 * the authenticateInternal and getUserInfo methods
 *
 * @package Sabre
 * @subpackage DAV
 * @copyright Copyright (C) 2007-2010 Rooftop Solutions. All rights reserved.
 * @author James David Low (http://jameslow.com/)
 * @author Evert Pot (http://www.rooftopsolutions.nl/) 
 * @license http://code.google.com/p/sabredav/wiki/License Modified BSD License
 */
abstract class Sabre_DAV_Auth_Backend_AbstractBasic extends Sabre_DAV_Auth_Backend_Abstract {

    /**
     * This variable holds information about the currently
     * logged in user.
     *
     * @var array|null
     */
    protected $currentUser;

    /**
     * Validates a username and password
     *
     * If the username and password were correct, this method must return
     * an array with at least a 'uri' key.  
     *
     * If the credentials are incorrect, this method must return false.
     *
     * @return bool|array
     */
    abstract protected function validateUserPass($username, $password);

    /**
     * Returns information about the currently logged in user.
     *
     * If nobody is currently logged in, this method should return null.
     *
     * @return array|null
     */
    public function getCurrentUser() {
        return $this->currentUser;
    }


    /**
     * Authenticates the user based on the current request.
     *
     * If authentication is succesful, true must be returned.
     * If authentication fails, an exception must be thrown.
     *
     * @throws Sabre_DAV_Exception_NotAuthenticated
     * @return bool
     */
    public function authenticate(Sabre_DAV_Server $server,$realm) {

        $auth = new Sabre_HTTP_BasicAuth();
        $auth->setHTTPRequest($server->httpRequest);
        $auth->setHTTPResponse($server->httpResponse);
        $auth->setRealm($realm);
        $userpass = $auth->getUserPass();
        if (!$userpass) {
            $auth->requireLogin();
            throw new Sabre_DAV_Exception_NotAuthenticated('No basic authentication headers were found');
        }

        // Authenticates the user
        if (!($userData = $this->validateUserPass($userpass[0],$userpass[1]))) {
            $auth->requireLogin();
            throw new Sabre_DAV_Exception_NotAuthenticated('Username or password does not match');
        }
        if (!isset($userData['uri'])) {
            throw new Sabre_DAV_Exception('The returned array from validateUserPass must contain at a uri element');
        }
        $this->currentUser = $userData;
        return true;
    }


} 

