<?php

namespace MicroweberPackages\OpenApi\Http\Controllers;

use L5Swagger\Http\Controllers\SwaggerController as L5SwaggerController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Support\Facades\Response as ResponseFacade;
use L5Swagger\Exceptions\L5SwaggerException;
use L5Swagger\GeneratorFactory;

use OpenApi\Analysis;
use OpenApi\Annotations\Components;
use OpenApi\Annotations\Operation;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Schema;
use cebe\openapi\Reader;
use const OpenApi\UNDEFINED;
use Symfony\Component\Finder\Finder;
use OpenApi\Util;
use \phpDocumentor\Reflection\DocBlock;
use \phpDocumentor\Reflection\DocBlock\Tag;
use Mtrajano\LaravelSwagger\Generator;
use MicroweberPackages\OpenApi\Models\SwGen;


class SwaggerController extends L5SwaggerController
{
    public function docs(Request $request, string $file = null)
    {

        $host = (parse_url(site_url()));


        $config = [];
        $config['title'] = 'Api';
        $config['description'] = 'Api';
        $config['appVersion'] = '1.0';
        $config['parseSecurity'] = true;
        $config['host'] = $host['host'];
        $config['basePath'] = $host['path'];
        $config['schemes'] = ['http'];
        $config['ignoredMethods'] = ['head','options','patch'];
        $config['parseDocBlock'] = true;
        if (is_https()) {
            $config['schemes'] = ['https', 'http'];
        }


        $config['consumes'] = ['application/json' ];
        $config['produces'] = ['application/json' ];





        $gen = new SwGen($config);
        $all_json_data = $gen->generate();

         $json = json_encode($all_json_data, JSON_PRETTY_PRINT);
        return ResponseFacade::make($json, 200, [
            'Content-Type' => 'application/json',
        ]);


    }

    public function docs2(Request $request, string $file = null)
    {
        // $directory = base_path() . '/src/MicroweberPackages/';
        $directory = dirname(dirname(dirname(__DIR__)));

        $exclude = [];
        $pattern = ['swagger.json'];
        $finder = Util::finder($directory, $exclude, $pattern);
        $all_json_paths = [];
        $all_json_data = [];

        foreach ($finder as $file) {
            $all_json_paths[] = $file->getPathname();
            $openapi = Reader::readFromJsonFile($file->getPathname());
            $all_json_data = array_merge_recursive($all_json_data, json_decode(json_encode($openapi->getSerializableData()), 1));
        }


        // set custom data
        $all_json_data = $this->_setCustomData($all_json_data);


        $json = json_encode($all_json_data, JSON_PRETTY_PRINT);
        return ResponseFacade::make($json, 200, [
            'Content-Type' => 'application/json',
        ]);


    }


