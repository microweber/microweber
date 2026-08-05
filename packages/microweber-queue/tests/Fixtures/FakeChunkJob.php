<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Tests\Fixtures;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Test double job used to verify chunked / bus dispatch without side effects.
 */
class FakeChunkJob implements ShouldQueue
{
    use Batchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** @var list<array{items: list<mixed>, context: array<string, mixed>}> */
    public static array $handled = [];

    /**
     * Simulated work units (used by timeout scenario tests).
     */
    public static int $workUnitsProcessed = 0;

    /**
     * Soft timeout threshold in "work units" for timeout scenario tests.
     * When a single job's chunk would exceed this, we record a timeout risk.
     */
    public static int $softTimeoutUnits = 50;

    /** @var list<string> */
    public static array $timeoutRisks = [];

    /**
     * @param  array<int, mixed>  $items
     * @param  array<string, mixed>  $context
     */
    public function __construct(
        public array $items,
        public array $context = [],
    ) {
    }

    public function handle(): void
    {
        $count = count($this->items);

        if ($count > self::$softTimeoutUnits) {
            self::$timeoutRisks[] = sprintf(
                'Chunk of %d items exceeds soft timeout of %d units',
                $count,
                self::$softTimeoutUnits
            );
        }

        self::$workUnitsProcessed += $count;
        self::$handled[] = [
            'items' => array_values($this->items),
            'context' => $this->context,
        ];
    }

    public static function reset(): void
    {
        self::$handled = [];
        self::$workUnitsProcessed = 0;
        self::$timeoutRisks = [];
        self::$softTimeoutUnits = 50;
    }
}
