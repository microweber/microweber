<?php namespace Phroute;

use ReflectionClass;
use ReflectionMethod;

use Phroute\Exception\BadRouteException;

class RouteCollector {
    
    const DEFAULT_CONTROLLER_ROUTE = 'index';
    
    const APPROX_CHUNK_SIZE = 10;
    
    private $routeParser;
    private $filters;
    private $staticRoutes = [];
    private $regexToRoutesMap = [];
    private $reverse = [];
    
    private $globalFilters = array();
    
    public function __construct(RouteParser $routeParser = null) {
        $this->routeParser = $routeParser ?: new RouteParser();
    }
    
    public function route($name, $args = array())
    {
        $replacements = (array) $args;
        
        return count($replacements) ? preg_replace(array_fill(0, count($replacements), '/\{[^\{\}\/]+\}/'), $replacements, $this->reverse[$name], 1) : $this->reverse[$name];
    }

    public function addRoute($httpMethod, $route, $handler, array $filters = array()) {
        
        if(is_array($route))
        {
            list($route, $name) = $route;
        }
        
        list($routeData, $reverseData) = $this->routeParser->parse(trim($route , '/'));
        
        if(isset($name))
        {
            $this->reverse[$name] = $reverseData;
        }
        
        $filters = array_merge_recursive($this->globalFilters, $filters);

        isset($routeData[1]) ? 
            $this->addVariableRoute($httpMethod, $routeData, $handler, $filters) :
            $this->addStaticRoute($httpMethod, $routeData, $handler, $filters);
        
        return $this;
    }
    
    private function addStaticRoute($httpMethod, $routeData, $handler, $filters)
    {
        $routeStr = $routeData[0];

        if (isset($this->staticRoutes[$routeStr][$httpMethod]))
        {
            throw new BadRouteException("Cannot register two routes matching '$routeStr' for method '$httpMethod'");
        }

        foreach ($this->regexToRoutesMap as $regex => $routes) {
            if (isset($routes[$httpMethod]) && preg_match('~^' . $regex . '$~', $routeStr))
            {
                throw new BadRouteException("Static route '$routeStr' is shadowed by previously defined variable route '$regex' for method '$httpMethod'");
            }
        }

        $this->staticRoutes[$routeStr][$httpMethod] = array($handler, $filters, array());
    }

    private function addVariableRoute($httpMethod, $routeData, $handler, $filters)
    {
        list($regex, $variables) = $routeData;

        if (isset($this->regexToRoutesMap[$regex][$httpMethod]))
        {
            throw new BadRouteException("Cannot register two routes matching '$regex' for method '$httpMethod'");
        }

        $this->regexToRoutesMap[$regex][$httpMethod] = array($handler, $filters, $variables);
    }
    
    public function group(array $filters, \Closure $callback)
    {
        $oldGlobal = $this->globalFilters;
        $this->globalFilters = array_merge_recursive($this->globalFilters, array_intersect_key($filters, array(Route::AFTER => 1, Route::BEFORE => 1)));
        $callback($this);
        $this->globalFilters = $oldGlobal;
    }

    public function filter($name, $handler)
    {
        $this->filters[$name] = $handler;
    }
    
    public function get($route, $handler, array $filters = array())
    {
        return $this->addRoute(Route::GET, $route, $handler, $filters);
    }
    
    public function head($route, $handler, array $filters = array())
    {
        return $this->addRoute(Route::HEAD, $route, $handler, $filters);
    }
    
    public function post($route, $handler, array $filters = array())
    {
        return $this->addRoute(Route::POST, $route, $handler, $filters);
    }
    
    public function put($route, $handler, array $filters = array())
    {
        return $this->addRoute(Route::PUT, $route, $handler, $filters);
    }
    
    public function delete($route, $handler, array $filters = array())
    {
        return $this->addRoute(Route::DELETE, $route, $handler, $filters);
    }
    
    public function options($route, $handler, array $filters = array())
    {
        return $this->addRoute(Route::OPTIONS, $route, $handler, $filters);
    }
    
    public function any($route, $handler, array $filters = array())
    {
        return $this->addRoute(Route::ANY, $route, $handler, $filters);
    }

    public function getFilters() 
    {
        return $this->filters;
    }
    
    public function controller($route, $classname)
    {
        $reflection = new ReflectionClass($classname);

        $validMethods = $this->getValidMethods();
        
        foreach($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
        {   
            foreach($validMethods as $valid)
            {
                if(stripos($method->name, $valid) === 0)
                {
                    $methodName = strtolower(substr($method->name, strlen($valid)));
                    
                    if($methodName === self::DEFAULT_CONTROLLER_ROUTE)
                    {
                        $this->addRoute($valid, $route, array($classname, $method->name));
                    }
                    
                    $sep = $route === '/' ? '' : '/';
                    
                    $this->addRoute($valid, $route . $sep . $methodName, array($classname, $method->name));
                    
                    break;
                }
            }
        }
        
        return $this;
    }
    
    public function getValidMethods()
    {
        return array(
            Route::ANY,
            Route::GET,
            Route::POST,
            Route::PUT,
            Route::DELETE,
            Route::HEAD,
            Route::OPTIONS,
        );
    }
    
    public function getData()
    {
        if (empty($this->regexToRoutesMap))
        {
            return [$this->staticRoutes, []];
        }

        return [$this->staticRoutes, $this->generateVariableRouteData()];
    }

    private function generateVariableRouteData()
    {
        $chunkSize = $this->computeChunkSize(count($this->regexToRoutesMap));
        $chunks = array_chunk($this->regexToRoutesMap, $chunkSize, true);
        return array_map(array($this, 'processChunk'), $chunks);
    }

    private function computeChunkSize($count)
    {
        $numParts = max(1, round($count / self::APPROX_CHUNK_SIZE));
        return ceil($count / $numParts);
    }

    private function processChunk($regexToRoutesMap)
    {
        $routeMap = [];
        $regexes = [];
        $numGroups = 0;
        foreach ($regexToRoutesMap as $regex => $routes) {
            $firstRoute = reset($routes);
            $numVariables = count($firstRoute[2]);
            $numGroups = max($numGroups, $numVariables);

            $regexes[] = $regex . str_repeat('()', $numGroups - $numVariables);

            foreach ($routes as $httpMethod => $route) {
                $routeMap[$numGroups + 1][$httpMethod] = $route;
            }

            $numGroups++;
        }

        $regex = '~^(?|' . implode('|', $regexes) . ')$~';
        return ['regex' => $regex, 'routeMap' => $routeMap];
    }
}
