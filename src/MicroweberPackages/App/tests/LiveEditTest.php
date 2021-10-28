<?php
namespace MicroweberPackages\App\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;


class LiveEditTest extends TestCase
{
    public function testIndex()
    {

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $response = $this->call(
            'GET',
            route('home'),
            [
                'editmode'=>'y'
            ]
        );

    }
}
