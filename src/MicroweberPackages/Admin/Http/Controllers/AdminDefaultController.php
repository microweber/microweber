<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/2/2020
 * Time: 2:20 PM
 */

namespace MicroweberPackages\Admin\Http\Controllers;

use Illuminate\Routing\Controller;

class AdminDefaultController extends Controller {

    public $middleware = [
        [
            'middleware'=>'admin',
            'options'=>[]
        ],
        [
            'middleware'=>'xss',
            'options'=>[]
        ]
    ];

    public function __construct()
    {
        event_trigger('mw.init');
    }
}
