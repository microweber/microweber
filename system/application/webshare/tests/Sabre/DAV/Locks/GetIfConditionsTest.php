<?php

require_once 'Sabre/HTTP/ResponseMock.php';
require_once 'Sabre/DAV/AbstractServer.php';

class Sabre_DAV_Locks_GetIfConditionsTest extends Sabre_DAV_AbstractServer {

    protected $locksPlugin;

    function setUp() {

        parent::setUp();
        $locksPlugin = new Sabre_DAV_Locks_Plugin();
        $this->server->addPlugin($locksPlugin);
        $this->locksPlugin = $locksPlugin;

    }

    function testNoConditions() {
        
        $serverVars = array(
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);

        $conditions = $this->locksPlugin->getIfConditions();
        $this->assertEquals(array(),$conditions);

    }

    function testLockToken() {

        $serverVars = array(
            'HTTP_IF' => '(<opaquelocktoken:token1>)',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);

        $conditions = $this->locksPlugin->getIfConditions();

        $compare = array(

            array(
                'uri' => '',
                'tokens' => array(
                    array(
                        1,
                        'opaquelocktoken:token1',
                        '',
                    ),
                ),

            ),

        );

        $this->assertEquals($compare,$conditions);

    }

    function testNotLockToken() {

        $serverVars = array(
            'HTTP_IF' => '(Not <opaquelocktoken:token1>)',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);

        $conditions = $this->locksPlugin->getIfConditions();

        $compare = array(

            array(
                'uri' => '',
                'tokens' => array(
                    array(
                        0,
                        'opaquelocktoken:token1',
                        '',
                    ),
                ),

            ),

        );
        $this->assertEquals($compare,$conditions);

    }

    function testLockTokenUrl() {

        $serverVars = array(
            'HTTP_IF' => '<http://www.example.com/> (<opaquelocktoken:token1>)',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);

        $conditions = $this->locksPlugin->getIfConditions();

        $compare = array(

            array(
                'uri' => 'http://www.example.com/',
                'tokens' => array(
                    array(
                        1,
                        'opaquelocktoken:token1',
                        '',
                    ),
                ),

            ),

        );
        $this->assertEquals($compare,$conditions);

    }

    function test2LockTokens() {

        $serverVars = array(
            'HTTP_IF' => '(<opaquelocktoken:token1>) (Not <opaquelocktoken:token2>)',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);

        $conditions = $this->locksPlugin->getIfConditions();

        $compare = array(

            array(
                'uri' => '',
                'tokens' => array(
                    array(
                        1,
                        'opaquelocktoken:token1',
                        '',
                    ),
                    array(
                        0,
                        'opaquelocktoken:token2',
                        '',
                    ),
                ),

            ),

        );
        $this->assertEquals($compare,$conditions);

    }

    function test2UriLockTokens() {

        $serverVars = array(
            'HTTP_IF' => '<http://www.example.org/node1> (<opaquelocktoken:token1>) <http://www.example.org/node2> (Not <opaquelocktoken:token2>)',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);

        $conditions = $this->locksPlugin->getIfConditions();

        $compare = array(

            array(
                'uri' => 'http://www.example.org/node1',
                'tokens' => array(
                    array(
                        1,
                        'opaquelocktoken:token1',
                        '',
                    ),
                 ),
            ),
            array(
                'uri' => 'http://www.example.org/node2',
                'tokens' => array(
                    array(
                        0,
                        'opaquelocktoken:token2',
                        '',
                    ),
                ),

            ),

        );
        $this->assertEquals($compare,$conditions);

    }

    function test2UriMultiLockTokens() {

        $serverVars = array(
            'HTTP_IF' => '<http://www.example.org/node1> (<opaquelocktoken:token1>) (<opaquelocktoken:token2>) <http://www.example.org/node2> (Not <opaquelocktoken:token3>)',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);

        $conditions = $this->locksPlugin->getIfConditions();

        $compare = array(

            array(
                'uri' => 'http://www.example.org/node1',
                'tokens' => array(
                    array(
                        1,
                        'opaquelocktoken:token1',
                        '',
                    ),
                    array(
                        1,
                        'opaquelocktoken:token2',
                        '',
                    ),
                 ),
            ),
            array(
                'uri' => 'http://www.example.org/node2',
                'tokens' => array(
                    array(
                        0,
                        'opaquelocktoken:token3',
                        '',
                    ),
                ),

            ),

        );
        $this->assertEquals($compare,$conditions);

    }

    function testEtag() {

        $serverVars = array(
            'HTTP_IF' => '([etag1])',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);

        $conditions = $this->locksPlugin->getIfConditions();

        $compare = array(

            array(
                'uri' => '',
                'tokens' => array(
                    array(
                        1,
                        '',
                        'etag1',
                    ),
                 ),
            ),

        );
        $this->assertEquals($compare,$conditions);

    }

    function test2Etags() {

        $serverVars = array(
            'HTTP_IF' => '<http://www.example.org/> ([etag1]) ([etag2])',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);

        $conditions = $this->locksPlugin->getIfConditions();

        $compare = array(

            array(
                'uri' => 'http://www.example.org/',
                'tokens' => array(
                    array(
                        1,
                        '',
                        'etag1',
                    ),
                    array(
                        1,
                        '',
                        'etag2',
                    ),
                 ),
            ),

        );
        $this->assertEquals($compare,$conditions);

    }

    function testComplexIf() {

        $serverVars = array(
            'HTTP_IF' => '<http://www.example.org/node1> (<opaquelocktoken:token1> [etag1]) ' .
                         '(Not <opaquelocktoken:token2>) ([etag2]) <http://www.example.org/node2> ' . 
                         '(<opaquelocktoken:token3>) (Not <opaquelocktoken:token4>) ([etag3])',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);

        $conditions = $this->locksPlugin->getIfConditions();

        $compare = array(

            array(
                'uri' => 'http://www.example.org/node1',
                'tokens' => array(
                    array(
                        1,
                        'opaquelocktoken:token1',
                        'etag1',
                    ),
                    array(
                        0,
                        'opaquelocktoken:token2',
                        '',
                    ),
                    array(
                        1,
                        '',
                        'etag2',
                    ),
                 ),
            ),
            array(
                'uri' => 'http://www.example.org/node2',
                'tokens' => array(
                    array(
                        1,
                        'opaquelocktoken:token3',
                        '',
                    ),
                    array(
                        0,
                        'opaquelocktoken:token4',
                        '',
                    ),
                    array(
                        1,
                        '',
                        'etag3',
                    ),
                 ),
            ),

        );
        $this->assertEquals($compare,$conditions);

    }

}

?>
