<?php

namespace MicroweberPackages\Modules\Testimonials\Http\Controllers;

use Illuminate\Http\Request;

class TestimonialsLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        return view('microweber-module-testimonials::live-edit.settings',['moduleId' => $params['id']]);
    }
}
