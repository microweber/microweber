<?php

namespace MicroweberPackages\App\Http\Controllers\Traits;

use MicroweberPackages\Option\Models\Option;

trait ContentShowFields
{
    public function appendContentShowFields()
    {
        $show_fields = false;
        if (isset($this->moduleParams['data-show'])) {
            $show_fields = $this->moduleParams['data-show'];
        }
        if (isset($this->moduleParams['show'])) {
            $show_fields = $this->moduleParams['show'];
        }

        $show_fields1 = Option::fetchFromCollection($this->moduleOptions, 'data-show');

        if ($show_fields1 != false and is_string($show_fields1) and trim($show_fields1) != '') {
            $show_fields = $show_fields1;
        }
        if ($show_fields != false and is_string($show_fields)) {
            $show_fields = explode(',', $show_fields);
        }

        $this->viewData['show_fields'] = $show_fields;
    }

}
