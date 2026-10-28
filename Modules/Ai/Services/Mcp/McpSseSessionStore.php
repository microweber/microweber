<?php

declare(strict_types=1);

namespace Modules\Ai\Services\Mcp;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Cross-request bridge for the legacy MCP HTTP+SSE transport.
 *
 * In that transport a client opens a long-lived `GET /sse` stream and then
 * POSTs its JSON-RPC requests to a *separate* `/messages` request; the
 * responses have to travel back out over the already-open stream. Those
 * are two different PHP requests (often two different FPM workers), so the
 * POST handler and the streaming GET need shared state to hand the
 * response across. This store is that shared state: a per-session FIFO
 * queue kept in the cache.
 *
 * It MUST be backed by a cache store shared across workers (file / redis /
 * database) in production — the `array` store is per-request and only
 * bridges within a single process (fine for a dev server or tests).
 */
class McpSseSessionStore
{
    /** Key prefix for every session artefact. */
    private const PREFIX = 'mcp:sse:';

    /** Mint a new session: write its marker + empty queue, return the id. */
    public function open(): string
    {
        $id = (string) Str::uuid();
        $ttl = $this->ttlSeconds();

        $store = $this->store();
        $store->put($this->markerKey($id), true, $ttl);
        $store->put($this->queueKey($id), [], $ttl);

        return $id;
    }

    /** Whether a session is currently open (its marker has not expired). */
    public function exists(string $id): bool
    {
        return $id !== '' && $this->store()->has($this->markerKey($id));
    }

    /**
     * Append one JSON-RPC response envelope to the session's queue.
     *
     * @param array<string, mixed> $message
     */
    public function enqueue(string $id, array $message): void
    {
        $this->mutateQueue($id, static function (array $queue) use ($message): array {
            $queue[] = $message;

            return $queue;
        });
    }

    /**
     * Atomically take everything currently queued for a session, leaving
     * the queue empty. Returns FIFO order.
     *
     * @return list<array<string, mixed>>
     */
    public function drain(string $id): array
    {
        $taken = [];

        $this->mutateQueue($id, static function (array $queue) use (&$taken): array {
            $taken = $queue;

            return [];
        });

        return array_values($taken);
    }

    /** Tear a session down (its stream closed). */
    public function close(string $id): void
    {
        $store = $this->store();
        $store->forget($this->markerKey($id));
        $store->forget($this->queueKey($id));
    }

    /**
     * Format one SSE frame. `data` is split across lines so multi-line
     * payloads stay spec-valid; a frame ends with a blank line.
     */
    public function frame(string $event, string $data): string
    {
        $out = 'event: ' . $event . "\n";
        foreach (explode("\n", $data) as $line) {
            $out .= 'data: ' . $line . "\n";
        }

        return $out . "\n";
    }

    /**
     * Read-modify-write the queue under a short lock so a POST enqueue and
     * the stream's drain can't clobber each other. Falls back to a plain
     * read-modify-write when the cache store can't provide a lock.
     *
     * @param callable(list<array<string, mixed>>): list<array<string, mixed>> $mutator
     */
    private function mutateQueue(string $id, callable $mutator): void
    {
        $store = $this->store();
        $queueKey = $this->queueKey($id);
        $ttl = $this->ttlSeconds();

        $apply = function () use ($store, $queueKey, $ttl, $mutator): void {
            $queue = (array) $store->get($queueKey, []);
            $store->put($queueKey, $mutator(array_values($queue)), $ttl);
        };

        try {
            $lock = $store->lock($this->lockKey($id), 5);
            $lock->block(2, $apply);
        } catch (\Throwable) {
            // Store without a working lock provider — accept the tiny race
            // rather than drop the message entirely.
            $apply();
        }
    }

    private function store(): Repository
    {
        $name = config('modules.ai.mcp.sse.cache_store');

        return $name !== null && $name !== ''
            ? Cache::store((string) $name)
            : Cache::store();
    }

    private function ttlSeconds(): int
    {
        $maxSeconds = (int) config('modules.ai.mcp.sse.max_seconds', 25);

        // Outlive a single stream by a comfortable margin so a response
        // enqueued just before a stream recycles survives to the next one.
        return max(120, $maxSeconds * 2 + 60);
    }

    private function markerKey(string $id): string
    {
        return self::PREFIX . $id;
    }

    private function queueKey(string $id): string
    {
        return self::PREFIX . $id . ':queue';
    }

    private function lockKey(string $id): string
    {
        return self::PREFIX . $id . ':lock';
    }
}
