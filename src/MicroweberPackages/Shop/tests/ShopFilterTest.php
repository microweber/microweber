<?php
namespace MicroweberPackages\Shop\tests;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Shop\Http\Controllers\ShopController;

class ShopFilterTest extends TestCase
{
    public function testGetProducts()
    {
        $controller = App::make(ShopController::class);

        $request = new Request();
        $request->merge([]);

        $html = $controller->index($request);

        $findJs = (strpos($html, 'filter.js') !== false);
        $this->assertTrue($findJs);
        
        $findCss = (strpos($html, 'filter.css') !== false);
        $this->assertTrue($findCss);

    }
}
