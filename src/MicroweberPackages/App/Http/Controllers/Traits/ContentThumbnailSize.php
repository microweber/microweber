<?php

namespace MicroweberPackages\App\Http\Controllers\Traits;

use MicroweberPackages\Option\Models\Option;

trait ContentThumbnailSize {

    public function appendContentThumbnailSize()
    {
        $tnSize = array('150');
        $tn = $tnSize;

        if (isset($this->moduleParams['data-thumbnail-size'])) {
            $temp = explode('x', strtolower($this->moduleParams['data-thumbnail-size']));
            if (!empty($temp)) {
                $tnSize = $temp;
            }
        } else {
            $cfgPageItem = Option::fetchFromCollection($this->moduleOptions, 'data-thumbnail-size');
            if ($cfgPageItem != false) {
                $temp = explode('x', strtolower($cfgPageItem));
                if (!empty($temp)) {
                    $tnSize = $temp;
                }
            }
        }


        if (!isset($tn[0]) or ($tn[0]) == 150) {
            $tn[0] = 350;
        }
        if (!isset($tn[1])) {
            $tn[1] = $tn[0];
        }

        $this->viewData['tn'] = $tn;
        $this->viewData['tn_size'] = $tnSize;
    }
}
