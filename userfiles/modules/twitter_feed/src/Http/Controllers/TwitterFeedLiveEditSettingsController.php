<?php

namespace MicroweberPackages\Modules\TwitterFeed\Http\Controllers;

use Illuminate\Http\Request;

class TwitterFeedLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-twitter-feed::live-edit.settings',['moduleId' => $params['id']]);
    }
}
