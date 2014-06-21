<?php

namespace Phroute\Dispatcher;

use Phroute\RouteCollector;
use Phroute\RouteParser;
use Phroute\Dispatcher;
use Phroute\Route;

class Test {
    
    public function route()
    {
        return 'testRoute';
    }
    
    public function anyIndex()
    {
        return 'testRoute';
    }
    
    public function anyTest()
    {
        return 'testRoute';
    }
    
    public function getTest()
    {
        return 'testRoute';
    }
    
    public function postTest()
    {
        return 'testRoute';
    }
    
    public function putTest()
    {
        return 'testRoute';
    }

    public function deleteTest()
    {
        return 'testRoute';
    }
    
    public function headTest()
    {
        return 'testRoute';
    }
    
    public function optionsTest()
    {
        return 'testRoute';
    }
    
    
}

class DispatcherTest extends \PHPUnit_Framework_TestCase {

    /**
     * Set appropriate options for the specific Dispatcher class we're testing
     */
    private function router()
    {
        return new RouteCollector(new RouteParser);
    }

    private function dispatch($router, $method, $uri)
    {
        return (new Dispatcher($router))->dispatch($method, $uri);
    }

    /**
     * @dataProvider provideFoundDispatchCases
     */
    public function testFoundDispatches($method, $uri, $callback, $expected)
    {
        $r = $this->router();
        $callback($r);
        $response = $this->dispatch($r, $method, $uri);
        $this->assertEquals($expected, $response);
    }

    /**
     * @dataProvider provideNotFoundDispatchCases
     * @expectedException \Phroute\Exception\HttpRouteNotFoundException
     * @expectedExceptionMessage does not exist
     */
    public function testNotFoundDispatches($method, $uri, $callback)
    {
        $r = $this->router();
        $callback($r);
        $this->dispatch($r, $method, $uri);
    }

    /**
     * @dataProvider provideMethodNotAllowedDispatchCases
     * @expectedException \Phroute\Exception\HttpMethodNotAllowedException
     * @expectedExceptionMessage Allowed routes
     */
    public function testMethodNotAllowedDispatches($method, $uri, $callback)
    {
        $r = $this->router();
        $callback($r);
        $this->dispatch($r, $method, $uri);
    }
    
    public function testStringObjectIsDispatched()
    {
        $r = $this->router();
        
        $r->addRoute('GET', '/foo', array(__NAMESPACE__.'\\Test','route'));
        
        $response = $this->dispatch($r, 'GET', '/foo');
                
        $this->assertEquals('testRoute',$response);
    }
    
    public function testNamedRoutes()
    {
        $r = $this->router();
        
        $r->addRoute('GET', array('/foo', 'name'), array(__NAMESPACE__.'\\Test','route'));
                
        $this->assertEquals('foo',$r->route('name'));
        
        $r->addRoute('GET', array('/foo/{name}/{something:i}', 'name2'), array(__NAMESPACE__.'\\Test','route'));
                
        $this->assertEquals('foo/joe/something',$r->route('name2', ['joe', 'something']));
    }

    /**
     * @expectedException \Phroute\Exception\BadRouteException
     * @expectedExceptionMessage Cannot use the same placeholder 'test' twice
     */
    public function testDuplicateVariableNameError()
    {
        $this->router()->addRoute('GET', '/foo/{test}/{test:\d+}', function() {
            
        });
    }

    /**
     * @expectedException \Phroute\Exception\BadRouteException
     * @expectedExceptionMessage Cannot register two routes matching 'user/([^/]+)' for method 'GET'
     */
    public function testDuplicateVariableRoute()
    {
        $r = $this->router();
        $r->addRoute('GET', '/user/{id}', function() {
            
        }); // oops, forgot \d+ restriction ;)
        $r->addRoute('GET', '/user/{name}', function() {
            
        });
    }

    /**
     * @expectedException \Phroute\Exception\BadRouteException
     * @expectedExceptionMessage Cannot register two routes matching 'user' for method 'GET'
     */
    public function testDuplicateStaticRoute()
    {
        $r = $this->router();
        $r->addRoute('GET', '/user', function() {
            
        });
        $r->addRoute('GET', '/user', function() {
            
        });
    }

