<?php

namespace MicroweberPacakges\App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand;

class ServeTestCommand extends ServeCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'serve-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serve the application on the PHP testing server';



}
