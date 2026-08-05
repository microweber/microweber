<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Contracts;

use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Factory that turns a chunk of work items into a queueable job.
 */
interface ChunkableJobFactory
{
    /**
     * @param  array<int, mixed>  $chunk
     */
    public function make(array $chunk): ShouldQueue;
}
