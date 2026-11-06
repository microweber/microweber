<?php

declare(strict_types=1);

namespace Modules\Ai\Agents\Output;

use NeuronAI\StructuredOutput\SchemaProperty;

/**
 * Structured plan produced by {@see \Modules\Ai\Agents\SwarmPlannerAgent}.
 *
 * Decomposes a website-build brief into:
 *  - a single LOCKED global design system ({@see $design}); and
 *  - an ordered list of PAGE-lanes ({@see $lanes}), one worker agent per page.
 *
 * Shape (JSON):
 * {
 *   "design": { "tokens": { "<css-var-name>": "<value>", ... }, "summary": "..." },
 *   "lanes":  [ { "agent_id": "lane-1", "title": "...", "url": "...", "order": 1, "prompt": "..." }, ... ]
 * }
 */
class SwarmPlanOutput
{
    #[SchemaProperty(
        description: 'The locked global design system (CSS-variable tokens + a human summary) that every page lane inherits. Decided ONCE, before any lane runs.',
        required: true
    )]
    public SwarmDesignOutput $design;

    /**
     * @var SwarmLaneOutput[]
     */
    #[SchemaProperty(
        description: 'The ordered list of PAGE-lanes, one per page, in reading order (order=1 first). Keep this to 3-7 lanes unless the brief clearly implies more pages.',
        required: true,
        min: 3,
        max: 7,
        anyOf: [SwarmLaneOutput::class]
    )]
    public array $lanes;
}