    private function _setCustomData($all_json_data)
    {


        if (!isset($all_json_data['info'])) {
            $all_json_data['info'] = [];
        }
        if (!isset($all_json_data['paths'])) {
            $all_json_data['paths'] = [];
        }

        $all_json_data['info']['title'] = 'Open Api';


        $host = (parse_url(site_url()));


        $all_json_data['host'] = $host['host'];
        $all_json_data['basePath'] = $host['path'];

        if (is_https()) {
            $all_json_data['schemes'] = ['https', 'http'];

        }


        $routeCollection = \Route::getRoutes();


        foreach ($routeCollection as $value) {
            $stub = $this->pathItemStub;
            //  dd($value->methods[0]);
            if (isset($value->methods) and isset($value->methods[0])) {
                $error = false;


                $name = strtolower($value->getName());
                $tags = explode('.', $name);
                $fruit = array_pop($tags);
                $stub['__URI__']['__HTTP_METHOD__']['tags'] = [implode('.', $tags)];


                $action_name = $value->getActionName();
                $stub['__URI__']['__HTTP_METHOD__']['operationId'] = $action_name;
                //  $stub['__URI__']['__HTTP_METHOD__']['summary'] = $action_name;
                $stub['__URI__']['__HTTP_METHOD__']['description'] = $action_name;


                $try_get_summary = explode('@', $action_name);
                if (isset($try_get_summary[0]) and isset($try_get_summary[1])) {


                    try {
                        $rc = new \ReflectionClass($try_get_summary[0]);
                        //https://gist.github.com/maetl/949816
                        $comments = $rc->getMethod($try_get_summary[1])->getDocComment();


                        if ($comments) {


                            $comment_string = $comments;
                            $pattern = "#(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_].*)#";

//perform the regular expression on the string provided
                            preg_match_all($pattern, $comment_string, $try_params, PREG_PATTERN_ORDER);

//
//

                            $dbp = new AnnotationParser();
                            $comments_annotations_parsed = $dbp->getAnnotations($comments);
                            $comments_description_parsed = $dbp->getDescription($comments);


//                            echo "<pre>"; print_r($try_params);
//                            dump($comments_description_parsed);
                            // dump($comments_annotations_parsed);

                            $params_from_comments = $this->_makeParametersFromAnnotations($comments_annotations_parsed);

                            if ($params_from_comments) {
                                $stub['__URI__']['__HTTP_METHOD__']['parameters'] = $params_from_comments;

                            }

                            if ($comments_description_parsed) {
                                $stub['__URI__']['__HTTP_METHOD__']['summary'] = $comments_description_parsed;

                            } else {
                                $stub['__URI__']['__HTTP_METHOD__']['summary'] = $comments;

                            }

                        }
                    } catch (\ReflectionException $exception) {
                        // Output expected ReflectionException.
                        $error = true;
                    }
                }


                if (!$error) {

                    // unset($stub['__URI__']['__HTTP_METHOD__']);


                    // dd($value->methods);
                    //    dd($http_method = $value->getMethods());
                    $http_method = strtolower($value->methods[0]);
                    $stub['__URI__'][$http_method] = $stub['__URI__']['__HTTP_METHOD__'];
                    unset($stub['__URI__']['__HTTP_METHOD__']);

                    $url = $value->uri();
                    $test = preg_match("/^\//", $url);
                    if ($test != 1) {
                        $url = '/' . $value->uri();

                    }
                    $stub[$url] = $stub['__URI__'];
                    unset($stub['__URI__']);


                    // $key_url = $value->uri();

                    $all_json_data['paths'][array_key_first($stub)] = reset($stub);
                }


            }
        }


        return $all_json_data;
    }

