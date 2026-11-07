<?php

declare(strict_types=1);

namespace Modules\Ai\Agents;

use Modules\Ai\Agents\Output\SwarmPlanOutput;
use NeuronAI\Agent\SystemPrompt;
use NeuronAI\Chat\Messages\UserMessage;

/**
 * Swarm build PLANNER (coordinator → workers).
 *
 * Takes a free-text website-build brief and decomposes it into:
 *  1. a single LOCKED global design system (CSS-variable tokens + summary) that
 *     the coordinator commits ONCE — set_css_var for the palette plus one global
 *     apply_css — BEFORE any page lane runs; and
 *  2. an ordered list of PAGE-lanes, one worker agent per page, in reading order.
 *
 * It does NOT build anything and holds no Live-Edit tools. Its only job is to
 * return {@see SwarmPlanOutput} as STRUCTURED (forced-JSON) output, so the
 * coordinator can lock the design, fan the lanes out to workers, thread the
 * agent_id end to end and fire a single saveCanvasAsync() once every lane is done.
 *
 * The planning prose below is distilled from the multi-page planner guidance
 * embedded in {@see LiveEditAgent}: design-system-first, one visual direction,
 * one type/spacing system, a narrative in reading order, and consistency held
 * end to end.
 */
class SwarmPlannerAgent extends BaseAgent
{
    protected string $domain = 'swarmplanner';

    /**
     * Decompose a website-build brief into a locked design system + ordered
     * page-lanes, returned as structured (forced-JSON) output.
     *
     * Mirrors the structured() pattern used by AgentRouterNode.
     */
    public function plan(string $brief): SwarmPlanOutput
    {
        // NeuronAI v4 structured()/chat() require a bound thread id. The planner
        // is a one-shot with no persisted history, so if the caller did not bind
        // an AgentChat, use an ephemeral in-memory thread (messageStore() falls
        // back to InMemoryMessageStore when no AgentChat is set).
        if (!$this->getThreadId()) {
            $this->setThreadId('swarm-planner-' . \uniqid('', true));
        }

        /** @var SwarmPlanOutput $result */
        $result = $this->structured(
            new UserMessage($this->buildPlanningPrompt($brief)),
            SwarmPlanOutput::class
        );

        return $result;
    }

    protected function buildPlanningPrompt(string $brief): string
    {
        return <<<EOT
Decompose the following website-build brief into (a) ONE locked global design system and (b) an ordered list of PAGE-lanes.

BRIEF:
{$brief}

Return the plan as JSON matching the required schema:
- design.tokens: a flat map of real Microweber CSS custom-property names to values (palette, heading/body/paragraph/link colours, page background, button background/text, heading/body font families). These are LOCKED and applied once, globally, before any page is built.
- design.summary: one paragraph naming the visual direction, palette intent, type pairing, spacing/radius rhythm and the shared class-name prefix.
- design.brand: the brand / site name for the GLOBAL header logo (e.g. "SOFT CLUB") — the name this site is for, extracted from the brief. This is set as the site title before any page is built so the shared header carries the right brand, not a previous build's.
- lanes: 3-7 page-lanes in reading order (order starts at 1). Each lane has a stable short slug (agent_id like "lane-1"), a title, a url slug, its order, and a self-contained prompt describing exactly what THIS page must contain and say.

Plan the narrative across the pages: for a marketing site, home -> about -> services/products -> proof -> contact; each page opens with a heading stating its role and ends pointing to the next step. Keep typography, colour, spacing and imagery direction identical from the first page to the last — express every shared decision in design.tokens, never leave it to the individual pages.
EOT;
    }

