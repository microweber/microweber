<?php

declare(strict_types=1);

namespace Modules\Ai\Console\Commands;

use Illuminate\Console\Command;
use Modules\Ai\Services\Mcp\McpToolCatalog;

/**
 * Print the MCP tool catalog as a table. Reads directly off
 * {@see McpToolCatalog::allDefinitions()} — same source the
 * tools/list response is built from — so the operator-side view
 * matches what an MCP client sees on the wire.
 */
class McpToolsListCommand extends Command
{
    protected $signature = 'ai:mcp:tools:list
        {--module= : Filter to tools whose module key matches the given value}';

    protected $description = 'List the MCP tool catalog (name, module, title) in tabular form.';

    public function handle(McpToolCatalog $catalog): int
    {
        $filter = $this->option('module');
        $rows = [];
        foreach ($catalog->allDefinitions() as $name => $definition) {
            $module = (string) ($definition['module'] ?? '');
            if ($filter !== null && $filter !== '' && $module !== $filter) {
                continue;
            }
            $rows[] = [
                'name' => $name,
                'module' => $module,
                'title' => (string) ($definition['title'] ?? ''),
            ];
        }

        if ($rows === []) {
            $this->warn('No tools matched.' . ($filter ? ' (Filtered to module=' . $filter . '.)' : ''));
            return self::SUCCESS;
        }

        $this->table(['name', 'module', 'title'], $rows);
        $this->info(sprintf(
            '%d tool%s registered%s.',
            count($rows),
            count($rows) === 1 ? '' : 's',
            $filter ? ' in module ' . $filter : ''
        ));

        return self::SUCCESS;
    }
}
