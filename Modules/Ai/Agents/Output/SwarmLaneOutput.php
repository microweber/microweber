<?php

declare(strict_types=1);

namespace Modules\Ai\Agents\Output;

use NeuronAI\StructuredOutput\SchemaProperty;

/**
 * One PAGE-lane of a swarm build plan.
 *
 * Each lane is handed to a worker agent that owns a single page (its own
 * content_id, its own AgentChat thread and its own mw.ai.liveedit.context).
 * The worker builds the page described by {@see $prompt} on top of the
 * already-locked global design system.
 */
class SwarmLaneOutput
{
    #[SchemaProperty(
        description: 'Short, stable, URL-safe slug that identifies this lane for the whole run, e.g. "lane-1", "lane-home", "lane-about". Must be unique across the plan and never change once assigned.',
        required: true
    )]
    public string $agent_id;

    #[SchemaProperty(
        description: 'Human-readable page title, e.g. "Home", "About Us", "Services", "Contact".',
        required: true
    )]
    public string $title;

    #[SchemaProperty(
        description: 'The page URL slug (path segment), lowercase, hyphen-separated, no leading slash, e.g. "home", "about", "services", "contact". The home/landing page uses "home".',
        required: true
    )]
    public string $url;

    #[SchemaProperty(
        description: 'Reading order of this page in the site narrative, starting at 1 (1 = the first page a visitor reads, usually the home/landing page). Lower numbers are built first.',
        required: true
    )]
    public int $order;

    #[SchemaProperty(
        description: 'A self-contained build brief for THIS page only: the sections it must contain IN ORDER, the single thing it must say (its role in the overall narrative), the real copy themes, and how it opens and points to the next step. Written so a worker agent can build the whole page from this text alone, reusing the locked global design tokens.',
        required: true
    )]
    public string $prompt;
}
