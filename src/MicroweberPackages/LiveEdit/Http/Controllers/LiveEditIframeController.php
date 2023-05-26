<?php

namespace MicroweberPackages\LiveEdit\Http\Controllers;

use MicroweberPackages\App\Traits\LiveEditTrait;


class LiveEditIframeController
{
    use LiveEditTrait;

    public function index()
    {
        $params = request()->all();
        $l = view('microweber-live-edit::iframe', ['params' => $params]);
        $l = app()->template->append_api_js_to_layout($l);

        return $l;
    }
}