    private function _makeParametersFromAnnotations($comments_annotations_parsed)
    {
        $ready = [];
        //  $ready = $comments_annotations_parsed;

        $stub = array(
            'name' => '',
            'in' => 'body',
            //  'description' => 'ID of pet to return',
            //  'required' => true,
            'type' => 'string'
        );

        if (isset($comments_annotations_parsed['param']) and $comments_annotations_parsed['param']) {

            $param_name_type = explode('$', $comments_annotations_parsed['param']);
            $param_name_type = array_map('trim', $param_name_type);

            if (isset($param_name_type[1]) and $param_name_type[1]) {
                $new_param = $stub;

                if (!$param_name_type[0]) {
                    $param_name_type[0] = 'string';
                }

                $new_param['type'] = $param_name_type[0];
                $new_param['name'] = $param_name_type[1];


                $error = false;
                $rc = new \ReflectionClass($new_param['type']);
                try {
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


                                    $ready[] = $new_param_req;

                                }
                            }
                        }
                    }
                } else {
                    $ready[] = $new_param;

                }

            }

            // dump($param_name_type);
            // dump($comments_annotations_parsed);

        }


        return $ready;
    }

    private $pathItemStub = array(
        '__URI__' =>
            array(
                '__HTTP_METHOD__' =>
                    array(
                        'tags' =>
                            array(),
                        'summary' => '',
                        'description' => '',
                        'operationId' => '',
                        'produces' =>
                            array(
                                1 => 'application/json',
                            ),
                        'parameters' =>
                            array(
//                                0 =>
//                                    array(
//                                        'name' => 'petId',
//                                        'in' => 'path',
//                                        'description' => 'ID of pet to return',
//                                        'required' => true,
//                                        'type' => 'integer',
//                                        'format' => 'int64',
//                                    ),
                            ),
                        'responses' =>
                            array(
                                200 =>
                                    array(
                                        'description' => 'successful operation',

                                    ),
//                                400 =>
//                                    array(
//                                        'description' => 'Invalid ID supplied',
//                                    ),
//                                404 =>
//                                    array(
//                                        'description' => 'Pet not found',
//                                    ),
                            ),
                        'security' =>
                            array(
                                0 =>
                                    array(
                                        'api_key' =>
                                            array(),
                                    ),
                            ),
                    ),
//                'post' =>
//                    array(
//                        'tags' =>
//                            array(
//                                0 => 'pet',
//                            ),
//                        'summary' => 'Updates a pet in the store with form data',
//                        'description' => '',
//                        'operationId' => 'updatePetWithForm',
//                        'consumes' =>
//                            array(
//                                0 => 'application/x-www-form-urlencoded',
//                            ),
//                        'produces' =>
//                            array(
//                                0 => 'application/xml',
//                                1 => 'application/json',
//                            ),
//                        'parameters' =>
//                            array(
//                                0 =>
//                                    array(
//                                        'name' => 'petId',
//                                        'in' => 'path',
//                                        'description' => 'ID of pet that needs to be updated',
//                                        'required' => true,
//                                        'type' => 'integer',
//                                        'format' => 'int64',
//                                    ),
//                                1 =>
//                                    array(
//                                        'name' => 'name',
//                                        'in' => 'formData',
//                                        'description' => 'Updated name of the pet',
//                                        'required' => false,
//                                        'type' => 'string',
//                                    ),
//                                2 =>
//                                    array(
//                                        'name' => 'status',
//                                        'in' => 'formData',
//                                        'description' => 'Updated status of the pet',
//                                        'required' => false,
//                                        'type' => 'string',
//                                    ),
//                            ),
//                        'responses' =>
//                            array(
//                                405 =>
//                                    array(
//                                        'description' => 'Invalid input',
//                                    ),
//                            ),
//                        'security' =>
//                            array(
//                                0 =>
//                                    array(
//                                        'petstore_auth' =>
//                                            array(
//                                                0 => 'write:pets',
//                                                1 => 'read:pets',
//                                            ),
//                                    ),
//                            ),
//                    ),
//                'delete' =>
//                    array(
//                        'tags' =>
//                            array(
//                                0 => 'pet',
//                            ),
//                        'summary' => 'Deletes a pet',
//                        'description' => '',
//                        'operationId' => 'deletePet',
//                        'produces' =>
//                            array(
//                                0 => 'application/xml',
//                                1 => 'application/json',
//                            ),
//                        'parameters' =>
//                            array(
//                                0 =>
//                                    array(
//                                        'name' => 'api_key',
//                                        'in' => 'header',
//                                        'required' => false,
//                                        'type' => 'string',
//                                    ),
//                                1 =>
//                                    array(
//                                        'name' => 'petId',
//                                        'in' => 'path',
//                                        'description' => 'Pet id to delete',
//                                        'required' => true,
//                                        'type' => 'integer',
//                                        'format' => 'int64',
//                                    ),
//                            ),
//                        'responses' =>
//                            array(
//                                400 =>
//                                    array(
//                                        'description' => 'Invalid ID supplied',
//                                    ),
//                                404 =>
//                                    array(
//                                        'description' => 'Pet not found',
//                                    ),
//                            ),
//                        'security' =>
//                            array(
//                                0 =>
//                                    array(
//                                        'petstore_auth' =>
//                                            array(
//                                                0 => 'write:pets',
//                                                1 => 'read:pets',
//                                            ),
//                                    ),
//                            ),
//                    ),
            ),
    );

    private function _filterRoutesCollection($routeCollection)
    {
        echo "<table style='width:100%'>";
        echo "<tr>";
        echo "<td width='10%'><h4>HTTP Method</h4></td>";
        echo "<td width='10%'><h4>Route</h4></td>";
        echo "<td width='10%'><h4>Name</h4></td>";
        echo "<td width='70%'><h4>Corresponding Action</h4></td>";
        echo "</tr>";
        foreach ($routeCollection as $value) {
            echo "<tr>";
            echo "<td>" . $value->methods()[0] . "</td>";
            echo "<td>" . $value->uri() . "</td>";
            echo "<td>" . $value->getName() . "</td>";
            echo "<td>" . $value->getActionName() . "</td>";
            echo "</tr>";
        }
        echo "</table>";

        dd(__FILE__);
        exit;


        $allowed_siffux = ['index', 'create', 'store', 'show', 'edit', 'destroy', 'clone', 'update'];

        foreach ($routeCollection as $key => $value) {
            $skip = true;


        }
        return $routeCollection;

    }

    /*     * @deprecated
    */

    public function __DEPRECATED__docs113243(Request $request, string $file = null)
    {


        $documentation = $request->offsetGet('documentation');
        $config = $request->offsetGet('config');
        $targetFile = $config['paths']['docs_json'] ?? 'api-docs.json';
        $yaml = false;

        if (!is_null($file)) {
            $targetFile = $file;
            $parts = explode('.', $file);

            if (!empty($parts)) {
                $extension = array_pop($parts);
                $yaml = strtolower($extension) === 'yaml';
            }
        }


        $filePath = $config['paths']['docs'] . '/' . $targetFile;


// merge our custom processor
        $processors = [];
        foreach (\OpenApi\Analysis::processors() as $processor) {
            $processors[] = $processor;
            if ($processor instanceof \OpenApi\Processors\BuildPaths) {
                $processors[] = new  SchemaQueryParameter();
            }
        }


        $options = [
            'processors' => $processors,
        ];


//dd($options);
        // $openapi = \OpenApi\scan(base_path() . '/src/MicroweberPackages/OpenApi/', $options);

        $openapi = Reader::readFromJsonFile(base_path() . '/src/MicroweberPackages/OpenApi/swagger.json');


        //

//        $openapi->resolveReferences(
//            new \cebe\openapi\ReferenceContext($openapi, 'https://www.example.com/api/openapi.yaml')
//        );


        $json = \cebe\openapi\Writer::writeToJson($openapi);
        return ResponseFacade::make($json, 200, [
            'Content-Type' => 'application/json',
        ]);
//dd($json);

        $spec = json_encode($openapi, JSON_PRETTY_PRINT);
//dd($spec);

        // file_put_contents(__DIR__ . '/schema-query-parameter-processor.json', $spec);
    }


    /**
     * Dump api-docs content endpoint. Supports dumping a json, or yaml file.
     * @deprecated
     * @param Request $request
     * @param string $file
     *
     * @return Response
     * @throws L5SwaggerException
     */
    public function __DEPRECATED__doc1s(Request $request, string $file = null)
    {
        $documentation = $request->offsetGet('documentation');
        $config = $request->offsetGet('config');
        $targetFile = $config['paths']['docs_json'] ?? 'api-docs.json';
        $yaml = false;

        if (!is_null($file)) {
            $targetFile = $file;
            $parts = explode('.', $file);

            if (!empty($parts)) {
                $extension = array_pop($parts);
                $yaml = strtolower($extension) === 'yaml';
            }
        }


        $filePath = $config['paths']['docs'] . '/' . $targetFile;


//dd($documentation);
        //  $generator = $this->generatorFactory->make($documentation);


        // if ($config['generate_always'] || ! File::exists($filePath)) {
        $generator = $this->generatorFactory->make($documentation);

        try {
            $generator->generateDocs();
        } catch (\Exception $e) {
            Log::error($e);

            abort(
                404,
                sprintf(
                    'Unable to generate documentation file to: "%s". Please make sure directory is writable. Error: %s',
                    $filePath,
                    $e->getMessage()
                )
            );
        }
        // }

        $content = File::get($filePath);
        $content = str_replace('__API_URL__', site_url(), $content);

        if ($yaml) {
            return ResponseFacade::make($content, 200, [
                'Content-Type' => 'application/yaml',
                'Content-Disposition' => 'inline',
            ]);
        }

        return ResponseFacade::make($content, 200, [
            'Content-Type' => 'application/json',
        ]);
    }


}


