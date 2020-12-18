<?php


use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is maintenance / demo mode via the "down" command we
| will require this file so that any prerendered template can be shown
| instead of starting the framework, which could cause an exception.
|
*/



if (file_exists(__DIR__.'/../storage/framework/maintenance.php')) {
    require __DIR__.'/../storage/framework/maintenance.php';
}



//serve favicon to the PHP build-in server
if ($_SERVER and isset($_SERVER['SCRIPT_NAME']) and ($_SERVER['SCRIPT_NAME'] == '/favicon.ico') and file_exists(__DIR__.'/favicon.ico')) {
    //serve favicon to the PHP build-in server
    $favicon_path = __DIR__.'/favicon.ico';
    $size = filesize($favicon_path);

    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) &&
        strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= filemtime($favicon_path))
    {
        header('HTTP/1.0 304 Not Modified');
        exit;
    }
    header ('Content-Type: image/x-icon');
    header ("Content-length: $size");
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s T', filemtime($favicon_path)));
    echo file_get_contents($favicon_path);
    exit;
}




/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = tap($kernel->handle(
    $request = Request::capture()
))->send();

$kernel->terminate($request, $response);
