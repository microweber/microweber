<?php

declare(strict_types=1);

namespace Modules\Ai\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\AiTools\Base\BaseTool;
use MicroweberPackages\User\Models\User;
use Modules\Ai\Models\AgentChat;
use Modules\Ai\Services\AgentFactory;
use NeuronAI\Chat\Messages\UserMessage;
use NeuronAI\Workflow\WorkflowState;

/**
 * Drive the Microweber AI agent from the shell.
 *
 *   php artisan microweber:ai "add a blog post about cats"
 *
 * Same agent + same tool catalog as the Filament chat UI; this is
 * the CLI peer to the `ai:mcp:*` family. Use cases:
 *   - Operators automating routine editorial work without leaving
 *     the terminal.
 *   - CI scripts seeding content / products / categories.
 *   - Contributors prototyping write actions ahead of an MCP
 *     write-tool roll-out.
 *
 * Plan reference: `TODO.md` → "AI Agent CLI — `microweber:ai` artisan command"
 */
class MicroweberAiCommand extends Command
{
    protected $signature = 'microweber:ai
        {prompt : The prompt to dispatch to the AI agent (free text)}
        {--agent=general : Agent type registered with AgentFactory (general / content / shop / media / customer)}
        {--user= : Operator email to run as. Defaults to the first admin user.}
        {--user-id= : Numeric user id to run as. Wins over --user.}
        {--session= : Continue an existing AgentChat session id (omit for ephemeral).}
        {--json : Emit a JSON envelope on stdout instead of human-readable text.}';

    protected $description = 'Dispatch a free-text prompt to the Microweber AI agent and print the reply.';

    public function handle(AgentFactory $agentFactory): int
    {
        $prompt = (string) $this->argument('prompt');
        if ($prompt === '') {
            $this->error('Prompt cannot be empty.');
            return self::FAILURE;
        }

        $agentType = (string) $this->option('agent');
        $registered = $agentFactory->getRegisteredAgents();
        if (! in_array($agentType, $registered, true)) {
            $this->error("Unknown agent '{$agentType}'. Valid options: " . implode(', ', $registered) . '.');
            return self::FAILURE;
        }

        $user = $this->resolveUser();
        if ($user === null) {
            $this->error(
                'No admin user available. Pass --user-id=N or --user=email@example.com, '
                . 'or run `php artisan microweber:install` to seed an admin.'
            );
            return self::FAILURE;
        }

        Auth::login($user);

        $session = $this->resolveSession((int) $user->id);
        $startedAt = microtime(true);

        if (! $this->option('json')) {
            $this->line('→ Agent:   ' . $agentType);
            $this->line('→ User:    ' . $user->id . ' (' . ($user->email ?? '<no-email>') . ')');
            $this->line('→ Session: ' . ($session?->id ?? 'ephemeral'));
            $this->newLine();
        }

        try {
            $agent = $session === null
                ? $agentFactory->agent($agentType)
                : $agentFactory->agentWithChat($session);

            $state = new WorkflowState();
            $state->set('user_id', $user->id);
            if ($session !== null) {
                $state->set('chat_id', $session->id);
            }
            if (method_exists($agent, 'setState')) {
                $agent->setState($state);
            }

            $reply = $agent->chat(new UserMessage($prompt));
            $replyText = is_object($reply) && method_exists($reply, 'getContent')
                ? (string) $reply->getContent()
                : (string) $reply;
        } catch (\Throwable $e) {
            $this->error('Dispatch failed: ' . $e->getMessage());
            return self::FAILURE;
        }

        $isError = str_contains($replyText, BaseTool::ERROR_OUTPUT_MARKER);
        $durationMs = (int) round((microtime(true) - $startedAt) * 1000);

        if ($this->option('json')) {
            $this->line(json_encode([
                'agent' => $agentType,
                'user_id' => $user->id,
                'session_id' => $session?->id,
                'reply' => $replyText,
                'duration_ms' => $durationMs,
                'is_error' => $isError,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        } else {
            $this->line($replyText);
            $this->newLine();
            $this->line('— done in ' . $durationMs . ' ms' . ($isError ? ' (with errors)' : ''));
        }

        return $isError ? self::FAILURE : self::SUCCESS;
    }

    private function resolveUser(): ?User
    {
        $userId = $this->option('user-id');
        if ($userId !== null && $userId !== '') {
            return User::find((int) $userId);
        }

        $email = $this->option('user');
        if ($email !== null && $email !== '') {
            return User::where('email', $email)->first();
        }

        // Default to the first admin. is_admin is the canonical
        // admin flag in Microweber; falls back to "first user" if
        // the schema doesn't expose it (e.g. very old installs).
        $admin = User::where('is_admin', 1)->orderBy('id')->first();
        return $admin ?? User::orderBy('id')->first();
    }

    private function resolveSession(int $userId): ?AgentChat
    {
        $sessionId = $this->option('session');
        if ($sessionId === null || $sessionId === '') {
            return null;
        }

        $session = AgentChat::find((int) $sessionId);
        if ($session === null) {
            $this->warn("Session #{$sessionId} not found — falling back to ephemeral chat.");
            return null;
        }

        return $session;
    }
}