/**
 * Custom processor to translate the vendor tag `query-args-$ref` into query parameter annotations.
 *
 * Details for the parameters are taken from the referenced schema.
 */
class SchemaQueryParameter
{
    const X_QUERY_AGS_REF = 'query-args-$ref';

    public function __invoke(Analysis $analysis)
    {
        $schemas = $analysis->getAnnotationsOfType(Schema::class, true);
        $operations = $analysis->getAnnotationsOfType(Operation::class);

        foreach ($operations as $operation) {
            if ($operation->x !== UNDEFINED && array_key_exists(self::X_QUERY_AGS_REF, $operation->x)) {
                if ($schema = $this->schemaForRef($schemas, $operation->x[self::X_QUERY_AGS_REF])) {
                    $this->expandQueryArgs($operation, $schema);
                    $this->cleanUp($operation);
                }
            }
        }
    }

    /**
     * Find schema for the given ref.
     */
    protected function schemaForRef(array $schemas, string $ref)
    {
        foreach ($schemas as $schema) {
            if (Components::SCHEMA_REF . $schema->schema === $ref) {
                return $schema;
            }
        }

        return null;
    }

    /**
     * Expand the given operation by injecting parameters for all properties of the given schema.
     */
    protected function expandQueryArgs(Operation $operation, Schema $schema)
    {
        if ($schema->properties === UNDEFINED || !$schema->properties) {
            return;
        }

        $operation->parameters = $operation->parameters === UNDEFINED ? [] : $operation->parameters;
        foreach ($schema->properties as $property) {
            $parameter = new Parameter([
                'name' => $property->property,
                'in' => 'query',
                'required' => false,
            ]);
            $operation->parameters[] = $parameter;
        }
    }

