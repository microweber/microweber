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
        $baseInfo['definitions'] = [];

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
        if(isset($this->config['authFlow'])) {
            $authFlow = $this->config['authFlow'];
        } else {
            $authFlow = 'accessCode';
        }
       // $authFlow = $this->config['authFlow'];

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

        $this->addDefinitions();
        $this->addActionParameters();

        if ($this->hasSecurityDefinitions) {
            $this->addActionScopes();
        }
    }

    protected function addDefinitions()
    {
        $model = $this->__getDefinitionForModel();
        if ($model) {
            //dump($model);
            $name = (get_class($model));
            $this->docs['definitions'][$name] = [];
            $this->docs['definitions'][$name]['type'] = 'object';
            $this->docs['definitions'][$name]['properties'] = [];


            if (method_exists($model, 'getFillable')) {



                $fillables = $model->getFillable();

                if ($fillables) {
                    foreach ($fillables as $fillable) {
                        //dump($fillable);
                        $this->docs['definitions'][$name]['properties'][$fillable] = [];
                        $this->docs['definitions'][$name]['properties'][$fillable]['type'] = 'string';

                    }
                }


            }

            $base_methods = get_class_methods('Illuminate\Database\Eloquent\Model');
            $model_methods = get_class_methods(get_class($model));

            $maybe_relations = array_diff($model_methods, $base_methods);
            if ($maybe_relations) {
                foreach ($maybe_relations as $fillable) {


                    $this->docs['definitions'][$name]['properties'][$fillable] = [];
                    $this->docs['definitions'][$name]['properties'][$fillable]['type'] = 'object';

                    $parsedComment = '';
                    try {
                        $docBlock = $this->__getReflectionMethodReflection($model, $fillable);
                        $parsedComment = $this->docParser->create($docBlock);
                    } catch (\Exception $e) {

                    }
                    if ($parsedComment and $parsedComment->getSummary()) {
                        $this->docs['definitions']['properties'] [$name][$fillable]['type'] = 'object';
                        $this->docs['definitions']['properties'] [$name][$fillable]['summary'] = $parsedComment->getSummary();
                        //    $this->docs['definitions'][$name. $fillable]['properties'][$fillable]['$ref'] = '#/definitions/'. $name. $fillable;


                    }



                }
            }

        }

    }

    protected function addActionParameters()
    {
        $rules = $this->getFormRules() ?: [];

        $parameters = (new Parameters\PathParameterGenerator($this->route->originalUri()))->getParameters();

        $name = strtolower(($this->route->getRoute()->getName()));


        $tags = explode('.', $name);
        $remove_last = array_pop($tags);


        if (!empty($rules)) {
            $parameterGenerator = $this->getParameterGenerator($rules);

            $parameters = array_merge($parameters, $parameterGenerator->getParameters());
            //dump($parameters);
        }
        $model = $this->__getDefinitionForModel();
        $action_name = $this->route->getRoute()->getActionName();
        $try_get_summary = explode('@', $action_name);
        //   dump($try_get_summary);
        if (!empty($parameters)) {


            $error = false;
            $comments = false;




            /* if (isset($try_get_summary[0]) and $try_get_summary[0]) {
                     if (isset($try_get_summary[1]) and $try_get_summary[1]) {
                         try {
                             $rc = new \ReflectionClass($try_get_summary[0]);
                             $comments = $rc->getMethod($try_get_summary[1])->getDocComment();




                         } catch (\ReflectionException $exception) {
                             $error = true;
                         }

     //
     //                    if (!$error and $comments) {
     //                        $dbp = new AnnotationParser();
     //                        $comments_annotations_parsed = $dbp->getAnnotations($comments);
     //
     //                        if($comments_annotations_parsed and isset($comments_annotations_parsed['param'])){
     //                            $parsed_from_a = $this->_makeParametersFromAnnotations($comments_annotations_parsed['param']);
     //                            if($parsed_from_a){
     //                              //  $parameters = array_merge($parameters, $parsed_from_a);
     //                            }
     //                        }
     //
     //                    }
                     }
                 }*/


            if ($model and $parameters) {
                foreach ($parameters as $key => $parameter) {
                    $name = (get_class($model));
                    $parameter['schema']['$ref'] = '#/definitions/' . $name;
                    $parameters[$key] = $parameter;
                }


            }


        }

        if (isset($try_get_summary[0]) and isset($try_get_summary[1])  and $try_get_summary[1] == 'index') {


            if ($this->method == 'get' and $model and method_exists($model, 'modelFilter')) {
               // $model = $model->getModel();

                if ($this->method == 'get' and $model and method_exists($model, 'modelFilter')) {
                    $model_filter_class_name = $model->modelFilter();
                    if ($model_filter_class_name) {
                        if (class_exists($model_filter_class_name)) {

                            $base_methods = get_class_methods('Illuminate\Database\Eloquent\Model');
                            $base_filters = get_class_methods('EloquentFilter\ModelFilter');
                            $model_methods = get_class_methods($model_filter_class_name);

                            $class_methods = array_diff($model_methods, $base_methods, $base_filters);
                            if ($class_methods) {

                                foreach ($class_methods as $class_method) {
                                    $parsedComment = '';
                                    $summary = '';
                                    $description = '';
                                    try {
                                        $docBlock = $this->__getReflectionMethodReflection($model_filter_class_name, $class_method);
                                        $parsedComment = $this->docParser->create($docBlock);
                                        $summary = $parsedComment->getSummary();
                                        $description = (string)$parsedComment->getDescription();


                                    } catch (\Exception $e) {

                                    }

                                    $stub = array(
                                        'name' => $class_method,
                                        'in' => 'query',
                                        //  'description' => 'ID of pet to return',
                                        //  'required' => true,
                                        'type' => 'string'
                                    );
                                    if($parsedComment){
                                        $stub['description'] = $summary;
                                        $stub['summary'] = $description;
                                    }
                                    $parameters[] = $stub;
                                }
                            }

                        }
                    }
                }
            }
        }

        if (!empty($parameters)) {
            $this->docs['paths'][$this->route->uri()][$this->method]['parameters'] = $parameters;

        }

        //$this->docs['paths'][$this->route->uri()][$this->method]['description'] = $this->__formatDescription();
        $this->docs['paths'][$this->route->uri()][$this->method]['description'] = $this->route->action();
        $this->docs['paths'][$this->route->uri()][$this->method]['tags'] = [implode('.', $tags)];
    }

    private function __formatDescription()
    {

        $render = [];


        $name = $this->route->action();
        $action_name = $this->route->getRoute()->getActionName();


        $render['name'] = $name;
        $render['action_name'] = $action_name;


        // dump($this->route );
        // dd($render);


        return 1;
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


    private $_map_models_to_action_names = [];



    protected function __getDefinitionForModel()
    {
        $defs = [];

        $action_name = $this->route->getRoute()->getActionName();

        $error = false;
        $comments = false;

        $try_get_summary = explode('@', $action_name);

        if (isset($try_get_summary[0]) and $try_get_summary[0]) {
            if (isset($try_get_summary[1]) and $try_get_summary[1]) {
                try {
                    $rc = new \ReflectionClass($try_get_summary[0]);
                    //   $comments = $rc->getMethod($try_get_summary[1])->getDocComment();
                    $constructor = $rc->getConstructor();

                    if ($constructor) {
                        $constructor_params = $constructor->getParameters();
                        if ($constructor_params) {
                            foreach ($constructor_params as $constructor_param) {
                                $constructor_param_type = $constructor_param->getType();
                                if ($constructor_param_type) {
                                    $rc_type_param_class = new \ReflectionClass($constructor_param_type->getName());
                                    if ($rc_type_param_class->hasMethod('getModel')) {
                                        $class_name = $constructor_param_type->getName();

                                        $getModel = app()->make($class_name)->getModel();
                                       // dump($getModel );

                                        $this->_map_models_to_action_names[$action_name] = $getModel;
                                        return $getModel;

                                    }
                                }
                            }
                        }
                    }


                } catch (\ReflectionException $exception) {
                    $error = true;
                }

                //
                //                    if (!$error and $comments) {
                //                        $dbp = new AnnotationParser();
                //                        $comments_annotations_parsed = $dbp->getAnnotations($comments);
                //
                //                        if($comments_annotations_parsed and isset($comments_annotations_parsed['param'])){
                //                            $parsed_from_a = $this->_makeParametersFromAnnotations($comments_annotations_parsed['param']);
                //                            if($parsed_from_a){
                //                              //  $parameters = array_merge($parameters, $parsed_from_a);
                //                            }
                //                        }
                //
                //                    }
            }
        }

        return;

    }



    protected function __aaaagetDefinitionForModel()
    {
        $defs = [];

        $action_name = $this->route->getRoute()->getActionName();

        $error = false;
        $comments = false;

        $try_get_summary = explode('@', $action_name);

        if (isset($try_get_summary[0]) and $try_get_summary[0]) {
            if (isset($try_get_summary[1]) and $try_get_summary[1]) {
                try {
                    $rc = new \ReflectionClass($try_get_summary[0]);
                    //   $comments = $rc->getMethod($try_get_summary[1])->getDocComment();
                    $constructor = $rc->getConstructor();

                    if ($constructor) {
                        $constructor_params = $constructor->getParameters();
                        if ($constructor_params) {
                            foreach ($constructor_params as $constructor_param) {
                                $constructor_param_type = $constructor_param->getType();
                                //  dump($constructor_param->getType());
                                if ($constructor_param_type) {
                                    $rc_type_param_class = new \ReflectionClass($constructor_param_type->getName());
                                    if ($rc_type_param_class->hasMethod('getModel')) {
                                        $class_name = $constructor_param_type->getName();

                                        $getModel = app()->make($class_name)->getModel();
                                        $this->_map_models_to_action_names[$action_name] = $getModel;
                                        return $getModel;
                                        //dump($class_name);
                                        // dump($rc_type_param_class);
                                    }
                                }
                            }
                        }
                    }


                } catch (\ReflectionException $exception) {
                    $error = true;
                }

                //
                //                    if (!$error and $comments) {
                //                        $dbp = new AnnotationParser();
                //                        $comments_annotations_parsed = $dbp->getAnnotations($comments);
                //
                //                        if($comments_annotations_parsed and isset($comments_annotations_parsed['param'])){
                //                            $parsed_from_a = $this->_makeParametersFromAnnotations($comments_annotations_parsed['param']);
                //                            if($parsed_from_a){
                //                              //  $parameters = array_merge($parameters, $parsed_from_a);
                //                            }
                //                        }
                //
                //                    }
            }
        }

        return;

    }

    protected function getFormRules(): array
    {
        $all_rules = [];

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
                // return $rules;
                $all_rules = array_merge($all_rules, $rules);

            }


//            $model = $this->__getDefinitionForModel();
//            if ($model) {
//                $name = (get_class($model));
//                dump($model);
////                if (method_exists($model, 'getFillable')) {
////                    $fillables = $model->getFillable();
////                    if ($fillables) {
////                        foreach ($fillables as $fillable) {
////                            //dump($fillable);
////                            //$fillable
////                        }
////                    }
////                }
//            }

            // if (is_subclass_of($class_name, FormRequest::class)) {
            // }


        }

        return $all_rules;
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

    private function __getReflectionMethodReflection($class, $method): ?ReflectionMethod
    {

        // return new ReflectionMethod($class, $method);
        try {
            $rc = new ReflectionMethod($class, $method);;

            return $rc;
        } catch (\ReflectionException $exception) {
            return null;

        }


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
