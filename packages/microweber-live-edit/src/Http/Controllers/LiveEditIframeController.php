<?php

namespace MicroweberPackages\LiveEdit\Http\Controllers;

use MicroweberPackages\App\Traits\LiveEditTrait;


/**
 * @deprecated
 */
class LiveEditIframeController
{
    use LiveEditTrait;
    public function __construct()
    {

        //event_trigger('mw.init');
    }

    public function index()
    {
        $params = request()->all();
        $l = view('microweber-live-edit::iframe', ['params' => $params]);

        return $l;
    }
}