    /**
     * Clean up.
     */
    protected function cleanUp($operation)
    {
        unset($operation->x[self::X_QUERY_AGS_REF]);
        if (!$operation->x) {
            $operation->x = UNDEFINED;
        }
    }
}


class DocBlockParser
{
    /**
     * @param mixed $comment
     * @param ParserContext $context
     *
     * @return DocBlockNode
     */
    public function parse($comment)
    {
        $docBlock = null;
        $errorMessage = '';

        try {
            $docBlockContext = new DocBlock\Description($comment, $tags = ['param']);
            $docBlock = new DocBlock((string)$comment, $docBlockContext);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
        }

        $result = new DocBlockNode();

        if ($errorMessage) {
            $result->addError($errorMessage);

            return $result;
        }

        $result->setShortDesc($comment);
        $result->setLongDesc($comment);
        dump($docBlock);

        foreach ($docBlock->getTags() as $tag) {
            $result->addTag($tag->getName(), $this->parseTag($tag));
        }

        return $result;
    }

    public function getTag($string)
    {
        return Tag::createInstance($string);
    }

    protected function parseTag(DocBlock\Tag $tag)
    {
        switch (substr(get_class($tag), 38)) {
            case 'VarTag':
            case 'ReturnTag':
                return array(
                    $this->parseHint($tag->getTypes()),
                    $tag->getDescription(),
                );
            case 'PropertyTag':
            case 'PropertyReadTag':
            case 'PropertyWriteTag':
            case 'ParamTag':
                return array(
                    $this->parseHint($tag->getTypes()),
                    ltrim($tag->getVariableName(), '$'),
                    $tag->getDescription(),
                );
            case 'ThrowsTag':
                return array(
                    $tag->getType(),
                    $tag->getDescription(),
                );
            case 'SeeTag':
                // For backwards compatibility, in first cell we store content.
                // In second - only a referer for further parsing.
                // In docblock node we handle this in getOtherTags() method.
                return array(
                    $tag->getContent(),
                    $tag->getReference(),
                    $tag->getDescription(),
                );
            default:
                return $tag->getContent();
        }
    }

