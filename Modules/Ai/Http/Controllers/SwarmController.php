<?php

declare(strict_types=1);

namespace Modules\Ai\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Ai\Models\AgentChat;
use Modules\Ai\Models\AgentChatMessage;
use Modules\Ai\Services\AgentFactory;
use Modules\Ai\Services\SseToolEmitter;
use NeuronAI\Chat\Messages\UserMessage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * SWARM MODE — coordinator → workers, streamed over SSE.
 *
 * A PLANNER (SwarmPlannerAgent) decomposes the brief into ordered PAGE-lanes and
 * LOCKS a global design system; a COORDINATOR applies that design system FIRST
 * (set_css_var + one global apply_css) before any page lane runs; then one
 * LiveEditAgent WORKER per lane builds its page on its OWN AgentChat thread and
 * its OWN mw.ai.liveedit.context. PHASE 1 runs the lanes SEQUENTIALLY but every
 * tool frame is fully lane-tagged, so the wire contract is already what the
 * parallel (Phase 2 AsyncBranchRunner) version will emit — the frontend never
 * changes when we parallelize.
 *
 * ──────────────────────────────────────────────────────────────────────────
 * ASSUMED SseToolEmitter SIGNATURE CHANGE (made by the integrator, NOT here):
 *
 *     public function __construct(
 *         ?\Closure $onToolCall = null,   // NEW: invoked once per tool call with
 *                                         //      the recorded ['tool'=>…,'args'=>…]
 *                                         //      array, for live per-lane progress
 *         ?string   $agentId    = null    // NEW, the LAST optional arg: when set,
 *                                         //      every auto-emitted `tool` frame
 *                                         //      carries "agent_id" => $agentId;
 *                                         //      when null the key is OMITTED
 *                                         //      (legacy single-agent back-compat)
 *     )
 *
 * This controller relies ONLY on:
 *   • new SseToolEmitter()                    → plain writer (existing behaviour);
 *     emit($event, $data) writes one SSE frame of whatever $data I hand it.
 *   • new SseToolEmitter($onToolCall, $agentId) → a lane-tagged observer whose
 *     auto-emitted `tool` frames include agent_id=$agentId and which calls
 *     $onToolCall per tool so I can stream `lane` status:"building" frames.
 *
 * ASSUMED SwarmPlannerAgent CONTRACT (sibling task creates the class):
 *   app(SwarmPlannerAgent::class)->plan(string $brief, array $context = [])
 *     returns { design:{tokens:{…}, summary:string},
 *               lanes:[{agent_id,title,url,order,prompt}] }.
 *   Normalized defensively below so a missing/odd shape degrades gracefully.
 * ──────────────────────────────────────────────────────────────────────────
 */
