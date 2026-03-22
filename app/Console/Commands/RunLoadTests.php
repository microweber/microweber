<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

/**
 * Load Testing Command
 *
 * Run performance and load tests with configurable parameters
 */
class RunLoadTests extends Command
{
    protected $signature = 'test:load
                            {--requests=50 : Number of concurrent requests to simulate}
                            {--duration=60 : Test duration in seconds}
                            {--endpoint=/ : Target endpoint to test}
                            {--concurrent=10 : Number of concurrent connections}
                            {--benchmark : Run benchmark tests instead of load tests}
                            {--filter= : Filter tests by name}
                            {--group= : Run specific test group}';

    protected $description = 'Run load and performance tests';

    public function handle(): int
    {
        $requests = $this->option('requests');
        $duration = $this->option('duration');
        $endpoint = $this->option('endpoint');
        $concurrent = $this->option('concurrent');
        $benchmark = $this->option('benchmark');
        $filter = $this->option('filter');
        $group = $this->option('group');

        $this->info('Starting Load Test Suite...');
        $this->newLine();

        // Display configuration
        $this->info('Test Configuration:');
        $this->table(
            ['Parameter', 'Value'],
            [
                ['Test Type', $benchmark ? 'Benchmark' : 'Load'],
                ['Requests', $requests],
                ['Duration', $duration . 's'],
                ['Endpoint', $endpoint],
                ['Concurrent', $concurrent],
                ['Filter', $filter ?: 'None'],
                ['Group', $group ?: 'None'],
            ]
        );
        $this->newLine();

        // Build PHPUnit command
        $command = 'phpunit';
        $args = [];

        if ($benchmark) {
            $args[] = '--group benchmark';
            $this->info('Running benchmark tests...');
        }

        if ($group) {
            $args[] = "--group {$group}";
        }

        if ($filter) {
            $args[] = "--filter={$filter}";
        }

        $args[] = 'tests/Feature/Performance';

        // Run tests
        $this->info('Executing tests...');
        $this->newLine();

        $exitCode = 0;
        $output = [];

        // Run the actual test command
        $fullCommand = $command . ' ' . implode(' ', $args);
        exec($fullCommand . ' 2>&1', $output, $exitCode);

        // Display results
        foreach ($output as $line) {
            if (str_contains($line, 'ERRORS') || str_contains($line, 'FAILURES')) {
                $this->error($line);
            } elseif (str_contains($line, 'OK') || str_contains($line, 'passed')) {
                $this->info($line);
            } else {
                $this->line($line);
            }
        }

        $this->newLine();

        // Performance recommendations
        if ($exitCode === 0) {
            $this->info('✓ All tests passed!');
            $this->newLine();
            $this->info('Performance Recommendations:');
            $this->line('- Enable full page caching for better performance');
            $this->line('- Use Redis for session and cache storage in production');
            $this->line('- Enable OPcache for PHP bytecode optimization');
            $this->line('- Consider CDN for static asset delivery');
            $this->line('- Optimize database queries with proper indexes');
        } else {
            $this->error('✗ Some tests failed. Review output above for details.');
        }

        return $exitCode;
    }
}
