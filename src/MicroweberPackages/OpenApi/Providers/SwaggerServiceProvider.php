<?php

namespace MicroweberPackages\OpenApi\Providers;


class SwaggerServiceProvider extends \L5Swagger\L5SwaggerServiceProvider
{
    public function boot()
    {
        parent::boot();
        //Include routes



        $dir = dirname(__DIR__);
        $this->loadRoutesFrom($dir . '/routes/swagger.php');

    }

}