class SwarmController extends Controller
{
    /**
     * Agent-swarm streaming build over Server-Sent Events ("swarm mode").
     *
     * Frame sequence emitted (LOCKED contract):
     *   1  event: plan  — {lanes:[{agent_id,title,url,order,summary}], design:{tokens:{…}}}
     *   2  event: tool  — {agent_id:"coordinator", tool:"set_css_var", args:{vars:{…}}}
     *   3  event: tool  — {agent_id:"coordinator", tool:"apply_css",  args:{css:"…"}}
     *   4  per lane, in order:
     *        event: lane — {agent_id, status:"started", built:0, total, title, url}
     *        event: tool — {agent_id, tool, args}            (auto, 0..N, lane-tagged)
     *        event: lane — {agent_id, status:"building", built:n, total}   (0..N)
     *        event: lane — {agent_id, status:"done"|"error", built, total, title, url}
     *   5  event: done  — {lanes:[{agent_id,status,built,url}]}
     *
     * A single saveCanvasAsync() is the FRONTEND's job on the final `done` frame
     * (never per lane) — the backend just emits one `done` after all lanes.
     */
    public function agentSwarmStream(Request $request): StreamedResponse
    {
        $rules = [
            'message' => 'required|string|max:4000',
            'chat_id' => 'sometimes|integer|exists:agent_chats,id',
            'content_id' => 'sometimes|integer',
            'open_url' => 'sometimes|string|max:2000',
            'canvas_html' => 'sometimes|string',
            'css_vars' => 'sometimes|array',
            'selected_element' => 'sometimes|array',
            'selected_layout' => 'sometimes|array',
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return new StreamedResponse(function () use ($validator) {
                (new SseToolEmitter())->emit('error', ['message' => $validator->errors()->toArray()]);
            }, 422, $this->sseHeaders());
        }

        $userId = auth()->id();
        $message = (string) $request->input('message');
        $openContentId = (int) $request->input('content_id', 0);
        $openUrl = (string) $request->input('open_url', '');
        $canvasHtml = (string) $request->input('canvas_html', '');
        $cssVars = (array) $request->input('css_vars', []);
        $selectedElement = (array) $request->input('selected_element', []);
        $selectedLayout = (array) $request->input('selected_layout', []);

        // (1) Bind the live canvas context so the read tools (get_dom, get_css_vars,
        // get_selected_element, …) see the real page the user currently has open.
        // Each worker lane REBINDS this to its own page below; the open page keeps
        // the real DOM, off-canvas lanes get an empty DOM and build via create_content.
        app()->instance('mw.ai.liveedit.context', [
            'dom' => $canvasHtml,
            'edit_fields' => [],
            'computed_styles' => [],
            'css_vars' => $cssVars,
            'selected_element' => $selectedElement,
            'selected_layout' => $selectedLayout,
        ]);

        $response = new StreamedResponse(function () use (
            $userId, $message, $openContentId, $openUrl, $canvasHtml, $cssVars, $selectedElement, $selectedLayout
        ) {
            // Plain writer for the controller-authored frames (plan / lane / done /
            // coordinator tool / error). emit() json-encodes + flushes whatever I pass.
            $sse = new SseToolEmitter();

            try {
                $agentFactory = app(AgentFactory::class);

                // (2) PLAN: run the planner once, emit exactly ONE plan frame. The
                // plan frame exposes a `summary` per lane (never the raw worker
                // prompt) plus the design tokens the coordinator is about to lock.
                $plan = $this->runPlanner($message, [
                    'content_id' => $openContentId,
                    'open_url' => $openUrl,
                    'canvas_html' => $canvasHtml,
                    'css_vars' => $cssVars,
                    'selected_element' => $selectedElement,
                    'selected_layout' => $selectedLayout,
                ]);

                $lanes = $plan['lanes'];
                $tokens = $plan['design']['tokens'];
                $totalLanes = count($lanes);

                $sse->emit('plan', [
                    'lanes' => array_map(static function (array $lane) {
                        return [
                            'agent_id' => $lane['agent_id'],
                            'title' => $lane['title'],
                            'url' => $lane['url'],
                            'order' => $lane['order'],
                            'summary' => $lane['summary'],
                        ];
                    }, $lanes),
                    'design' => ['tokens' => $tokens],
                ]);

                // (3) COORDINATOR applies the GLOBAL design system FIRST — before any
                // page lane runs — as two agent_id:"coordinator" tool frames. These
                // are controller-authored (no agent), so I build the full {agent_id,
                // tool, args} payload and hand it straight to emit().
                if (!empty($tokens)) {
                    $sse->emit('tool', [
                        'agent_id' => 'coordinator',
                        'tool' => 'set_css_var',
                        'args' => ['vars' => $tokens],
                    ]);
                    $sse->emit('tool', [
                        'agent_id' => 'coordinator',
                        'tool' => 'apply_css',
                        'args' => ['css' => $this->buildGlobalCss($tokens)],
                    ]);
                }

                // Which lane is the page the user currently has OPEN? That one builds
                // live on the canvas with add_section; the rest build off-canvas via
                // create_content. Match by url, else by content_id, else lane order 1.
                $openLaneIndex = $this->resolveOpenLaneIndex($lanes, $openUrl, $openContentId);

                // The final `done` frame aggregates every lane's outcome.
                $doneLanes = [];

                // (4) Run each lane IN ORDER. PHASE 1 is sequential; every frame is
                // still lane-tagged so the wire contract matches the parallel version.
                foreach ($lanes as $index => $lane) {
                    $laneAgentId = $lane['agent_id'];
                    $isOpenPage = ($index === $openLaneIndex);

                    $sse->emit('lane', [
                        'agent_id' => $laneAgentId,
                        'status' => 'started',
                        'built' => 0,
                        'total' => $totalLanes,
                        'title' => $lane['title'],
                        'url' => $lane['url'],
                    ]);

                    try {
                        // Rebind the canvas context to THIS lane's page. Only the
                        // currently-open page gets the real live DOM (add_section runs
                        // live); off-canvas lanes get an empty DOM and build their page
                        // through create_content + CRUD. (Phase 2 parallelism will give
                        // each AsyncBranch its own isolated context instead of this
                        // sequential rebind.)
                        app()->instance('mw.ai.liveedit.context', [
                            'dom' => $isOpenPage ? $canvasHtml : '',
                            'edit_fields' => [],
                            'computed_styles' => [],
                            'css_vars' => $cssVars,
                            'selected_element' => $isOpenPage ? $selectedElement : [],
                            'selected_layout' => $isOpenPage ? $selectedLayout : [],
                        ]);

                        // PER-LANE AgentChat thread, unique title per lane, so each
                        // worker keeps its own isolated memory/history end to end.
                        $laneChat = $agentFactory->createOrGetChat(
                            agentType: 'liveedit',
                            title: $lane['thread_title'],
                            userId: $userId
                        );

                        $worker = $agentFactory->agentWithChat($laneChat);

                        // Lane-tagged emitter: auto tool frames carry this lane's
                        // agent_id, and the onToolCall callback streams `building`
                        // progress with a running built count.
                        $laneBuilt = 0;
                        $onToolCall = function (array $call = []) use (&$laneBuilt, $sse, $laneAgentId, $totalLanes) {
                            $laneBuilt++;
                            $sse->emit('lane', [
                                'agent_id' => $laneAgentId,
                                'status' => 'building',
                                'built' => $laneBuilt,
                                'total' => $totalLanes,
                            ]);
                        };
                        $laneEmitter = new SseToolEmitter($onToolCall, $laneAgentId);
                        $worker->observe($laneEmitter);

                        // Seed the lane thread with its brief for traceability.
                        AgentChatMessage::create([
                            'chat_id' => $laneChat->id,
                            'role' => 'user',
                            'content' => $lane['prompt'],
                            'metadata' => [
                                'user_id' => $userId,
                                'swarm' => true,
                                'lane_agent_id' => $laneAgentId,
                                'timestamp' => now()->toISOString(),
                            ],
                        ]);

                        $worker->chat(new UserMessage(
                            $this->buildLanePrompt($lane, $plan, $isOpenPage, $openContentId, $canvasHtml)
                        ))->getMessage();

                        // `built` = tool calls this worker actually applied. Prefer the
                        // live callback count; fall back to the emitter's record.
                        $built = $laneBuilt > 0 ? $laneBuilt : count($laneEmitter->all());

                        $sse->emit('lane', [
                            'agent_id' => $laneAgentId,
                            'status' => 'done',
                            'built' => $built,
                            'total' => $totalLanes,
                            'title' => $lane['title'],
                            'url' => $lane['url'],
                        ]);

                        $doneLanes[] = [
                            'agent_id' => $laneAgentId,
                            'status' => 'done',
                            'built' => $built,
                            'url' => $lane['url'],
                        ];
                    } catch (\Throwable $laneError) {
                        $sse->emit('lane', [
                            'agent_id' => $laneAgentId,
                            'status' => 'error',
                            'built' => 0,
                            'total' => $totalLanes,
                            'title' => $lane['title'],
                            'url' => $lane['url'],
                        ]);
                        $doneLanes[] = [
                            'agent_id' => $laneAgentId,
                            'status' => 'error',
                            'built' => 0,
                            'url' => $lane['url'],
                        ];
                    }
                }

                // (5) ONE final done frame — the frontend fires a single
                // saveCanvasAsync() off this, never per lane.
                $sse->emit('done', ['lanes' => $doneLanes]);
            } catch (\Throwable $e) {
                $sse->emit('error', ['message' => 'Swarm error: ' . $e->getMessage()]);
            }
        }, 200, $this->sseHeaders());

        return $response;
    }

