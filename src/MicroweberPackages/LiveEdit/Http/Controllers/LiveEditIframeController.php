<?php

namespace MicroweberPackages\LiveEdit\Http\Controllers;

use MicroweberPackages\App\Traits\LiveEditTrait;



class LiveEditIframeController
{
    use LiveEditTrait;

    public function index()
    {

        $l = view('admin::live_edit.iframe');
        $l = app()->template->append_api_js_to_layout($l);
        // $l = $this->liveEditToolbar($l);
        return $l;

    }
}
