<?php

namespace Modules\Newsletter\Console\Commands;

use Illuminate\Console\Command;
use Modules\Newsletter\Models\WorkflowExecution;
use Modules\Newsletter\Services\WorkflowEngine;

class ProcessWorkflows extends Command
{
    protected $signature = 'workflows:process';

    protected $description = 'Process pending workflow executions';

    public function handle(WorkflowEngine $engine): int
    {
        $this->info('Processing pending workflow executions...');

        $executions = WorkflowExecution::pending()
            ->where('created_at', '<=', now()->subMinutes(1))
            ->get();

        $count = 0;

        foreach ($executions as $execution) {
            $this->info("Processing execution: {$execution->execution_key}");
            
            $engine->execute($execution);
            $count++;
        }

        $this->info("Processed {$count} workflow executions.");

        return self::SUCCESS;
    }
}