    /**
     * SSE response headers (mirrors AiController::agentChatStream).
     *
     * @return array<string, string>
     */
    protected function sseHeaders(): array
    {
        return [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Connection' => 'keep-alive',
        ];
    }

    /**
     * Run the planner and normalize its output into a stable internal shape:
     *   ['design' => ['tokens' => array, 'summary' => string],
     *    'lanes'  => [['agent_id','title','url','order','prompt','summary','thread_title'], …]]
     *
     * Defensive: a missing planner class, a thrown planner, or an odd return
     * shape degrades to a single on-canvas lane so the stream still honours the
     * contract rather than dying.
     */
    protected function runPlanner(string $brief, array $context): array
    {
        $raw = null;
        $plannerClass = \Modules\Ai\Agents\SwarmPlannerAgent::class;

        if (class_exists($plannerClass)) {
            try {
                $planner = app($plannerClass);
                if (method_exists($planner, 'plan')) {
                    $raw = $planner->plan($brief, $context);
                }
            } catch (\Throwable $e) {
                $raw = null;
            }
        }

        return $this->normalizePlan($raw, $brief);
    }

    /**
     * Coerce whatever the planner returned (array | object | JSON string | null)
     * into the internal plan shape, filling sane defaults.
     */
    protected function normalizePlan(mixed $raw, string $brief): array
    {
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            $raw = is_array($decoded) ? $decoded : null;
        }
        if (is_object($raw)) {
            $raw = json_decode(json_encode($raw), true) ?: null;
        }

