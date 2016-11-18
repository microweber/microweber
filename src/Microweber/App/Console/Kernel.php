<?php namespace Microweber\App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'Microweber\App\Console\Commands\InspireCommand',
		'Microweber\App\Console\Commands\KeyGenerateCommand',
	];



}