    protected function parseHint($rawHints)
    {
        $hints = array();
        foreach ($rawHints as $hint) {
            if ('[]' == substr($hint, -2)) {
                $hints[] = array(substr($hint, 0, -2), true);
            } else {
                $hints[] = array($hint, false);
            }
        }

        return $hints;
    }
}


class Context
{

    /** @var string The current namespace. */
    protected $namespace = '';

    /** @var array List of namespace aliases => Fully Qualified Namespace. */
    protected $namespace_aliases = array();

    /** @var string Name of the structural element, within the namespace. */
    protected $lsen = '';

    /**
     * Cteates a new context.
     * @param string $namespace The namespace where this DocBlock
     *     resides in.
     * @param array $namespace_aliases List of namespace aliases => Fully
     *     Qualified Namespace.
     * @param string $lsen Name of the structural element, within
     *     the namespace.
     */
    public function __construct($namespace = '', array $namespace_aliases = array(), $lsen = '')
    {
        if (!empty($namespace)) {
            $this
                ->setNamespace($namespace);
        }
        $this
            ->setNamespaceAliases($namespace_aliases);
        $this
            ->setLSEN($lsen);
    }

    /**
     * @return string The namespace where this DocBlock resides in.
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return array List of namespace aliases => Fully Qualified Namespace.
     */
    public function getNamespaceAliases()
    {
        return $this->namespace_aliases;
    }

    /**
     * Returns the Local Structural Element Name.
     *
     * @return string Name of the structural element, within the namespace.
     */
    public function getLSEN()
    {
        return $this->lsen;
    }

    /**
     * Sets a new namespace.
     *
     * Sets a new namespace for the context. Leading and trailing slashes are
     * trimmed, and the keywords "global" and "default" are treated as aliases
     * to no namespace.
     *
     * @param string $namespace The new namespace to set.
     *
     * @return $this
     */
    public function setNamespace($namespace)
    {
        if ('global' !== $namespace && 'default' !== $namespace) {

            // Srip leading and trailing slash
            $this->namespace = trim((string)$namespace, '\\');
        } else {
            $this->namespace = '';
        }
        return $this;
    }

    /**
     * Sets the namespace aliases, replacing all previous ones.
     *
     * @param array $namespace_aliases List of namespace aliases => Fully
     *     Qualified Namespace.
     *
     * @return $this
     */
    public function setNamespaceAliases(array $namespace_aliases)
    {
        $this->namespace_aliases = array();
        foreach ($namespace_aliases as $alias => $fqnn) {
            $this
                ->setNamespaceAlias($alias, $fqnn);
        }
        return $this;
    }

    /**
     * Adds a namespace alias to the context.
     *
     * @param string $alias The alias name (the part after "as", or the last
     *     part of the Fully Qualified Namespace Name) to add.
     * @param string $fqnn The Fully Qualified Namespace Name for this alias.
     *     Any form of leading/trailing slashes are accepted, but what will be
     *     stored is a name, prefixed with a slash, and no trailing slash.
     *
     * @return $this
     */
    public function setNamespaceAlias($alias, $fqnn)
    {
        $this->namespace_aliases[$alias] = '\\' . trim((string)$fqnn, '\\');
        return $this;
    }

    /**
     * Sets a new Local Structural Element Name.
     *
     * Sets a new Local Structural Element Name. A local name also contains
     * punctuation determining the kind of structural element (e.g. trailing "("
     * and ")" for functions and methods).
     *
     * @param string $lsen The new local name of a structural element.
     *
     * @return $this
     */
    public function setLSEN($lsen)
    {
        $this->lsen = (string)$lsen;
        return $this;
    }

}


