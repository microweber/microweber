<?php


namespace MicroweberPackages\OpenApi\Models;


use Mtrajano\LaravelSwagger\Generator;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\DocBlockFactory;
use Mtrajano\LaravelSwagger\DataObjects;
use ReflectionMethod;
use Mtrajano\LaravelSwagger\Parameters\PathParameterGenerator;
use Mtrajano\LaravelSwagger\Parameters;
use Mtrajano\LaravelSwagger\LaravelSwaggerException;

class SwGen
{
    const SECURITY_DEFINITION_NAME = 'OAuth2';
    const OAUTH_TOKEN_PATH = '/oauth/token';
    const OAUTH_AUTHORIZE_PATH = '/oauth/authorize';

    protected $config;
    protected $routeFilter;
    protected $docs;
    protected $route;
    protected $method;
    protected $docParser;
    protected $hasSecurityDefinitions;

    public function __construct($config, $routeFilter = null)
    {
        $this->config = $config;
        $this->routeFilter = $routeFilter;
        $this->docParser = DocBlockFactory::createInstance();
        $this->hasSecurityDefinitions = false;
    }

    public function generate()
    {
        $this->docs = $this->getBaseInfo();

        if ($this->config['parseSecurity'] && $this->hasOauthRoutes()) {
            $this->docs['securityDefinitions'] = $this->generateSecurityDefinitions();
            $this->hasSecurityDefinitions = true;
        }

        foreach ($this->getAppRoutes() as $route) {
            $this->route = $route;

            if ($this->routeFilter && $this->isFilteredRoute()) {
                continue;
            }

            if (!isset($this->docs['paths'][$this->route->uri()])) {
                $this->docs['paths'][$this->route->uri()] = [];
            }

            foreach ($route->methods() as $method) {
                $this->method = $method;

                if (in_array($this->method, $this->config['ignoredMethods'])) {
                    continue;
                }

                $this->generatePath();
            }
        }

        return $this->docs;
    }

    protected function getBaseInfo()
    {
        $baseInfo = [
            'swagger' => '2.0',
            'info' => [
                'title' => $this->config['title'],
                'description' => $this->config['description'],
                'version' => $this->config['appVersion'],
            ],
            'host' => $this->config['host'],
            'basePath' => $this->config['basePath'],
        ];

        if (!empty($this->config['schemes'])) {
            $baseInfo['schemes'] = $this->config['schemes'];
        }

        if (!empty($this->config['consumes'])) {
            $baseInfo['consumes'] = $this->config['consumes'];
        }

        if (!empty($this->config['produces'])) {
            $baseInfo['produces'] = $this->config['produces'];
        }

        $baseInfo['paths'] = [];

        return $baseInfo;
    }

    protected function getAppRoutes()
    {
        return array_map(function ($route) {
            return new RouteDTO($route);
        }, app('router')->getRoutes()->getRoutes());
    }

    protected function generateSecurityDefinitions()
    {
        $authFlow = $this->config['authFlow'];

        $this->validateAuthFlow($authFlow);

        $securityDefinition = [
            self::SECURITY_DEFINITION_NAME => [
                'type' => 'oauth2',
                'flow' => $authFlow,
            ],
        ];

        if (in_array($authFlow, ['implicit', 'accessCode'])) {
            $securityDefinition[self::SECURITY_DEFINITION_NAME]['authorizationUrl'] = $this->getEndpoint(self::OAUTH_AUTHORIZE_PATH);
        }

        if (in_array($authFlow, ['password', 'application', 'accessCode'])) {
            $securityDefinition[self::SECURITY_DEFINITION_NAME]['tokenUrl'] = $this->getEndpoint(self::OAUTH_TOKEN_PATH);
        }

        $securityDefinition[self::SECURITY_DEFINITION_NAME]['scopes'] = $this->generateOauthScopes();

        return $securityDefinition;
    }

    protected function generatePath()
    {
        $actionInstance = $this->getActionClassInstance();
        $docBlock = $actionInstance ? ($actionInstance->getDocComment() ?: '') : '';

        [$isDeprecated, $summary, $description] = $this->parseActionDocBlock($docBlock);

        $this->docs['paths'][$this->route->uri()][$this->method] = [
            'summary' => $summary,
            'description' => $description,
            'deprecated' => $isDeprecated,
            'responses' => [
                '200' => [
                    'description' => 'OK',
                ],
            ],
        ];

        $this->addActionParameters();

        if ($this->hasSecurityDefinitions) {
            $this->addActionScopes();
        }
    }

