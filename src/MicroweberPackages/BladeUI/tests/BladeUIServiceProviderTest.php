<?php
namespace MicroweberPackages\BladeUI\tests;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use MicroweberPackages\BladeUI\Providers\BladeUIServiceProvider;
use MicroweberPackages\Blog\Http\Controllers\BlogController;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Shop\Http\Controllers\ShopController;

class BladeUIServiceProviderTest extends TestCase
{
    public function testIfBladeUIServiceProviderIsLoadedInApp()
    {

        $getProvider = app()->getProvider(BladeUIServiceProvider::class);
        $this->assertInstanceOf(BladeUIServiceProvider::class, $getProvider);
    }
}
