<?php

namespace MicroweberPackages\App\Http\Controllers;

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


class SwaggerController extends L5SwaggerController
{
    public function docs(Request $request, string $file = null)
    {
        $directory = base_path() . '/src/MicroweberPackages/';
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

        $all_json_data['host'] =site_url();
        $json = json_encode($all_json_data, JSON_PRETTY_PRINT);
        return ResponseFacade::make($json, 200, [
            'Content-Type' => 'application/json',
        ]);


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