    /**
     * @expectedException \Phroute\Exception\BadRouteException
     * @expectedExceptionMessage Static route 'user/nikic' is shadowed by previously defined variable route 'user/([^/]+)' for method 'GET'
     */
    public function testShadowedStaticRoute()
    {
        $r = $this->router();
        $r->addRoute('GET', '/user/{name}', function() {
            
        });
        $r->addRoute('GET', '/user/nikic', function() {
            
        });
    }

    public function testBeforeFilters()
    {
        $r = $this->router();

        $dispatchedFilter = false;
        
        $r->filter('test', function() use(&$dispatchedFilter){
            $dispatchedFilter = true;
        });

        $r->addRoute('GET', '/user', function() {
            return 'dispatched';
        }, array('before' => 'test'));

        $this->assertEquals('dispatched', $this->dispatch($r, 'GET', '/user'));
        
        $this->assertTrue($dispatchedFilter);
    }
    
    public function testBeforeFiltersStringClass()
    {
        $r = $this->router();
        
        $r->filter('test', array(__NAMESPACE__ . '\Test','route'));

        $r->addRoute('GET', '/user', function() {}, array('before' => 'test'));

        $this->assertEquals('testRoute', $this->dispatch($r, 'GET', '/user'));
    }

    public function testBeforeFilterCancels()
    {
        $r = $this->router();
        
        $r->filter('test', function(){            
            return 'cancel';
        });

        $r->addRoute('GET', '/user', function() {
            return 'dispatched';
        }, array('before' => 'test'));

        $this->assertEquals('cancel', $this->dispatch($r, 'GET', '/user'));
    }
    
    
    public function testAfterFilters()
    {
        $r = $this->router();

        $dispatchedFilter = false;
        
        $r->filter('test', function($response) use(&$dispatchedFilter){
            $dispatchedFilter = true;
            
            return $response . ' filtered';
        });

        $r->addRoute('GET', '/user', function() {
            return 'test';
        }, array('after' => 'test'));

        $response = $this->dispatch($r, 'GET', '/user');
        
        $this->assertTrue($dispatchedFilter);
        
        $this->assertEquals('test filtered', $response);
    }
    
    public function testFilterGroups()
    {
        $r = $this->router();
        
        $dispatchedFilter = 0;
        $dispatchedFilter2 = 0;
        
        $r->filter('test', function() use(&$dispatchedFilter){
            $dispatchedFilter++;
        });
        
        $r->filter('test2', function() use(&$dispatchedFilter2){
            $dispatchedFilter2++;
        });
        
        $r->group(array('before' => 'test'), function($router){
            $router->addRoute('GET', '/user', function() {
            
            });
            $router->group(array('before' => 'test2'), function($router){
                $router->addRoute('GET', '/user2', function() {

                });
            });
        });
        
        $this->dispatch($r, 'GET', '/user');
        
        $this->assertEquals(1, $dispatchedFilter);
        
        $this->dispatch($r, 'GET', '/user2');
        
        $this->assertEquals(2, $dispatchedFilter);
        $this->assertEquals(1, $dispatchedFilter2);
        
    }

    public function testValidMethods()
    {
        $this->assertEquals(array(
            Route::ANY,
            Route::GET,
            Route::POST,
            Route::PUT,
            Route::DELETE,
            Route::HEAD,
            Route::OPTIONS,
        ), $this->router()->getValidMethods());
    }
    
    public function testRestfulControllerMethods()
    {
        
        $r = $this->router();
        
        $r->controller('/user', __NAMESPACE__ . '\\Test');
        
        $data = $r->getData();
        
        $this->assertEquals($r->getValidMethods(), array_keys($data[0]['user/test']));
        
        $this->assertEquals(array(Route::ANY), array_keys($data[0]['user']));
        $this->assertEquals(array(Route::ANY), array_keys($data[0]['user/index']));
    }
    
    public function testRestfulMethods()
    {
        
        $r = $this->router();
        
        $methods = $r->getValidMethods();
        
        foreach($methods as $method)
        {
            $r->$method('/user','callback');
        }
        
        $data = $r->getData();
        
        $this->assertEquals($methods, array_keys($data[0]['user']));
    }
    
