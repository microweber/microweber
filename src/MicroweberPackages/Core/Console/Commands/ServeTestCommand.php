<?php

namespace MicroweberPacakges\Core\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand as BaseServeCommand;

class ServeTestCommand extends BaseServeCommand
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
