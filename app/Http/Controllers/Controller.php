<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function favoriteDrink()
    {
        $drinks = ['water', 'soda', 'beer', 'coffee'];
        $randKey = array_rand($drinks);

        return view('favorite-drink', ['drink' => $drinks[$randKey]]);
    }
}