class DocBlockNode
{
    protected $shortDesc;
    protected $longDesc;
    protected $tags = array();
    protected $errors = array();

    public function addTag($key, $value)
    {
        $this->tags[$key][] = $value;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function getOtherTags()
    {
        $tags = $this->tags;
        unset($tags['param'], $tags['return'], $tags['var'], $tags['throws']);

        foreach ($tags as $name => $values) {
            foreach ($values as $i => $value) {
                // For 'see' tag we try to maintain backwards compatibility
                // by returning only a part of the value.
                if ($name === 'see') {
                    $value = $value[0];
                }

                $tags[$name][$i] = is_string($value) ? explode(' ', $value) : $value;
            }
        }

        return $tags;
    }

    public function getTag($key)
    {
        return $this->tags[$key] ?? array();
    }

    public function getShortDesc()
    {
        return $this->shortDesc;
    }

    public function getLongDesc()
    {
        return $this->longDesc;
    }

    public function setShortDesc($shortDesc)
    {
        $this->shortDesc = $shortDesc;
    }

    public function setLongDesc($longDesc)
    {
        $this->longDesc = $longDesc;
    }

    public function getDesc()
    {
        return $this->shortDesc . "\n\n" . $this->longDesc;
    }

    public function addError($error)
    {
        $this->errors[] = $error;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}


class AnnotationParser
{
    const DOCBLOCK_PATTERN = '%\/\*\*.*\*\/%s';
    const ANNOTATION_PATTERN = '/(?:\*\s*\@)(?P<tag>[a-zA-Z]+)\s(?P<value>.+)\n/';
    const DESCRIPTION_PATTERN = '/\s*\*\s*(?P<description>[^@\/\s\*].+)/';

    /**
     * Verify if the string has a docBlock in it.
     *
     * @param string $block
     * @return bool
     */
    public function hasDocBlock($block)
    {
        if (!is_string($block) || $block == '') {
            return false;
        }

        return (bool)preg_match(self::DOCBLOCK_PATTERN, $block);
    }

    /**
     * Extract annotations from a docBlock
     * @param string $text
     * @return array
     */
    public function getAnnotations($text)
    {
        $annotations = array();

        if (!$this->hasDocBlock($text)) {
            return $annotations;
        }

        preg_match_all(self::ANNOTATION_PATTERN, $text, $matches);

        foreach ($matches['tag'] as $index => $tag) {
            $annotations[$tag] = trim($matches['value'][$index]);
        }

        return $annotations;
    }

    /**
     * Retrieves any text that is not a * follow by a space or an annotation
     * @param string $text
     * @return string
     */
    public function getDescription($text)
    {
        $description = '';

        if (!$this->hasDocBlock($text)) {
            return $description;
        }

        preg_match_all(self::DESCRIPTION_PATTERN, $text, $matches);

        return implode(" ", $matches['description']);
    }

    /**
     * Separate the docBlock from the content
     * @param string $text
     * @return array
     */
    public function extractDocBlock($text)
    {
        $split = array(
            'meta' => '',
            'content' => '',
        );

        if (!is_string($text) || !$this->hasDocBlock($text)) {
            $split['content'] = $text;
        } else {
            $split['meta'] = substr($text, 0, strpos($text, '*/') + 2);
            $split['content'] = trim(substr($text, strpos($text, '*/') + 2));
        }

        return $split;
    }

    /**
     * Parses a string for a single
     * @param string $text
     * @return array
     */
    public function parse($text)
    {
        $data = array(
            'meta' => array(),
            'content' => ''
        );

        if (!is_string($text) || !$this->hasDocBlock($text)) {
            $data['content'] = $text;
        } else {
            $split = $this->extractDocBlock($text);
            $data['meta'] = $this->getAnnotations($split['meta']);
            $data['meta']['description'] = $this->getDescription($split['meta']);
            $data['content'] = $split['content'];
        }


        return $data;
    }
}