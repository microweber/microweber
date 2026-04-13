<?php

declare(strict_types=1);

namespace Modules\Ai\Services\Secrets;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class PassCommandRunner
{
    public function run(array $arguments, ?string $input = null): string
    {
        $binary = (string) config('modules.ai.secret_store.pass.binary', 'pass');
        $environment = array_filter([
            'PASSWORD_STORE_DIR' => config('modules.ai.secret_store.pass.store_dir'),
        ], static fn (mixed $value): bool => filled($value));

        $process = new Process(
            command: array_merge([$binary], $arguments),
            env: $environment === [] ? null : $environment,
            input: $input !== null ? rtrim($input, "\r\n") . PHP_EOL : null,
        );

        $process->mustRun();

        return trim($process->getOutput());
    }

    public function succeeds(array $arguments): bool
    {
        try {
            $this->run($arguments);

            return true;
        } catch (ProcessFailedException) {
            return false;
        }
    }
}
