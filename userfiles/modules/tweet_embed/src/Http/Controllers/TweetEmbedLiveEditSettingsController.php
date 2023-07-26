<?php

namespace MicroweberPackages\Modules\TweetEmbed\Http\Controllers;

use Illuminate\Http\Request;

class TweetEmbedLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-tweet-embed::live-edit.settings',['moduleId' => $params['id']]);
    }
}
