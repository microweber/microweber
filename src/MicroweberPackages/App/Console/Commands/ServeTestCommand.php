<?php

namespace MicroweberPackages\App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand;
use Illuminate\Support\Carbon;
use Symfony\Component\Process\PhpExecutableFinder;
use function Termwind\terminal;

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
    protected $description = 'Serve the application on the PHP testing server for Dusk and PHPUnit';

    public function handle()
    {
        $status = parent::handle();
        $envArg = $this->option('env');
        if ($envArg) {
            $_ENV['APP_ENV'] = $envArg;
            putenv('APP_ENV=' . $envArg);

        }
        app()->detectEnvironment(function () use ($envArg) {
            return $envArg;
        });
        return $status;
    }

    /**
     * Get the full server command.
     *
     * @return array
     */
    protected function serverCommand()
    {

//        if(isset($_ENV['APP_ENV']) and $_ENV['APP_ENV']){
//            $setEnvCommand = 'export APP_ENV='.$_ENV['APP_ENV'];
//            if (PHP_OS_FAMILY === "Windows") {
//                $setEnvCommand = 'set APP_ENV='.$_ENV['APP_ENV'];
//            }
//            exec($setEnvCommand);
//
//         }

        $command = [
            (new PhpExecutableFinder)->find(false),
            '-d',
            'variables_order=EGPCS',
            '-S',
            $this->host() . ':' . $this->port(),
            base_path('server.php'),
        ];
        return $command;
    }

    /**
     * Get the request port from the given PHP server output.
     *
     * @param string $line
     * @return int
     */
    protected function getRequestPortFromLine($line)
    {
        preg_match('/:(\d+)\s(?:(?:\w+$)|(?:\[.*))/', $line, $matches);
        if (!isset($matches[1])) {
            return (int)8000;
        }
        return (int)$matches[1];
    }

    /**
     * Returns a "callable" to handle the process output.
     *
     * @return callable(string, string): void
     */
    protected function handleProcessOutput()
    {
        return fn ($type, $buffer) => str($buffer)->explode("\n")->each(function ($line) {
            if (str($line)->contains('Development Server (http')) {
                if ($this->serverRunningHasBeenDisplayed) {
                    return;
                }

                $this->components->info("Server running on [http://{$this->host()}:{$this->port()}].");
                $this->comment('  <fg=yellow;options=bold>Press Ctrl+C to stop the server</>');

                $this->newLine();

                $this->serverRunningHasBeenDisplayed = true;
            } elseif (str($line)->contains(' Accepted')) {
                $requestPort = $this->getRequestPortFromLine($line);

                $this->requestsPool[$requestPort] = [
                    $this->getDateFromLine($line),
                    false,
                ];
            } elseif (str($line)->contains([' [200]: GET '])) {
                $requestPort = $this->getRequestPortFromLine($line);

                $this->requestsPool[$requestPort][1] = trim(explode('[200]: GET', $line)[1]);
            } elseif (str($line)->contains(' Closing')) {
                $requestPort = $this->getRequestPortFromLine($line);
                $request = $this->requestsPool[$requestPort];

                [$startDate, $file] = $request;

                $formattedStartedAt = $startDate->format('Y-m-d H:i:s');

                unset($this->requestsPool[$requestPort]);

                [$date, $time] = explode(' ', $formattedStartedAt);

                $this->output->write("  <fg=gray>$date</> $time");

                $runTime = $this->getDateFromLine($line)->diffInSeconds($startDate);

                if ($file) {
                    $this->output->write($file = " $file");
                }

                $dots = max(terminal()->width() - mb_strlen($formattedStartedAt) - mb_strlen($file) - mb_strlen($runTime) - 9, 0);

                $this->output->write(' '.str_repeat('<fg=gray>.</>', $dots));
                $this->output->writeln(" <fg=gray>~ {$runTime}s</>");
            } elseif (str($line)->contains(['Closed without sending a request'])) {
                // ...
            } elseif (! empty($line)) {
                $warning = explode('] ', $line);
                $this->components->warn(count($warning) > 1 ? $warning[1] : $warning[0]);
            }
        });
    }


    protected function getDateFromLine($line)
    {
        $regex = env('PHP_CLI_SERVER_WORKERS', 1) > 1
            ? '/^\[\d+]\s\[(.*)]/'
            : '/^\[([^\]]+)\]/';

        preg_match($regex, $line, $matches);




        try {
            return Carbon::createFromFormat('D M d H:i:s Y', $matches[1]);
        } catch (\ErrorException $e) {
            return Carbon::createFromFormat('D M d H:i:s Y',Carbon::now()->format('D M d H:i:s Y'));
        }

    }

}