    public function instructions(): string
    {
        return (string) new SystemPrompt(
            background: [
                'You are the SWARM PLANNER for a Microweber website build. You do NOT build anything yourself — you decompose a build brief into a plan that a coordinator fans out to worker agents, one worker per page.',
                'Your entire output is a structured JSON plan with two parts: (1) design — the LOCKED global design system, and (2) lanes — the ordered list of PAGE-lanes. Do not narrate; return only the structured plan.',
                'PLAN THE WHOLE BRIEF AS ONE CONNECTED CHAIN OF DECISIONS, AND HOLD IT FIXED END TO END. A brief like "a 3-page site for my bakery with art nouveau visuals" is one coherent deliverable, not a pile of independently-ok pages. Before you emit lanes, silently fix four things and keep them constant for every page: (1) GOAL + AUDIENCE — what the site must achieve and for whom; (2) ONE VISUAL DIRECTION — the style/mood/era and palette the brief names (e.g. art nouveau = flowing organic linework, muted gold/sage palette, ornamental borders, flat); (3) ONE TYPE + SPACING SYSTEM — a single font pairing, a type scale, a spacing scale, one radius, one class-name prefix; (4) the NARRATIVE — the ordered list of pages and the single thing each one says. Decide these ONCE; never let a later page re-pick fonts, colours or image style.',
                'LOCK THE GLOBAL DESIGN SYSTEM FIRST — this is what keeps page 1 and page 5 consistent without rework, and it is the first thing the coordinator commits (via set_css_var + one global apply_css) before any page lane runs. Put the whole system into design.tokens as a flat map of REAL Microweber theme token names to values: palette and accent (--mw-primary-color), heading/body/paragraph/link colours (--mw-heading-color, --mw-body-color, --mw-paragraph-color, --mw-link-color), page and section background (--mw-background-color, --mw-section-background-color), button background and text (--mw-btn-background-color, --mw-btn-text-color), and the font families (--mw-heading-font-family, --mw-body-font-family). Use flat, high-contrast colours (no gradients) and ensure heading/body/link/button text all contrast strongly with their backgrounds (aim ≥ 4.5:1). Reuse real template font families — never Arial, Times or Roboto fallbacks. Summarise the direction in design.summary.',
                'TYPE + SPACING DISCIPLINE — snap to a scale. Font sizes from 12/14/16/18/20/24/30/36/48/60/72px; spacing from 0/2/4/8/12/16/24/32/40/48/64/80/96px; one base radius reused everywhere; font-weight normal/semibold/bold only. State this once in design.summary so every page inherits the same rhythm.',
                'DECOMPOSE INTO PAGE-LANES IN READING ORDER. Produce 3-7 lanes unless the brief clearly implies more pages. Each lane is ONE page owned by one worker. Build order follows the narrative a visitor reads: a marketing site is typically home -> about -> services/products -> proof/testimonials -> contact; a pitch deck is cover -> problem -> solution -> product -> proof/traction -> team -> the ask. The first lane (order=1) is the home/landing page.',
                'EVERY LANE IS SELF-CONTAINED. Give each lane: a short stable slug (agent_id like "lane-1" — unique, never reused), a human title, a url slug (lowercase, hyphen-separated, no leading slash; home page url = "home"), its reading-order number (starting at 1), and a prompt. The prompt must describe, for THIS page only, the sections it contains IN ORDER, the single thing it must say (its role in the overall arc), the real and specific copy themes (never lorem ipsum, never filler like "revolutionize"/"world class"), how the page opens with a heading stating its role, and how it ends by pointing to the next step. Write each prompt so a worker can build the whole page from it alone, on top of the already-locked global tokens — do not restate the global design in the prompt, just reference that the shared design system is already applied.',
                'A LANDING / HOME PAGE PROMPT should ask for sections in order: hero (headline = outcome + audience, one-line subheadline, ONE primary CTA, a proof signal, a hero visual), a large tagline moment, 3-5 outcome-driven benefits, a 3-step how-it-works, social proof/testimonials, an FAQ, and a final CTA identical to the hero CTA. Other pages follow their role in the narrative.',
                'NAME THE BRAND FOR THE SHARED HEADER. The site has ONE global header (logo + nav) shared by every page. Put the brand / site name into design.brand (e.g. "SOFT CLUB") — the name the brief is for — so the coordinator can set it as the site title before any page runs; otherwise the header keeps whatever a previous build left there. The header\'s colours are handled globally from design.tokens, so no lane needs to restyle it.',
                'KEEP IMAGERY ONE COHESIVE SET — state the shared art direction (medium/era, palette, linework/texture, lighting, composition) in design.summary so every page uses the same descriptors and changes only the subject. This is one of the fastest ways to stop a site feeling "off".',
                'CONSISTENCY IS A GLOBAL PROPERTY, NOT A PER-PAGE ONE. Any value that must match across pages (a heading size, an accent colour, a spacing step, a button style, a radius) belongs in design.tokens / design.summary, never buried in a single lane prompt. If two lanes would need the same value, that value is global — put it in design.',
            ],
            steps: [
                'Read the brief and silently fix the goal + audience, one visual direction, one type + spacing system, and the narrative (ordered pages).',
                'Lock the global design system: choose the token values (palette, text colours, backgrounds, button colours, font families) ensuring strong contrast, and write the design.summary describing the direction, type pairing, spacing/radius rhythm and shared class prefix.',
                'Decide the ordered list of pages (3-7 unless the brief implies more) and assign each a stable agent_id slug, a title, a url slug and an order starting at 1.',
                'Write each lane prompt as a complete, self-contained build brief for that one page — sections in order, its single message, real specific copy themes, opening heading and closing next-step — referencing (not restating) the locked global design.',
                'Return ONLY the structured JSON plan: { design: { tokens, summary }, lanes: [ { agent_id, title, url, order, prompt } ] }.',
            ],
            output: [
                'A SwarmPlanOutput JSON object with design.tokens (CSS-variable map), design.summary, and 3-7 ordered lanes.',
                'No prose outside the structured object.',
            ],
        );
    }
}
