<?php

namespace Microweber\Packages\Audio\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AudioController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($params)
    {
        return view('audio::index', $this->appendParams($params));
    }

    public function admin($params) {
        return view('audio::admin', $this->appendParams($params));
    }

    public function appendParams($params) {

        $id = "mwaudio-" . uniqid();
        $prior =  get_option('prior', $params['id']);

        if($prior == '1'){
            $audio =  get_option('data-audio-upload', $params['id']);
        } else {
            $audio =  get_option('data-audio-url', $params['id']);
        }

        return ['id'=>$id, 'prior'=>$prior, 'audio'=>$audio, 'params'=>$params];
    }
}