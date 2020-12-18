<?php

namespace MicroweberPackages\Queue\Listeners;



class ProcessQueueListener
{
    public function handle($event)
    {
        $controller = app()->make('MicroweberPackages\Queue\Http\Controllers\ProcessQueueController');
        $controller->handle();
    }
}
