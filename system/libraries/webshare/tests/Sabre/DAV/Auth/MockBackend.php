<?php

class Sabre_DAV_Auth_MockBackend extends Sabre_DAV_Auth_Backend_Abstract {

    protected $currentUser;

    function authenticate(Sabre_DAV_Server $server, $realm) {

        if ($realm=='failme') throw new Sabre_DAV_Exception_NotAuthenticated('deliberate fail'); 

        $this->currentUser = array(
            'uri' => 'principals/admin',
        );
        return true;

    }

    function setCurrentUser($userUri) {

        $this->currentUser = array('uri' => $userUri);

    }

    function getCurrentUser() {

        return $this->currentUser;

    }

    function getUsers() {

        return array(
            array(
                'uri' => 'principals/admin',
            ),
            array(
                'uri' => 'principals/user1',
            ),
        );

    }

}