        $design = [];
        $lanesRaw = [];
        if (is_array($raw)) {
            $design = (array) ($raw['design'] ?? []);
            $lanesRaw = (array) ($raw['lanes'] ?? []);
        }

        $tokens = $this->normalizeTokens((array) ($design['tokens'] ?? []));
        $designSummary = trim((string) ($design['summary'] ?? ''));

        $runId = substr((string) Str::uuid(), 0, 8);
        $lanes = [];
        $order = 0;
        foreach ($lanesRaw as $laneRaw) {
            $laneRaw = (array) $laneRaw;
            $order++;
            $laneOrder = (int) ($laneRaw['order'] ?? $order);
            $title = trim((string) ($laneRaw['title'] ?? ('Page ' . $laneOrder)));
            $url = trim((string) ($laneRaw['url'] ?? Str::slug($title) ?: ('page-' . $laneOrder)));
            $agentId = trim((string) ($laneRaw['agent_id'] ?? ('worker-' . $laneOrder)));
            $prompt = trim((string) ($laneRaw['prompt'] ?? ''));
            if ($prompt === '') {
                $prompt = "Build the \"{$title}\" page ({$url}) for this site.";
            }
            $summary = trim((string) ($laneRaw['summary'] ?? Str::limit($prompt, 140)));

            $lanes[] = [
                'agent_id' => $agentId,
                'title' => $title,
                'url' => $url,
                'order' => $laneOrder,
                'prompt' => $prompt,
                'summary' => $summary,
                // Unique per run so re-runs don't collide on firstOrCreate(title).
                'thread_title' => "Swarm[{$runId}] {$laneOrder}. {$title}",
            ];
        }

        // Fallback: no usable plan → one on-canvas lane that builds from the brief.
        if (empty($lanes)) {
            $lanes[] = [
                'agent_id' => 'worker-1',
                'title' => 'Home',
                'url' => 'home',
                'order' => 1,
                'prompt' => $brief,
                'summary' => Str::limit($brief, 140),
                'thread_title' => "Swarm[{$runId}] 1. Home",
            ];
        }

        // Keep lanes strictly in reading order.
        usort($lanes, static fn ($a, $b) => $a['order'] <=> $b['order']);