    public function provideFoundDispatchCases()
    {
        $cases = [];
        
         // 0 -------------------------------------------------------------------------------------->

        $callback = function($r) {
            $r->addRoute('GET', '/', function() {
                return true;
            });
        };
        
        $cases[] = ['GET', '', $callback, true];

        $cases[] = ['GET', '/', $callback, true];
        
        
        $callback = function($r) {
            $r->addRoute('GET', '', function() {
                return true;
            });
        };
        
        $cases[] = ['GET', '', $callback, true];

        $cases[] = ['GET', '/', $callback, true];

        // 0 -------------------------------------------------------------------------------------->

        $callback = function($r) {
            $r->addRoute('GET', '/resource/123/456', function() {
                return true;
            });
        };

        $cases[] = ['GET', '/resource/123/456', $callback, true];
        
        
        
        $callback = function($r) {
            $r->addRoute('GET', 'resource/123/456', function() {
                return true;
            });
        };

        $cases[] = ['GET', 'resource/123/456', $callback, true];
        
        
        $callback = function($r) {
            $r->addRoute('GET', 'resource/123/456', function() {
                return true;
            });
        };

        $cases[] = ['GET', '/resource/123/456', $callback, true];

        // 1 -------------------------------------------------------------------------------------->

        $callback = function($r) {
            $r->addRoute('GET', '/handler0', function() {
                
            });
            $r->addRoute('GET', '/handler1', function() {
                
            });
            $r->addRoute('GET', '/handler2', function() {
                return true;
            });
        };
        $cases[] = ['GET', '/handler2', $callback, true];

        // 2 -------------------------------------------------------------------------------------->

        $callback = function($r) {
            $r->addRoute('GET', '/user/{name}/{id:[0-9]+}', function($name, $id) {
                return [$name, $id];
            });
            $r->addRoute('GET', '/user/{id:[0-9]+}', function($id) {
                return $id;
            });
            $r->addRoute('GET', '/user/{name}', function($name) {
                return $name;
            });
        };

        $cases[] = ['GET', '/user/rdlowrey', $callback, 'rdlowrey'];


        // 3 -------------------------------------------------------------------------------------->
        // reuse $callback from #2

        $cases[] = ['GET', '/user/12345', $callback, '12345'];

        // 4 -------------------------------------------------------------------------------------->
        // reuse $callback from #3

        $cases[] = ['GET', '/user/NaN', $callback, 'NaN'];

        // 5 -------------------------------------------------------------------------------------->
        // reuse $callback from #4
        $cases[] = ['GET', '/user/rdlowrey/12345', $callback, ['rdlowrey', '12345']];

        // 6 -------------------------------------------------------------------------------------->

        $callback = function( $r) {
            $r->addRoute('GET', '/user/{id:[0-9]+}', function() {
                
            });
            $r->addRoute('GET', '/user/12345/extension', function() {
                
            });
            $r->addRoute('GET', '/user/{id:[0-9]+}.{extension}', function($id, $extension) {
                return [$id, $extension];
            });
        };

        $cases[] = ['GET', '/user/12345.svg', $callback, ['12345', 'svg']];

        // 7 ----- Test GET method fallback on HEAD route miss ------------------------------------>

        $callback = function($r) {
            $r->addRoute('GET', '/user/{name}', function($name) {
                return $name;
            });
            $r->addRoute('GET', '/user/{name}/{id:[0-9]+}', function($name, $id) {
                return [$name, $id];
            });
            $r->addRoute('GET', '/static0', function() {
                return 'static0';
            });
            $r->addRoute('GET', '/static1', function() {
                
            });
            $r->addRoute('HEAD', '/static1', function() {
                return 'static1head';
            });
        };

        $cases[] = ['HEAD', '/user/rdlowrey', $callback, 'rdlowrey'];

        // 8 ----- Test GET method fallback on HEAD route miss ------------------------------------>
        // reuse $callback from #7
        $cases[] = ['HEAD', '/user/rdlowrey/1234', $callback, ['rdlowrey', '1234']];

        // 9 ----- Test GET method fallback on HEAD route miss ------------------------------------>
        // reuse $callback from #8

        $cases[] = ['HEAD', '/static0', $callback, 'static0'];

        // 10 ---- Test existing HEAD route used if available (no fallback) ----------------------->
        // reuse $callback from #9

        $cases[] = ['HEAD', '/static1', $callback, 'static1head'];

        // x -------------------------------------------------------------------------------------->

        
        // 11 -------------------------------------------------------------------------------------->
        // Test optional parameter
        $callback = function($r) {
            $r->addRoute('GET', '/user/{name}/{id:[0-9]+}?', function($name, $id = null) {
                return [$name, $id];
            });
        };

        $cases[] = ['GET', '/user/rdlowrey', $callback, array('rdlowrey', null)];
        
        // 12
        $cases[] = ['GET', '/user/rdlowrey/23', $callback, array('rdlowrey', '23')];
        
        // 13 -------------------------------------------------------------------------------------->
        // Test multiple optional parameters
        $callback = function($r) {
            $r->addRoute('GET', '/user/{name}/{id:[0-9]+}?/{other}?', function($name, $id = null, $other = null) {
                return [$name, $id, $other];
            });
        };

        $cases[] = ['GET', '/user/rdlowrey', $callback, array('rdlowrey', null, null)];
        
        // 14
        $cases[] = ['GET', '/user/rdlowrey/23', $callback, array('rdlowrey', '23', null)];
        
        //15
        $cases[] = ['GET', '/user/rdlowrey/23/blah', $callback, array('rdlowrey', '23', 'blah')];
        

        $callback = function($r) {
            $r->addRoute('GET', '/user/random_{name}', function($name) {
                return $name;
            });
        };

        //16
        $cases[] = ['GET', '/user/random_rdlowrey', $callback, 'rdlowrey'];
        
        
        $callback = function($r) {
            $r->addRoute('GET', '/user/random_{name}?', function($name = null) {
                return $name;
            });
        };

        //17
        $cases[] = ['GET', '/user/random_rdlowrey', $callback, 'rdlowrey'];
         //18
        $cases[] = ['GET', '/user/random_', $callback, null];
        
        $callback = function($r) {
            $r->addRoute('GET', '{name}?', function($name = null) {
                return $name;
            });
        };

        //19
        $cases[] = ['GET', 'rdlowrey', $callback, 'rdlowrey'];
         //20
        $cases[] = ['GET', '/', $callback, null];
        
        // 11 -------------------------------------------------------------------------------------->
        // Test shortcuts parameter
        $callback = function($r) {
            $r->addRoute('GET', '/user/{id:i}', function($id) {
                return $id;
            });
            $r->addRoute('GET', '/user1/{idname:a}', function($idname) {
                return array($idname);
            });
            $r->addRoute('GET', '/user2/{hexcode:h}', function($hexcode) {
                return array($hexcode);
            });
            $r->addRoute('GET', '/user3/{id:i}/{hexcode:h}?', function($id, $hexcode = null) {
                return array($id, $hexcode);
            });
        };

        $cases[] = ['GET', '/user/21', $callback, '21'];
        $cases[] = ['GET', '/user1/abcdezzASd123', $callback, array('abcdezzASd123')];
        $cases[] = ['GET', '/user2/abcde123', $callback, array('abcde123')];
        $cases[] = ['GET', '/user3/21/abcde123', $callback, array('21','abcde123')];
        $cases[] = ['GET', '/user3/21', $callback, array('21', null)];
        
        
        
        // 11 -------------------------------------------------------------------------------------->
        // Test shortcuts parameter
        $callback = function($r) {
            $r->addRoute('GET', '/user/{id}?/{id2}?/{id3}?', function() {
                return 'first';
            });
            $r->addRoute('GET', '/user2/{id}?', function() {
                return 'second';
            });
            $r->addRoute('GET', '/user3/{id}?', function() {
                return 'third';
            });
            $r->addRoute('GET', '/user4/{id}?/{id2}?/{id3}?', function() {
                return 'fourth';
            });
        };

        $cases[] = ['GET', '/user/21', $callback, 'first'];
        $cases[] = ['GET', '/user2/abcdezzASd123', $callback, 'second'];
        $cases[] = ['GET', '/user2/abcde123', $callback, 'second'];
        $cases[] = ['GET', '/user/21/abcde123', $callback, 'first'];
        $cases[] = ['GET', '/user2/21', $callback, 'second'];
        $cases[] = ['GET', '/user3/abcdezzASd123', $callback, 'third'];
        $cases[] = ['GET', '/user3/abcde123', $callback, 'third'];
        $cases[] = ['GET', '/user3/21', $callback, 'third'];
        $cases[] = ['GET', '/user4/abcdezzASd123', $callback, 'fourth'];
        $cases[] = ['GET', '/user4/abcde123', $callback, 'fourth'];
        $cases[] = ['GET', '/user4/21', $callback, 'fourth'];
        
        // 11 -------------------------------------------------------------------------------------->
        // Test shortcuts parameter
        $callback = function($r) {
            $r->addRoute('GET', 'ext/{asset}.json', function($asset) {
                return $asset . ' jsonencoded';
            });
            $r->addRoute('GET', 'ext/{asset}', function($asset) {
                return $asset;
            });
        };

        $cases[] = ['GET', 'ext/asset', $callback, 'asset'];
        $cases[] = ['GET', 'ext/asset.json', $callback, 'asset jsonencoded'];
        
        return $cases;
    }

