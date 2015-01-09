<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
	public function __construct()
	{
		//$this->socialite = $socialite;
	}

	function getMw($action = '')
	{
        Socialite::extend('microweber', function($app) {
            $config = $app['config']['services.microweber'];
            return Socialite::buildProvider('Microweber\Providers\Socialite\MicroweberProvider', $config);
        });
        //dd(Socialite::getDrivers());

		if($action == 'callback') {
            $user = Socialite::driver('microweber')->user();
            $user = UserProvider::findOrCreate($user, 'microweber');
            Auth::login($user);
			return Redirect::intended('/');
		}
		return Socialite::driver('microweber')->redirect();
	}

	function getLogout()
	{
		Auth::logout();
		return Redirect::back();
	}
}