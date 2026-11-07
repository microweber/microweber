<?php

declare(strict_types=1);

namespace Modules\Ai\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Modules\Ai\Models\AgentChat;
use Modules\Ai\Models\AgentChatMessage;
use Modules\Ai\Services\AgentFactory;
use Modules\Ai\Services\SseToolEmitter;
use Modules\Ai\Support\LibrariesHint;
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
    use LibrariesHint;

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
        $libraries = (array) $request->input('libraries', []);
        $editingTemplate = $this->editingTemplate($openContentId);

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
            'libraries' => $libraries,
        ]);

        $response = new StreamedResponse(function () use (
            $userId, $message, $openContentId, $openUrl, $canvasHtml, $cssVars, $selectedElement, $selectedLayout, $libraries, $editingTemplate
        ) {
            // Plain writer for the controller-authored frames (plan / lane / done /
            // coordinator tool / error). emit() json-encodes + flushes whatever I pass.
            $sse = new SseToolEmitter();

            try {
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

                // Set the shared site header's brand up front (before lanes run).
                $this->lockHeaderBrand($plan);

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

                // (4) Run each lane IN ORDER. This combined endpoint is the
                // SEQUENTIAL path (kept for back-compat); the parallel path runs
                // the same runLane() once per process via agentSwarmLaneStream.
                foreach ($lanes as $index => $lane) {
                    $doneLanes[] = $this->runLane(
                        $sse,
                        $lane,
                        $plan,
                        $index === $openLaneIndex,
                        $openContentId,
                        $canvasHtml,
                        [
                            'total_lanes' => $totalLanes,
                            'css_vars' => $cssVars,
                            'selected_element' => $selectedElement,
                            'selected_layout' => $selectedLayout,
                            'libraries' => $libraries,
                            'editing_template' => $editingTemplate,
                        ],
                        $userId
                    );
                }

                // (4b) NAVIGATION — wire the built pages into the header menu.
                $doneLanes[] = $this->buildNavigation($plan, $sse, $openContentId);

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
     * PHASE 2 — PLAN endpoint (JSON). Run the planner ONCE and hand the frontend
     * everything it needs to fan the lanes out as CONCURRENT requests:
     *   { plan_id, lanes:[{agent_id,title,url,order,summary}],
     *     design:{tokens}, coordinator:[{tool,args}, …] }
     *
     * The FULL plan (worker prompts + per-lane thread titles) is kept SERVER-SIDE
     * in the cache under plan_id (never shipped to the client); each lane request
     * (agentSwarmLaneStream) looks it up by plan_id + agent_id. The coordinator
     * frames are the two global design applications the frontend plays once, up
     * front, before the lane streams start — same payloads the sequential stream
     * emits as agent_id:"coordinator" tool frames.
     */
    public function agentSwarmPlan(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'message' => 'required|string|max:4000',
            'content_id' => 'sometimes|integer',
            'open_url' => 'sometimes|string|max:2000',
            'canvas_html' => 'sometimes|string',
            'css_vars' => 'sometimes|array',
            'selected_element' => 'sometimes|array',
            'selected_layout' => 'sometimes|array',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toArray()], 422);
        }

        $message = (string) $request->input('message');
        $openContentId = (int) $request->input('content_id', 0);
        $openUrl = (string) $request->input('open_url', '');
        $canvasHtml = (string) $request->input('canvas_html', '');
        $cssVars = (array) $request->input('css_vars', []);

        // Let the planner's read tools see the page the user currently has open.
        app()->instance('mw.ai.liveedit.context', [
            'dom' => $canvasHtml,
            'edit_fields' => [],
            'computed_styles' => [],
            'css_vars' => $cssVars,
            'selected_element' => (array) $request->input('selected_element', []),
            'selected_layout' => (array) $request->input('selected_layout', []),
        ]);

        $plan = $this->runPlanner($message, [
            'content_id' => $openContentId,
            'open_url' => $openUrl,
            'canvas_html' => $canvasHtml,
            'css_vars' => $cssVars,
        ]);

        // Set the shared site header's brand up front — the plan call is the
        // single "once" step in the parallel path, so this runs exactly once.
        $this->lockHeaderBrand($plan);

        $planId = 'swarm_plan_' . substr((string) Str::uuid(), 0, 12);
        // Keep the full plan (with prompts) server-side for the lane requests.
        Cache::put('swarm:plan:' . $planId, [
            'plan' => $plan,
            'brief' => $message,
            'open_url' => $openUrl,
            'open_content_id' => $openContentId,
            'user_id' => auth()->id(),
        ], now()->addMinutes(15));

        $tokens = $plan['design']['tokens'];
        $coordinator = [];
        if (!empty($tokens)) {
            $coordinator[] = ['tool' => 'set_css_var', 'args' => ['vars' => $tokens]];
            $coordinator[] = ['tool' => 'apply_css', 'args' => ['css' => $this->buildGlobalCss($tokens)]];
        }

        return response()->json([
            'plan_id' => $planId,
            'lanes' => array_map(static fn (array $lane) => [
                'agent_id' => $lane['agent_id'],
                'title' => $lane['title'],
                'url' => $lane['url'],
                'order' => $lane['order'],
                'summary' => $lane['summary'],
            ], $plan['lanes']),
            'design' => ['tokens' => $tokens],
            'coordinator' => $coordinator,
        ]);
    }

    /**
     * PHASE 2 — single-LANE SSE stream. One worker, one page, one request — so
     * each lane runs in its OWN php process with its OWN mw.ai.liveedit.context
     * binding: concurrent lanes cannot clobber each other's canvas context (the
     * structural isolation Phase 2 needs; no shared in-process state to race on).
     *
     * Takes plan_id + agent_id (resolved against the server-side plan) plus THIS
     * lane's live canvas context, and emits the lane-tagged started/building/
     * tool/done frames for that one lane. The frontend opens N of these at once
     * (Promise.all) and fires a single saveCanvasAsync() after they all close.
     */
    public function agentSwarmLaneStream(Request $request): StreamedResponse
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'plan_id' => 'required|string|max:64',
            'agent_id' => 'required|string|max:64',
            'content_id' => 'sometimes|integer',
            'open_url' => 'sometimes|string|max:2000',
            'canvas_html' => 'sometimes|string',
            'css_vars' => 'sometimes|array',
            'selected_element' => 'sometimes|array',
            'selected_layout' => 'sometimes|array',
            'is_open_page' => 'sometimes|boolean',
        ]);
        if ($validator->fails()) {
            return new StreamedResponse(function () use ($validator) {
                (new SseToolEmitter())->emit('error', ['message' => $validator->errors()->toArray()]);
            }, 422, $this->sseHeaders());
        }

        $planId = (string) $request->input('plan_id');
        $agentId = (string) $request->input('agent_id');
        $openContentId = (int) $request->input('content_id', 0);
        $openUrl = (string) $request->input('open_url', '');
        $canvasHtml = (string) $request->input('canvas_html', '');
        $cssVars = (array) $request->input('css_vars', []);
        $selectedElement = (array) $request->input('selected_element', []);
        $selectedLayout = (array) $request->input('selected_layout', []);
        $libraries = (array) $request->input('libraries', []);
        $editingTemplate = $this->editingTemplate($openContentId);
        $isOpenPageReq = $request->has('is_open_page') ? (bool) $request->boolean('is_open_page') : null;
        $userId = auth()->id();

        return new StreamedResponse(function () use (
            $planId, $agentId, $openContentId, $openUrl, $canvasHtml,
            $cssVars, $selectedElement, $selectedLayout, $isOpenPageReq, $userId, $libraries, $editingTemplate
        ) {
            $sse = new SseToolEmitter();

            try {
                $cached = Cache::get('swarm:plan:' . $planId);
                if (!is_array($cached) || empty($cached['plan']['lanes'])) {
                    $sse->emit('error', ['message' => 'Swarm plan expired or not found — re-run the plan.']);

                    return;
                }

                $plan = $cached['plan'];
                $lanes = $plan['lanes'];
                $totalLanes = count($lanes);

                // Find this lane in the plan by its agent_id, keeping its index so
                // we can tell whether it is the page the user has open.
                $laneIndex = null;
                foreach ($lanes as $i => $candidate) {
                    if ((string) $candidate['agent_id'] === $agentId) {
                        $laneIndex = $i;
                        break;
                    }
                }
                if ($laneIndex === null) {
                    $sse->emit('error', ['message' => 'Unknown lane: ' . $agentId]);

                    return;
                }

                $lane = $lanes[$laneIndex];
                $openLaneIndex = $this->resolveOpenLaneIndex($lanes, $openUrl, $openContentId);
                $isOpenPage = $isOpenPageReq ?? ($laneIndex === $openLaneIndex);

                $this->runLane(
                    $sse,
                    $lane,
                    $plan,
                    $isOpenPage,
                    $openContentId,
                    $canvasHtml,
                    [
                        'total_lanes' => $totalLanes,
                        'css_vars' => $cssVars,
                        'selected_element' => $selectedElement,
                        'selected_layout' => $selectedLayout,
                        'libraries' => $libraries,
                        'editing_template' => $editingTemplate,
                    ],
                    $userId
                );
            } catch (\Throwable $e) {
                $sse->emit('error', ['message' => 'Swarm lane error: ' . $e->getMessage()]);
            }
        }, 200, $this->sseHeaders());
    }

    /**
     * PHASE 2 — NAVIGATION endpoint (JSON). The frontend calls this ONCE after all
     * lane streams close, to wire the built pages into the header menu. Reads the
     * plan from the cache by plan_id and runs the deterministic buildNavigation().
     */
    public function agentSwarmNav(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'plan_id' => 'required|string|max:64',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toArray()], 422);
        }

        $cached = Cache::get('swarm:plan:' . (string) $request->input('plan_id'));
        if (!is_array($cached) || empty($cached['plan']['lanes'])) {
            return response()->json(['nav' => ['status' => 'skipped', 'built' => 0]]);
        }

        // No SSE emitter here — this is a JSON endpoint; buildNavigation returns
        // the record which the frontend uses to update its board.
        $record = $this->buildNavigation($cached['plan'], null, (int) ($cached['open_content_id'] ?? 0));

        return response()->json(['nav' => $record]);
    }

    /**
     * Run ONE lane end to end: bind its canvas context, spin up a dedicated
     * LiveEdit worker on its own AgentChat thread, stream the lane-tagged
     * started/building/tool/done frames, and return the lane's done record.
     *
     * Shared by the sequential combined stream and the Phase-2 per-process lane
     * stream — the only difference between the two is WHERE the context binding
     * lives (same process, rebound per lane vs. a fresh process per lane).
     *
     * @param array<string, mixed> $lane
     * @param array<string, mixed> $plan
     * @param array{total_lanes?:int,css_vars?:array,selected_element?:array,selected_layout?:array} $ctx
     * @return array<string, mixed> the lane's entry for the final `done` frame
     */
    protected function runLane(
        SseToolEmitter $sse,
        array $lane,
        array $plan,
        bool $isOpenPage,
        int $openContentId,
        string $canvasHtml,
        array $ctx,
        ?int $userId
    ): array {
        $laneAgentId = (string) $lane['agent_id'];
        $totalLanes = (int) ($ctx['total_lanes'] ?? 1);

        $sse->emit('lane', [
            'agent_id' => $laneAgentId,
            'status' => 'started',
            'built' => 0,
            'total' => $totalLanes,
            'title' => $lane['title'],
            'url' => $lane['url'],
        ]);

        try {
            // Bind the canvas context for THIS lane. In the per-process (parallel)
            // path this binding is private to the process, so concurrent lanes do
            // not share — and cannot clobber — it. Only the open page gets the real
            // live DOM (add_section runs live); off-canvas lanes get an empty DOM
            // and build their page through create_content + CRUD.
            app()->instance('mw.ai.liveedit.context', [
                'dom' => $isOpenPage ? $canvasHtml : '',
                'edit_fields' => [],
                'computed_styles' => [],
                'css_vars' => $ctx['css_vars'] ?? [],
                'selected_element' => $isOpenPage ? ($ctx['selected_element'] ?? []) : [],
                'selected_layout' => $isOpenPage ? ($ctx['selected_layout'] ?? []) : [],
                // So create_content in an off-canvas lane makes the new page on the
                // SAME template as the page the build started on (consistent design).
                'editing_template' => (string) ($ctx['editing_template'] ?? ''),
            ]);

            $agentFactory = app(AgentFactory::class);

            // PER-LANE AgentChat thread, unique title per lane, so each worker
            // keeps its own isolated memory/history end to end.
            $laneChat = $agentFactory->createOrGetChat(
                agentType: 'liveedit',
                title: $lane['thread_title'],
                userId: $userId
            );

            $worker = $agentFactory->agentWithChat($laneChat);

            // Lane-tagged emitter: auto tool frames carry this lane's agent_id,
            // and the onToolCall callback streams `building` progress.
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

            $lanePrompt = $this->buildLanePrompt($lane, $plan, $isOpenPage, $openContentId, $canvasHtml);
            // Tell the lane worker which JS libraries are loaded on the page so it
            // can drive motion with gsap/ScrollTrigger (see LibrariesHint).
            $libHint = $this->formatLibrariesHint((array) ($ctx['libraries'] ?? []));
            if ($libHint !== '') {
                $lanePrompt .= "\n\n" . $libHint;
            }
            $worker->chat(new UserMessage($lanePrompt))->getMessage();

            $built = $laneBuilt > 0 ? $laneBuilt : count($laneEmitter->all());

            $sse->emit('lane', [
                'agent_id' => $laneAgentId,
                'status' => 'done',
                'built' => $built,
                'total' => $totalLanes,
                'title' => $lane['title'],
                'url' => $lane['url'],
            ]);

            return [
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

            return [
                'agent_id' => $laneAgentId,
                'status' => 'error',
                'built' => 0,
                'url' => $lane['url'],
            ];
        }
    }

    /**
     * NAVIGATION subagent — runs AFTER the page lanes and wires the built pages
     * into the header menu. Deterministic (not model-driven) so it is reliable:
     * it resolves each planned page by url/title to its content id, then REBUILDS
     * the header_menu fresh (dropping the items accumulated by earlier builds) with
     * one item per page, in reading order, and clears the menu cache so the header
     * re-renders. Emits a lane-tagged frame (agent_id "nav") for the swarm board.
     *
     * @param array<string, mixed> $plan
     * @return array<string, mixed> the nav lane's done record
     */
    protected function buildNavigation(array $plan, ?SseToolEmitter $sse = null, int $openContentId = 0): array
    {
        if ($sse) { $sse->emit('lane', ['agent_id' => 'nav', 'status' => 'started', 'built' => 0, 'total' => 1, 'title' => 'Navigation', 'url' => '']); }
        $built = 0;
        try {
            $lanes = (array) ($plan['lanes'] ?? []);
            $pages = [];
            // The home/open page FIRST, resolved by its real content id — the
            // plan's home-lane url is a slug ("home") that usually does not match
            // the page the build opened on (e.g. "nimbus-home"), so url lookup
            // would miss it. Use its own lane title when we have it.
            if ($openContentId > 0) {
                $oc = \Modules\Content\Models\Content::find($openContentId);
                if ($oc && (int) $oc->is_deleted === 0) {
                    $homeTitle = trim((string) (($lanes[0]['title'] ?? '') ?: $oc->title));
                    $pages[] = ['id' => (int) $oc->id, 'title' => $homeTitle !== '' ? $homeTitle : 'Home'];
                }
            }
            foreach ($lanes as $lane) {
                $lane = (array) $lane;
                $url = trim((string) ($lane['url'] ?? ''), '/ ');
                $title = trim((string) ($lane['title'] ?? ''));
                $c = null;
                if ($url !== '') {
                    $c = \Modules\Content\Models\Content::where('url', $url)->where('is_deleted', 0)->orderBy('id', 'desc')->first();
                }
                if (!$c && $title !== '') {
                    $c = \Modules\Content\Models\Content::where('title', $title)->where('is_deleted', 0)->orderBy('id', 'desc')->first();
                }
                if ($c) {
                    $pages[] = ['id' => (int) $c->id, 'title' => $title !== '' ? $title : (string) $c->title];
                }
            }
            // Dedup by content id, keep reading order.
            $seen = [];
            $ordered = [];
            foreach ($pages as $p) {
                if (!isset($seen[$p['id']])) {
                    $seen[$p['id']] = 1;
                    $ordered[] = $p;
                }
            }

            if (!empty($ordered)) {
                $group = \Modules\Menu\Models\Menu::whereNull('parent_id')->where('title', 'header_menu')->first();
                if (!$group) {
                    $group = \Modules\Menu\Models\Menu::create(['title' => 'header_menu']);
                }
                // Fresh navigation for THIS site: drop the accumulated items and add
                // one link per built page (otherwise every build piles more items on).
                \Modules\Menu\Models\Menu::where('parent_id', $group->id)->delete();
                $pos = 0;
                foreach ($ordered as $p) {
                    $pos++;
                    \Modules\Menu\Models\Menu::create([
                        'parent_id' => $group->id,
                        'title' => $p['title'],
                        'content_id' => $p['id'],
                        'item_type' => 'content',
                        'position' => $pos,
                        'is_active' => 1,
                    ]);
                    $built++;
                }
                // The header menu module caches its items — clear it so the new nav
                // renders instead of the stale cached menu.
                $this->clearMenuCache();
            }

            if ($sse) { $sse->emit('lane', ['agent_id' => 'nav', 'status' => 'done', 'built' => $built, 'total' => 1, 'title' => 'Navigation', 'url' => '']); }

            return ['agent_id' => 'nav', 'status' => 'done', 'built' => $built, 'url' => ''];
        } catch (\Throwable $e) {
            if ($sse) { $sse->emit('lane', ['agent_id' => 'nav', 'status' => 'error', 'built' => 0, 'total' => 1, 'title' => 'Navigation', 'url' => '']); }

            return ['agent_id' => 'nav', 'status' => 'error', 'built' => 0, 'url' => ''];
        }
    }

    /** Clear the Microweber menu cache so a rebuilt menu renders. */
    protected function clearMenuCache(): void
    {
        foreach (['menus', 'menus/global'] as $group) {
            try { app()->cache_manager->delete($group); } catch (\Throwable $e) {}
        }
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
     * The template the build is happening on: the open page's own template, else
     * the site default. Created (off-canvas) pages inherit this so the whole site
     * stays on one template + design.
     */
    protected function editingTemplate(int $openContentId): string
    {
        try {
            if ($openContentId > 0) {
                $c = \Modules\Content\Models\Content::find($openContentId);
                if ($c && !empty($c->active_site_template)) {
                    return (string) $c->active_site_template;
                }
            }
        } catch (\Throwable $e) {
            // fall through to the site default
        }

        try {
            return function_exists('get_option') ? (string) get_option('current_template', 'template') : '';
        } catch (\Throwable $e) {
            return '';
        }
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
        $brand = trim((string) ($design['brand'] ?? ''));

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
            'design' => ['tokens' => $tokens, 'summary' => $designSummary, 'brand' => $brand],
            'lanes' => $lanes,
        ];
    }

    /**
     * Lock the shared site header BEFORE any lane runs: set the site title to the
     * brand the planner named, so the one global template header (logo + nav that
     * every page shares) carries this build's brand instead of whatever a previous
     * build left in the `website_title` option. The header's COLOURS are handled
     * separately — buildGlobalCss() binds the header/logo/nav to the design tokens,
     * so the shared header is re-styled on every build and never keeps the old
     * template/build palette. No-op when the planner named no brand.
     */
    protected function lockHeaderBrand(array $plan): void
    {
        $brand = trim((string) ($plan['design']['brand'] ?? ''));
        if ($brand === '') {
            return;
        }

        try {
            if (!function_exists('save_option')) {
                return;
            }

            // The site title (used in <title>, some templates' header fallback).
            save_option([
                'option_key' => 'website_title',
                'option_value' => $brand,
                'option_group' => 'website',
            ]);

            // The Logo module renders its OWN per-instance text option, NOT
            // website_title — option_key 'text' under a group that is the logo
            // module's id (e.g. "header-logo-header-layout",
            // "footer-logo-footer-layout"). A previous build's brand lives there,
            // so update every logo-module text option to this build's brand.
            $logoTextGroups = \Illuminate\Support\Facades\DB::table('options')
                ->where('option_key', 'text')
                ->where('option_group', 'like', '%logo%')
                ->pluck('option_group');
            foreach ($logoTextGroups as $group) {
                save_option([
                    'option_key' => 'text',
                    'option_value' => $brand,
                    'option_group' => (string) $group,
                ]);
            }
        } catch (\Throwable $e) {
            // Non-fatal: a failed brand set must never abort the build.
        }
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

        // SHARED GLOBAL HEADER / NAV — the template's header (logo + menu) is one
        // piece of chrome shared by every page that no single page lane owns, so
        // the coordinator re-styles it here, once, bound to the design tokens. The
        // global live_edit.css ACCUMULATES across builds, so without this a new
        // build inherits the previous build's header colours (the "header is the
        // old style" bug). !important is deliberate: it overrides the colours an
        // earlier build left in this same file. Brand TEXT is set separately via
        // lockHeaderBrand() (the website_title option the logo module renders).
        //
        // Two layers, because templates split into two camps:
        //  (1) TOKEN-DRIVEN headers (Plain/Big/…) read their own header/footer
        //      chrome tokens (--mw-*header*, --mw-footer-*). A previous build's
        //      stale values for these persist (set_css_var merges per-token), so
        //      we re-derive them from THIS build's main palette.
        //  (2) CLASS-DRIVEN headers — a generic selector pass for templates whose
        //      header isn't token-driven.
        $derivedTokens = ":root {\n"
            . "  --mw-header-background-color: var(--mw-background-color) !important;\n"
            . "  --mw-header-link-color: var(--mw-heading-color) !important;\n"
            . "  --mw-header-link-hover-color: var(--mw-link-color) !important;\n"
            . "  --mw-top-header-background-color: var(--mw-background-color) !important;\n"
            . "  --mw-top-header-link-color: var(--mw-body-color) !important;\n"
            . "  --mw-top-header-link-hover-color: var(--mw-link-color) !important;\n"
            . "  --mw-top-header-button-background-color: var(--mw-primary-color, var(--mw-btn-background-color)) !important;\n"
            . "  --mw-top-header-button-text-color: var(--mw-btn-text-color) !important;\n"
            . "  --mw-footer-background-color: var(--mw-section-background-color, var(--mw-background-color)) !important;\n"
            . "  --mw-footer-link-color: var(--mw-link-color) !important;\n"
            . "  --mw-footer-text-color: var(--mw-paragraph-color, var(--mw-body-color)) !important;\n"
            // The language/currency switcher modules read their OWN surface/text
            // tokens (--mw-surface, --mw-text-color, --mw-text-muted) which the
            // design system doesn't name — map them to the palette so the switcher
            // inherits the template (dark surface + light text) instead of its
            // light-theme fallback (white pill + dark text).
            . "  --mw-surface: var(--mw-section-background-color, var(--mw-background-color)) !important;\n"
            . "  --mw-surface-hover: var(--mw-section-background-color, var(--mw-background-color)) !important;\n"
            . "  --mw-text-color: var(--mw-body-color) !important;\n"
            . "  --mw-text-muted: var(--mw-paragraph-color, var(--mw-body-color)) !important;\n"
            . "}\n";

        // Real Microweber header hooks (what the rendered header actually uses:
        // #mw-header-background bar, #header-logo-header-layout .logo-text, the
        // .menu_element_link nav links, the .mw-lang-switcher, the header icons)
        // — these must match prior builds' own selectors to outrank them, and
        // since this build's apply_css lands LAST in the accumulating file, equal
        // specificity means this build wins. Plus a generic fallback pass for
        // templates whose header isn't built on these exact ids.
        $header = "#mw-header-background, #mw-header-background.header-background { background: var(--mw-background-color) !important; }\n"
            . ".top-header-background, .templates-top-header-menu .top-header-background.header-top { background: var(--mw-background-color) !important; }\n"
            . "#header-logo-header-layout .logo-text, .logo-text { color: var(--mw-heading-color) !important; font-family: var(--mw-heading-font-family, inherit) !important; }\n"
            . "#mw-header-background .navbar-nav .nav-link, #mw-header-background .menu_element_link, .menu_element_link { color: var(--mw-body-color) !important; }\n"
            . "#mw-header-background .menu_element_link:hover, #mw-header-background .navbar-nav .nav-link:hover { color: var(--mw-link-color) !important; }\n"
            . ".btn-search .nav-link, .btn-member .nav-link, .btn-shopping-cart .nav-link { color: var(--mw-link-color) !important; }\n"
            . ".mw-lang-switcher__toggle, .mw-lang-switcher__current { color: var(--mw-body-color) !important; }\n"
            // Language-switcher dropdown items live as <a> inside the header, so a
            // prior build's `#header-layout .nav-item a { color:#d4af37 !important }`
            // bleeds gold onto them and the ACTIVE language. Match that id-level
            // specificity so this build's palette wins (active = the accent colour).
            . "#mw-header-background .mw-lang-switcher__menu, #header-layout .mw-lang-switcher__menu { background: var(--mw-section-background-color, var(--mw-background-color)) !important; }\n"
            . "#mw-header-background a.mw-lang-switcher__item, #header-layout a.mw-lang-switcher__item { color: var(--mw-body-color) !important; }\n"
            . "#mw-header-background a.mw-lang-switcher__item.is-active, #header-layout a.mw-lang-switcher__item.is-active, #mw-header-background a.mw-lang-switcher__item.is-active .mw-lang-switcher__item-name { color: var(--mw-primary-color, var(--mw-link-color)) !important; }\n"
            . "#mw-header-background .mw-lang-switcher__check, #header-layout .mw-lang-switcher__check { color: var(--mw-primary-color, var(--mw-link-color)) !important; }\n"
            // Generic fallback for non-id-based template headers.
            . "header, .mw-template-header, .nav-wrapper, .menu-wrapper, .module-menu { background-color: var(--mw-background-color, transparent) !important; }\n"
            . ".module-logo, .module-logo a, .nav-logo a, .navbar-brand { color: var(--mw-heading-color, inherit) !important; }\n"
            . ".module-menu a, .menu-items a, nav a.nav-link, .mw-nav a { color: var(--mw-link-color, var(--mw-body-color, inherit)) !important; }\n";

        return $root . $base . $derivedTokens . $header;
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
