<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/13/2020
 * Time: 12:16 PM
 */

namespace MicroweberPackages\Form\Http\Controllers\ApiPublic;

use Illuminate\Http\Request;

class FormController
{
    public function post(Request $request)
    {
        $response = mw()->forms_manager->post($request->all());

        $responseCode = 200;
        if (isset($response['error'])) {
            $responseCode = 422;
        }

        if (isset($response['form_errors'])) {
            $responseCode = 422;
        }

        return response()->json($response, $responseCode);
    }
}
