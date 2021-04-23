<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 4/9/2021
 * Time: 5:27 PM
 */

namespace MicroweberPackages\Dusk\Console\Commands;


use RuntimeException;
use Laravel\Dusk\Console\DuskCommand;
use Symfony\Component\Process\Process;
use  MicroweberPackages\Dusk\ProcessBuilder;

class DuskServeCommand extends DuskCommand {

    const PROCESS_TIMEOUT = 60;
    const PROCESS_IDLE_TIMEOUT = 10;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dusk:serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serve the application and run Dusk tests';

    /**
     * @var Process
     */
    protected $serve;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        // Snippets copied from DuskCommand::handle()
        $this->purgeScreenshots();

        $this->purgeConsoleLogs();

        return $this->withDuskEnvironment(function () {
            // Start the Web Server AFTER Dusk handled the environment, but before running PHPUnit
            $serve = $this->serve();

            // Run PHP Unit
            return $this->runPhpunit();
        });
    }

    /**
     * Snippet copied from DuskCommand::handle() to actually run PHP Unit
     *
     * @return int
     */
    protected function runPhpunit() {
        $options = array_slice($_SERVER['argv'], 2);

        $process = (new ProcessBuilder())
            ->setTimeout(null)
            ->setPrefix($this->binary())
            ->setArguments($this->phpunitArguments($options))
            ->getProcess();


        try {
            $process->setTty(true);
        } catch (RuntimeException $e) {
            $this->output->writeln('Warning: ' . $e->getMessage());
        }

        return $process->run(function ($type, $line) {
            $this->output->write($line);
        });
    }

    /**
     * Build a process to run php artisan serve
     *
     * @return Process
     */
    protected function serve() {
        // Compatibility with Windows and Linux environment
        $arguments = [PHP_BINARY, 'artisan', 'serve', '--env=testing'];

        // Build the process
        $serve = (new ProcessBuilder($arguments))
            ->setTimeout(null)
            ->getProcess();

        return tap($serve, function (Process $serve) {
            $serve->start(function ($type, $line) {
                $this->output->writeln($line);
            });
        });
    }
}