<?php

declare(strict_types=1);

namespace Modules\Ai\Console\Commands;

use Illuminate\Console\Command;
use Modules\Ai\Models\ChatEvent;

/**
 * Read or write the autonomous loop's cross-compaction handoff state, stored per
 * chat/session in the ai_chat_events table (type='loop_state').
 *
 *   php artisan ai:loop-state --session=SID                 # print the state
 *   php artisan ai:loop-state --session=SID --set-file=PATH # store file contents
 *   echo "state" | php artisan ai:loop-state --session=SID --set-stdin
 *
 * Used by the compaction hooks (read) and by the loop itself (write each tick).
 */
class LoopStateCommand extends Command
{
    protected $signature = 'ai:loop-state
        {--session= : The chat/session id to scope the state to}
        {--set= : Store this string as the loop state}
        {--set-file= : Store the contents of this file as the loop state}
        {--set-stdin : Store STDIN as the loop state}
        {--chat= : Optional agent_chats id to associate}';

    protected $description = 'Read or write the autonomous loop handoff state (ai_chat_events).';

    public function handle(): int
    {
        $session = $this->option('session') ?: null;
        $data = null;

        if ($this->option('set') !== null) {
            $data = (string) $this->option('set');
        } elseif ($this->option('set-file')) {
            $path = (string) $this->option('set-file');
            $data = is_file($path) ? (string) file_get_contents($path) : null;
        } elseif ($this->option('set-stdin')) {
            $data = (string) stream_get_contents(STDIN);
        }

        if ($data !== null) {
            $chatId = $this->option('chat') ? (int) $this->option('chat') : null;
            $row = ChatEvent::put($session, 'loop_state', $data, null, $chatId);
            $this->info('loop_state saved (row #' . $row->id . ', ' . strlen($data) . ' chars)');
            return self::SUCCESS;
        }

        // Read mode: print the latest state (prefer this session, else newest).
        $state = ChatEvent::latest_value('loop_state', $session);
        if ($state !== null && $state !== '') {
            $this->line($state);
        }
        return self::SUCCESS;
    }
}
