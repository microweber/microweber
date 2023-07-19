<?php
must_have_access();

use Illuminate\Support\Facades\View;

View::addNamespace('social_links', normalize_path((__DIR__) . '/src/resources/views'));

return view('social_links::admin-form', [
    'params'=>$params
]);