        return [
            'design' => ['tokens' => $tokens, 'summary' => $designSummary],
            'lanes' => $lanes,
        ];
    }

    /**
     * Normalize a token map: trim, ensure each name starts with "--", drop empties.
     *
     * @param array<string, mixed> $tokens
     * @return array<string, string>
     */
    protected function normalizeTokens(array $tokens): array
    {
        $out = [];
        foreach ($tokens as $name => $value) {
            $name = trim((string) $name);
            $value = trim((string) $value);
            if ($name === '' || $value === '') {
                continue;
            }
            if (strncmp($name, '--', 2) !== 0) {
                $name = '--' . ltrim($name, '-');
            }
            $out[$name] = $value;
        }

        return $out;
    }

    /**
     * Build the ONE global apply_css the coordinator ships from the design tokens:
     * a :root block mirroring every token plus a few base element bindings so the
     * design system is visible even where a template doesn't itself read a token.
     *
     * @param array<string, string> $tokens
     */
    protected function buildGlobalCss(array $tokens): string
    {
        $root = ":root {\n";
        foreach ($tokens as $name => $value) {
            $root .= "  {$name}: {$value};\n";
        }
        $root .= "}\n";

        $base = "body { background: var(--mw-background-color, #ffffff); color: var(--mw-body-color, #182433); }\n"
            . "h1, h2, h3, h4, h5, h6 { color: var(--mw-heading-color, inherit); }\n"
            . "a { color: var(--mw-link-color, inherit); }\n";

        return $root . $base;
    }

    /**
     * Assemble the worker's prompt: the lane's own brief plus a preamble that
     * pins the shared design system, the target page, and whether this lane runs
     * live on the open canvas (add_section) or off-canvas (create_content).
     *
     * @param array<string, mixed> $lane
     * @param array<string, mixed> $plan
     */
    protected function buildLanePrompt(array $lane, array $plan, bool $isOpenPage, int $openContentId, string $canvasHtml): string
    {
        $preamble = '[Swarm lane — you are the worker for the "' . $lane['title'] . '" page (url: ' . $lane['url'] . ').';
        if ($isOpenPage) {
            if ($openContentId > 0) {
                $preamble .= ' This page is OPEN in the editor (content_id=' . $openContentId . ').';
            }
            $preamble .= ' Build it LIVE on the canvas with add_section (+ apply_css); it is applied live as you go.]';
        } else {
            $preamble .= ' This page is NOT the open canvas page — build it OFF-CANVAS with create_content'
                . ' (title + url + content HTML) and the content CRUD tools, reusing the shared design classes.]';
        }

        $designSummary = trim((string) ($plan['design']['summary'] ?? ''));
        if ($designSummary !== '') {
            $preamble .= "\n\n[SHARED DESIGN SYSTEM (already locked globally by the coordinator — reuse it, do NOT re-pick fonts/colours): "
                . $designSummary . ']';
        }

        $tokens = (array) ($plan['design']['tokens'] ?? []);
        if (!empty($tokens)) {
            $preamble .= "\n\n[Design tokens in effect: " . implode(', ', array_map(
                static fn ($k, $v) => $k . '=' . $v,
                array_keys($tokens),
                array_values($tokens)
            )) . ']';
        }

        if ($isOpenPage) {
            $canvasContext = $this->summarizeCanvas($canvasHtml);
            if ($canvasContext !== '') {
                $preamble .= "\n\n[Current page canvas markup]\n" . $canvasContext;
            }
        }

        return $preamble . "\n\n" . $lane['prompt'];
    }

    /**
     * Reduce raw canvas HTML to a compact, model-friendly excerpt (mirrors
     * AiController::summarizeCanvas): strip script/style/svg/comments, collapse
     * whitespace, cap length.
     */
    protected function summarizeCanvas(string $html): string
    {
        $html = trim($html);
        if ($html === '') {
            return '';
        }

        $html = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $html) ?? $html;
        $html = preg_replace('#<style\b[^>]*>.*?</style>#is', '', $html) ?? $html;
        $html = preg_replace('#<svg\b[^>]*>.*?</svg>#is', '', $html) ?? $html;
        $html = preg_replace('#<!--.*?-->#s', '', $html) ?? $html;
        $html = preg_replace('/\s+/', ' ', $html) ?? $html;
        $html = trim($html);

        $max = 9000;
        if (mb_strlen($html) > $max) {
            $html = mb_substr($html, 0, $max) . ' …[truncated]';
        }

        return $html;
    }

    /**
     * Pick the lane index that corresponds to the page the user currently has
     * OPEN: match by url first, then fall back to the first lane (order 1).
     *
     * @param list<array<string, mixed>> $lanes
     */
    protected function resolveOpenLaneIndex(array $lanes, string $openUrl, int $openContentId): int
    {
        $openUrl = trim($openUrl, '/ ');
        if ($openUrl !== '') {
            foreach ($lanes as $i => $lane) {
                if (trim((string) $lane['url'], '/ ') === $openUrl) {
                    return $i;
                }
            }
        }

        // Default: the first lane in reading order is the open/home page.
        return 0;
    }
}
