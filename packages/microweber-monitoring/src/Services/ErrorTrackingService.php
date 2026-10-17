<?php

namespace MicroweberPackages\Monitoring\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Throwable;

class ErrorTrackingService
{
    /**
     * Track an exception in the error tracking system.
     *
     * @param Throwable $exception
     * @param array $context Additional context data
     * @return int|false The error tracking ID or false on failure
     */
    public function trackException(Throwable $exception, array $context = []): int|false
    {
        try {
            $errorData = $this->prepareErrorData($exception, $context);

            // recordError de-duplicates (increments occurrence_count on a repeat)
            // and clears the stats cache — same path as trackError().
            $id = $this->recordError($errorData);

            // Also log to Laravel's logger
            Log::error($exception->getMessage(), [
                'error_tracking_id' => $id,
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);
            
            return $id;
        } catch (Throwable $e) {
            // If we can't track the error, at least log it
            Log::critical('Failed to track exception', [
                'original_exception' => $exception->getMessage(),
                'tracking_error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }
    
    /**
     * Track an error message without an exception object.
     *
     * @param string $level Error level (error, warning, critical, etc.)
     * @param string $message Error message
     * @param array $context Additional context
     * @return int|false
     */
    public function trackError(string $level, string $message, array $context = []): int|false
    {
        try {
            $errorData = array_merge([
                'level' => $level,
                'message' => $message,
                'exception_class' => $context['exception_class'] ?? null,
                'file' => $context['file'] ?? 'unknown',
                'line' => $context['line'] ?? 0,
                'code' => $context['code'] ?? null,
                'trace' => $context['trace'] ?? null,
                'context' => json_encode($context['custom'] ?? []),
            ], $this->requestContext($context), $this->baseErrorRow());

            return $this->recordError($errorData);
        } catch (Throwable $e) {
            Log::critical('Failed to track error', [
                'message' => $message,
                'tracking_error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }
    
    /**
     * Get error statistics.
     *
     * @param string $period Period for stats (today, week, month)
     * @return array
     */
    /** Periods cached by getErrorStats(); used to invalidate every variant. */
    protected const STATS_PERIODS = ['today', 'week', 'month'];

    /**
     * Forget all period-keyed stats caches. getErrorStats() caches under
     * "monitoring:error_stats:{period}", so forgetting the un-suffixed key alone
     * left every period's cache stale for up to 300s after track/resolve.
     */
    protected function clearStatsCache(): void
    {
        foreach (self::STATS_PERIODS as $period) {
            Cache::forget("monitoring:error_stats:{$period}");
        }
        Cache::forget('monitoring:error_stats');
    }

    public function getErrorStats(string $period = 'today'): array
    {
        $cacheKey = "monitoring:error_stats:{$period}";
        
        return Cache::remember($cacheKey, 300, function () use ($period) {
            $query = DB::table('error_tracking');
            
            switch ($period) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->subWeek(), now()]);
                    break;
                case 'month':
                    $query->whereBetween('created_at', [now()->subMonth(), now()]);
                    break;
            }
            
            $total = (clone $query)->count();
            $unresolved = (clone $query)->where('is_resolved', false)->count();
            $critical = (clone $query)->where('level', 'critical')->count();
            
            $byLevel = (clone $query)
                ->select('level', DB::raw('count(*) as count'))
                ->groupBy('level')
                ->pluck('count', 'level')
                ->toArray();
            
            return [
                'total' => $total,
                'unresolved' => $unresolved,
                'critical' => $critical,
                'by_level' => $byLevel,
                'period' => $period,
            ];
        });
    }
    
    /**
     * Get recent errors.
     *
     * @param int $limit Number of errors to retrieve
     * @param bool $unresolvedOnly Only show unresolved errors
     * @return array
     */
    public function getRecentErrors(int $limit = 20, bool $unresolvedOnly = false): array
    {
        $query = DB::table('error_tracking')
            ->orderBy('last_occurred_at', 'desc');
        
        if ($unresolvedOnly) {
            $query->where('is_resolved', false);
        }
        
        return $query->limit($limit)->get()->toArray();
    }
    
    /**
     * Mark an error as resolved.
     *
     * @param int $errorId
     * @param string|null $resolutionNotes
     * @return bool
     */
    public function markAsResolved(int $errorId, ?string $resolutionNotes = null): bool
    {
        try {
            DB::table('error_tracking')
                ->where('id', $errorId)
                ->update([
                    'is_resolved' => true,
                    'resolved_at' => now(),
                    'resolved_by' => auth()->id(),
                    'resolution_notes' => $resolutionNotes,
                    'updated_at' => now(),
                ]);
            
            $this->clearStatsCache();
            
            return true;
        } catch (Throwable $e) {
            Log::error('Failed to mark error as resolved', [
                'error_id' => $errorId,
                'exception' => $e->getMessage(),
            ]);
            
            return false;
        }
    }
    
    /**
     * Get top error sources (files with most errors).
     *
     * @param int $limit
     * @return array
     */
    public function getTopErrorSources(int $limit = 10): array
    {
        return DB::table('error_tracking')
            ->select('file', DB::raw('count(*) as error_count'))
            ->where('is_resolved', false)
            ->groupBy('file')
            ->orderByDesc('error_count')
            ->limit($limit)
            ->get()
            ->toArray();
    }
    
    /**
     * Prepare error data from exception.
     *
     * @param Throwable $exception
     * @param array $context
     * @return array
     */
    protected function prepareErrorData(Throwable $exception, array $context): array
    {
        return array_merge([
            'level' => $this->determineErrorLevel($exception),
            'message' => $exception->getMessage(),
            'exception_class' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'code' => $exception->getCode(),
            'trace' => $exception->getTraceAsString(),
            'context' => json_encode($context),
        ], $this->requestContext($context), $this->baseErrorRow());
    }

    /**
     * Request/user context fields shared by exception and message tracking.
     * request() degrades gracefully in CLI/queue (no fatal), so no guard needed.
     */
    protected function requestContext(array $context): array
    {
        return [
            'url' => $context['url'] ?? request()->fullUrl(),
            'method' => $context['method'] ?? request()->method(),
            'user_id' => auth()->id(),
            'user_ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'server_data' => json_encode($this->sanitizeServerData($_SERVER)),
        ];
    }

    /** New-row defaults shared by both tracking paths. */
    protected function baseErrorRow(): array
    {
        return [
            'is_resolved' => false,
            'occurrence_count' => 1,
            'last_occurred_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Insert an error row, or increment occurrence_count on an existing
     * unresolved row with the same message+file+line. Shared by trackException
     * and trackError so both de-duplicate identically. Clears the stats cache.
     */
    protected function recordError(array $row): int
    {
        $existing = DB::table('error_tracking')
            ->where('message', $row['message'])
            ->where('file', $row['file'])
            ->where('line', $row['line'])
            ->where('is_resolved', false)
            ->first();

        if ($existing) {
            DB::table('error_tracking')->where('id', $existing->id)->update([
                'occurrence_count' => $existing->occurrence_count + 1,
                'last_occurred_at' => now(),
                'updated_at' => now(),
            ]);
            $this->clearStatsCache();

            return (int) $existing->id;
        }

        $id = (int) DB::table('error_tracking')->insertGetId($row);
        $this->clearStatsCache();

        return $id;
    }
    
    /**
     * Determine error level from exception.
     *
     * @param Throwable $exception
     * @return string
     */
    protected function determineErrorLevel(Throwable $exception): string
    {
        $class = get_class($exception);
        
        return match (true) {
            str_contains($class, 'Fatal') => 'critical',
            str_contains($class, 'Database') => 'critical',
            str_contains($class, 'Connection') => 'critical',
            $exception->getCode() >= 500 => 'error',
            default => 'error',
        };
    }
    
    /**
     * Sanitize server data to remove sensitive information.
     *
     * @param array $serverData
     * @return array
     */
    protected function sanitizeServerData(array $serverData): array
    {
        $sensitiveKeys = ['HTTP_COOKIE', 'PHP_AUTH_PW', 'HTTP_AUTHORIZATION', 'PASSWORD', 'SECRET', 'KEY'];
        
        foreach ($serverData as $key => $value) {
            foreach ($sensitiveKeys as $sensitive) {
                if (str_contains(strtoupper($key), $sensitive)) {
                    $serverData[$key] = '[REDACTED]';
                    break;
                }
            }
        }
        
        return $serverData;
    }
}