    public function provideNotFoundDispatchCases()
    {
        $cases = [];

        // 0 -------------------------------------------------------------------------------------->

        $callback = function( $r) {
            $r->addRoute('GET', '/resource/123/456', 'handler0');
        };

        $method = 'GET';
        $uri = '/not-found';

        $cases[] = [$method, $uri, $callback];

        // 1 -------------------------------------------------------------------------------------->
        // reuse callback from #0
        $method = 'POST';
        $uri = '/not-found';

        $cases[] = [$method, $uri, $callback];

        // 2 -------------------------------------------------------------------------------------->
        // reuse callback from #1
        $method = 'PUT';
        $uri = '/not-found';

        $cases[] = [$method, $uri, $callback];

        // 3 -------------------------------------------------------------------------------------->

        $callback = function( $r) {
            $r->addRoute('GET', '/handler0', 'handler0');
            $r->addRoute('GET', '/handler1', 'handler1');
            $r->addRoute('GET', '/handler2', 'handler2');
        };

        $method = 'GET';
        $uri = '/not-found';

        $cases[] = [$method, $uri, $callback];

        // 4 -------------------------------------------------------------------------------------->

        $callback = function( $r) {
            $r->addRoute('GET', '/user/{name}/{id:[0-9]+}', 'handler0');
            $r->addRoute('GET', '/user/{id:[0-9]+}', 'handler1');
            $r->addRoute('GET', '/user/{name}', 'handler2');
        };

        $method = 'GET';
        $uri = '/not-found';

        $cases[] = [$method, $uri, $callback];

        // 5 -------------------------------------------------------------------------------------->
        // reuse callback from #4
        $method = 'GET';
        $uri = '/user/rdlowrey/12345/not-found';

        $cases[] = [$method, $uri, $callback];

        // x -------------------------------------------------------------------------------------->

        $callback = function($r) {
            $r->addRoute('GET', '/user/random_{name}?', function($name = null) {
                return $name;
            });
        };

        //17
        $cases[] = ['GET', 'rdlowrey', $callback];
        
        //19
        $cases[] = ['GET', '/user/rdlowrey', $callback, null];
        
        return $cases;
    }

