<?php

namespace MicroweberPackages\OpenApi\Http\Controllers;

use L5Swagger\Http\Controllers\SwaggerController as L5SwaggerController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response as ResponseFacade;

use MicroweberPackages\OpenApi\Models\SwGen;


class SwaggerController extends L5SwaggerController
{
    public function docs(Request $request, string $file = null)
    {

        $host = (parse_url(site_url()));

        if(!is_array($host)){
            $host = [];
        }

        if(!isset($host['host'])){
            $host['host'] = 'localhost';
        }

        if(!isset($host['path'])){
            $host['path'] = '';
        }


        $config = [];
        $config['title'] = 'Api';
        $config['description'] = 'Api';
        $config['appVersion'] = '1.0';
        $config['parseSecurity'] = true;
        $config['host'] = $host['host'];
        $config['basePath'] = $host['path'];
        $config['schemes'] = ['http'];

        $config['ignoredMethods'] = ['head','options','patch'];
     //   $config['ignoredMethods'] = [ ];
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





}

