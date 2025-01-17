<?php

namespace Modules\ContactForm\Http\Controllers;

class ContactFormController
{

    public function submit()
    {
        $requestData = request()->all();

        $save = app()->forms_manager->post($requestData);
        return $save;
    }

}
