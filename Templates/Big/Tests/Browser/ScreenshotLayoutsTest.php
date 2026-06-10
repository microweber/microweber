<?php

namespace Templates\Big\Tests\Browser;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;
use Illuminate\Support\Facades\Auth;
use Laravel\Dusk\Browser;

use SapientPro\ImageComparator\ImageComparator;
use Tests\Browser\Components\AdminLogin;
use Tests\BrowserLegacy\ModuleScreenshots\DuskModuleScreenshots;
use Tests\DuskTestCase;

class ScreenshotLayoutsTest extends DuskModuleScreenshots
{
    public $template_name = 'Big';


}