    public function provideMethodNotAllowedDispatchCases()
    {
        $cases = [];

        // 0 -------------------------------------------------------------------------------------->

        $callback = function( $r) {
            $r->addRoute('GET', '/resource/123/456', 'handler0');
        };

        $method = 'POST';
        $uri = '/resource/123/456';
        $allowedMethods = ['GET'];

        $cases[] = [$method, $uri, $callback, $allowedMethods];

        // 1 -------------------------------------------------------------------------------------->

        $callback = function( $r) {
            $r->addRoute('GET', '/resource/123/456', 'handler0');
            $r->addRoute('POST', '/resource/123/456', 'handler1');
            $r->addRoute('PUT', '/resource/123/456', 'handler2');
        };

        $method = 'DELETE';
        $uri = '/resource/123/456';
        $allowedMethods = ['GET', 'POST', 'PUT'];

        $cases[] = [$method, $uri, $callback, $allowedMethods];

        // 2 -------------------------------------------------------------------------------------->

        $callback = function( $r) {
            $r->addRoute('GET', '/user/{name}/{id:[0-9]+}', 'handler0');
            $r->addRoute('POST', '/user/{name}/{id:[0-9]+}', 'handler1');
            $r->addRoute('PUT', '/user/{name}/{id:[0-9]+}', 'handler2');
            $r->addRoute('PATCH', '/user/{name}/{id:[0-9]+}', 'handler3');
        };

        $method = 'DELETE';
        $uri = '/user/rdlowrey/42';
        $allowedMethods = ['GET', 'POST', 'PUT', 'PATCH'];

        $cases[] = [$method, $uri, $callback, $allowedMethods];

        // x -------------------------------------------------------------------------------------->

        return $cases;
    }

}
