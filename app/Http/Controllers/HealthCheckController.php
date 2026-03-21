<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Queue;
use App\Providers\CacheServiceProvider;

class HealthCheckController extends Controller
{
    /**
     * General health check endpoint.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'queue' => $this->checkQueue(),
        ];

        $isHealthy = collect($checks)->every(fn ($check) => $check['healthy']);

        return response()->json([
            'status' => $isHealthy ? 'healthy' : 'unhealthy',
            'timestamp' => now()->toIso8601String(),
            'checks' => $checks,
        ], $isHealthy ? 200 : 503);
    }

    /**
     * Cache-specific health check.
     */
    public function cache(): \Illuminate\Http\JsonResponse
    {
        $status = CacheServiceProvider::getStatus();

        return response()->json([
            'status' => $status['healthy'] ? 'healthy' : 'unhealthy',
            'timestamp' => now()->toIso8601String(),
            'cache' => $status,
        ], $status['healthy'] ? 200 : 503);
    }

    /**
     * Database health check.
     */
    public function database(): \Illuminate\Http\JsonResponse
    {
        $check = $this->checkDatabase();

        return response()->json([
            'status' => $check['healthy'] ? 'healthy' : 'unhealthy',
            'timestamp' => now()->toIso8601String(),
            'database' => $check,
        ], $check['healthy'] ? 200 : 503);
    }

    /**
     * Storage health check.
     */
    public function storage(): \Illuminate\Http\JsonResponse
    {
        $check = $this->checkStorage();

        return response()->json([
            'status' => $check['healthy'] ? 'healthy' : 'unhealthy',
            'timestamp' => now()->toIso8601String(),
            'storage' => $check,
        ], $check['healthy'] ? 200 : 503);
    }

    /**
     * Check database connectivity.
     */
    protected function checkDatabase(): array
    {
        try {
            $start = microtime(true);
            DB::connection()->getPdo();
            $responseTime = round((microtime(true) - $start) * 1000, 2);

            return [
                'healthy' => true,
                'connection' => DB::getDefaultConnection(),
                'response_time_ms' => $responseTime,
            ];
        } catch (\Exception $e) {
            return [
                'healthy' => false,
                'connection' => DB::getDefaultConnection(),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check cache connectivity.
     */
    protected function checkCache(): array
    {
        try {
            $status = CacheServiceProvider::getStatus();

            return [
                'healthy' => $status['healthy'],
                'driver' => $status['driver'],
                'prefix' => $status['prefix'],
                'ttl' => $status['ttl'],
                'store' => $status['store'],
                'redis' => $status['redis'] ?? null,
            ];
        } catch (\Exception $e) {
            return [
                'healthy' => false,
                'driver' => config('cache.default'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check storage connectivity.
     */
    protected function checkStorage(): array
    {
        try {
            $start = microtime(true);
            $testFile = 'health_check_' . uniqid() . '.txt';
            $testContent = 'test_' . time();

            Storage::put($testFile, $testContent);
            $retrieved = Storage::get($testFile);
            Storage::delete($testFile);

            $responseTime = round((microtime(true) - $start) * 1000, 2);

            return [
                'healthy' => $retrieved === $testContent,
                'disk' => config('filesystems.default'),
                'response_time_ms' => $responseTime,
            ];
        } catch (\Exception $e) {
            return [
                'healthy' => false,
                'disk' => config('filesystems.default'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check queue connectivity.
     */
    protected function checkQueue(): array
    {
        try {
            $driver = config('queue.default');

            // For sync driver, it's always available
            if ($driver === 'sync') {
                return [
                    'healthy' => true,
                    'driver' => $driver,
                    'message' => 'Synchronous queue driver (no external dependency)',
                ];
            }

            // For database driver, check connection
            if ($driver === 'database') {
                DB::table('jobs')->count();

                return [
                    'healthy' => true,
                    'driver' => $driver,
                    'connection' => config('queue.connections.database.connection'),
                ];
            }

            // For Redis driver, check connection
            if ($driver === 'redis') {
                $connection = Queue::getRedis()->ping();

                return [
                    'healthy' => $connection === '+PONG' || $connection === true,
                    'driver' => $driver,
                    'connection' => config('queue.connections.redis.connection'),
                ];
            }

            return [
                'healthy' => true,
                'driver' => $driver,
            ];
        } catch (\Exception $e) {
            return [
                'healthy' => false,
                'driver' => config('queue.default'),
                'error' => $e->getMessage(),
            ];
        }
    }
}