    protected function addActionParameters()
    {
        $rules = $this->getFormRules() ?: [];

        $parameters = (new Parameters\PathParameterGenerator($this->route->originalUri()))->getParameters();

        if (!empty($rules)) {
            $parameterGenerator = $this->getParameterGenerator($rules);

            $parameters = array_merge($parameters, $parameterGenerator->getParameters());
        }

        if (!empty($parameters)) {

            $action_name = $this->route->getRoute()->getActionName();

            $error = false;
            $comments = false;

            $try_get_summary = explode('@', $action_name);


        if (isset($try_get_summary[0]) and $try_get_summary[0]) {
                if (isset($try_get_summary[1]) and $try_get_summary[1]) {
                    try {
                        $rc = new \ReflectionClass($try_get_summary[0]);
                        $comments = $rc->getMethod($try_get_summary[1])->getDocComment();

                    } catch (\ReflectionException $exception) {
                        $error = true;
                    }


                    if (!$error and $comments) {
                        $dbp = new AnnotationParser();
                        $comments_annotations_parsed = $dbp->getAnnotations($comments);

                        if($comments_annotations_parsed and isset($comments_annotations_parsed['param'])){
                            $parsed_from_a = $this->_makeParametersFromAnnotations($comments_annotations_parsed['param']);
                            if($parsed_from_a){
                              //  $parameters = array_merge($parameters, $parsed_from_a);
                            }
                        }

                    }
                }
            }


            $name = strtolower(($this->route->getRoute()->getName()));


            $tags = explode('.', $name);
            $remove_last = array_pop($tags);


            $this->docs['paths'][$this->route->uri()][$this->method]['parameters'] = $parameters;
            $this->docs['paths'][$this->route->uri()][$this->method]['description'] = $this->route->action();
            $this->docs['paths'][$this->route->uri()][$this->method]['tags'] = [implode('.', $tags)];


        }
    }

    protected function addActionScopes()
    {
        foreach ($this->route->middleware() as $middleware) {
            if ($this->isPassportScopeMiddleware($middleware)) {
                $this->docs['paths'][$this->route->uri()][$this->method]['security'] = [
                    self::SECURITY_DEFINITION_NAME => $middleware->parameters(),
                ];
            }
        }
    }

    protected function getFormRules(): array
    {
        $action_instance = $this->getActionClassInstance();

        if (!$action_instance) {
            return [];
        }

        $parameters = $action_instance->getParameters();

        foreach ($parameters as $parameter) {
            try {
                $class = $parameter->getClass();
            } catch (\ReflectionException $exception) {
                // Output expected ReflectionException.
                $class = null;
            }


            if (!$class) {
                continue;
            }
            $error = false;
            $class_name = $class->getName();
            $rc = new \ReflectionClass($class_name);
            try {
                $comments = $rc->getMethod('rules')->getDocComment();

            } catch (\ReflectionException $exception) {
                // Output expected ReflectionException.
                $error = true;
            }


            if (!$error) {
                $rules = (new $class_name)->rules();
                if (!$rules) {
                    $rules = [];
                }
                return $rules;

            }

            // if (is_subclass_of($class_name, FormRequest::class)) {
            // }


        }

        return [];
    }

    protected function getParameterGenerator($rules)
    {
        switch ($this->method) {
            case 'head':
                break;
            case 'post':
            case 'put':
            case 'patch':
                return new Parameters\BodyParameterGenerator($rules);
            default:
                return new Parameters\QueryParameterGenerator($rules);
        }
    }

    private function getActionClassInstance(): ?ReflectionMethod
    {
        [$class, $method] = Str::parseCallback($this->route->action());


        if (!$class || !$method) {
            return null;
        }

        // return new ReflectionMethod($class, $method);
        try {
            $rc = new ReflectionMethod($class, $method);;

        } catch (\ReflectionException $exception) {
            return null;

        }

        return $rc;

    }

