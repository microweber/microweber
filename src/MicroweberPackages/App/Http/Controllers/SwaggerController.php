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



class SwaggerController extends L5SwaggerController
{
    /**
     * Dump api-docs content endpoint. Supports dumping a json, or yaml file.
     *
     * @param Request $request
     * @param string $file
     *
     * @return Response
     * @throws L5SwaggerException
     */
    public function docs(Request $request, string $file = null)
    {
        $documentation = $request->offsetGet('documentation');
        $config = $request->offsetGet('config');

        $targetFile = $config['paths']['docs_json'] ?? 'api-docs.json';
        $yaml = false;

        if (! is_null($file)) {
            $targetFile = $file;
            $parts = explode('.', $file);

            if (! empty($parts)) {
                $extension = array_pop($parts);
                $yaml = strtolower($extension) === 'yaml';
            }
        }

        $filePath = $config['paths']['docs'].'/'.$targetFile;

        if ($config['generate_always'] || ! File::exists($filePath)) {
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
        }

        $content = File::get($filePath);
        $content = str_replace('__API_URL__',site_url(),$content);

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
