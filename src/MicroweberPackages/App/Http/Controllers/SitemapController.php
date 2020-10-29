<?php

namespace MicroweberPackages\App\Http\Controllers;



use MicroweberPackages\Queue\Events\ProcessQueueEvent;

class SitemapController extends Controller
{

   public function index()
   {
       event(new ProcessQueueEvent());
   }
}
