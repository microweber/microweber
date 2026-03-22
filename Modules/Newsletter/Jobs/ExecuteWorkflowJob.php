<?php

namespace Modules\Newsletter\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Newsletter\Models\WorkflowExecution;
use Modules\Newsletter\Services\WorkflowEngine;

class ExecuteWorkflowJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The workflow execution to process.
     */
    public WorkflowExecution $execution;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     */
    public $timeout = 300; // 5 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(WorkflowExecution $execution)
    {
        $this->execution = $execution;
    }

    /**
     * Execute the job.
     */
    public function handle(WorkflowEngine $engine): void
    {
        $engine->execute($this->execution);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $this->execution->markAsFailed($exception->getMessage());
        
        // Update workflow statistics
        $this->execution->workflow->incrementExecution(false);
    }
}