    private function parseActionDocBlock(string $docBlock)
    {
        if (empty($docBlock) || !$this->config['parseDocBlock']) {
            return [false, '', ''];
        }

        try {
            $parsedComment = $this->docParser->create($docBlock);

            $isDeprecated = $parsedComment->hasTag('deprecated');

            $summary = $parsedComment->getSummary();
            $description = (string)$parsedComment->getDescription();

            return [$isDeprecated, $summary, $description];
        } catch (\Exception $e) {
            return [false, '', ''];
        }
    }

    private function isFilteredRoute()
    {
        return !preg_match('/^' . preg_quote($this->routeFilter, '/') . '/', $this->route->uri());
    }

    /**
     * Assumes routes have been created using Passport::routes().
     */
    private function hasOauthRoutes()
    {
        foreach ($this->getAppRoutes() as $route) {
            $uri = $route->uri();

            if ($uri === self::OAUTH_TOKEN_PATH || $uri === self::OAUTH_AUTHORIZE_PATH) {
                return true;
            }
        }

        return false;
    }

    private function getEndpoint(string $path)
    {
        return rtrim($this->config['host'], '/') . $path;
    }

    private function generateOauthScopes()
    {
        if (!class_exists('\Laravel\Passport\Passport')) {
            return [];
        }

        $scopes = \Laravel\Passport\Passport::scopes()->toArray();

        return array_combine(array_column($scopes, 'id'), array_column($scopes, 'description'));
    }

    private function validateAuthFlow(string $flow)
    {
        if (!in_array($flow, ['password', 'application', 'implicit', 'accessCode'])) {
            throw new LaravelSwaggerException('Invalid OAuth flow passed');
        }
    }

    private function isPassportScopeMiddleware(DataObjects\Middleware $middleware)
    {
        $resolver = $this->getMiddlewareResolver($middleware->name());

        return $resolver === 'Laravel\Passport\Http\Middleware\CheckScopes' ||
            $resolver === 'Laravel\Passport\Http\Middleware\CheckForAnyScope';
    }

    private function getMiddlewareResolver(string $middleware)
    {
        $middlewareMap = app('router')->getMiddleware();

        return $middlewareMap[$middleware] ?? null;
    }





    private function _makeParametersFromAnnotations($comments_annotations_param)
    {
        $ready = [];
        //  $ready = $comments_annotations_parsed;



        if (isset($comments_annotations_param) and $comments_annotations_param) {

            $param_name_type = explode('$', $comments_annotations_param);
            $param_name_type = array_map('trim', $param_name_type);

            if (isset($param_name_type[1]) and $param_name_type[1]) {

                $stub = array(
                    'name' => '',
                    'in' => 'body',
                    //  'description' => 'ID of pet to return',
                    //  'required' => true,
                    'type' => 'string'
                );


                $new_param = $stub;

                if (!$param_name_type[0]) {
                    $param_name_type[0] = 'string';
                }

                $new_param['type'] = $param_name_type[0];
                $new_param['name'] = $param_name_type[1];


                $error = false;
                try {
                    $rc = new \ReflectionClass($new_param['type']);

                    $comments = $rc->getMethod('rules')->getDocComment();

                } catch (\ReflectionException $exception) {
                    // Output expected ReflectionException.
                    $error = true;
                }
                if (!$error) {

                    if (class_exists($new_param['type'])) {
                        $new_req = new $new_param['type']();

                        if (method_exists($new_req, 'rules')) {


                            $get_rules_from_req = $new_req->rules();

                            if ($get_rules_from_req) {
                                foreach ($get_rules_from_req as $qkey => $get_rules_from) {
                                    $new_param_req = $new_param;
                                    $new_param_req['name'] = $qkey;
                                    $new_param_req['type'] = 'string';
                                    $new_param_req['in'] = 'formData';


                                    $get_rules_from_all = explode('|', $get_rules_from);
                                    if (in_array('required', $get_rules_from_all)) {
                                        $new_param_req['required'] = true;
                                    }

                                    if (in_array('required', $get_rules_from_all)) {
                                        $new_param_req['required'] = true;
                                    }

                                    if (in_array('integer', $get_rules_from_all)) {
                                        $new_param_req['type'] = 'integer';
                                    }


                                    $ready [] = $new_param_req;

                                }
                            }
                        }
                    }
                } else {

                }

            }

            // dump($param_name_type);
            // dump($comments_annotations_parsed);

        }


        return $ready;
    }
}