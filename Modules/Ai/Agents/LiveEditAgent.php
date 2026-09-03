<?php

declare(strict_types=1);

namespace Modules\Ai\Agents;

use Modules\Ai\Tools\GenerateImageTool;
use Modules\Ai\Tools\LiveEdit\ApplyCssTool;
use Modules\Ai\Tools\LiveEdit\GetPageContextTool;
use NeuronAI\Agent\SystemPrompt;

/**
 * Conversational Live-Edit design/content agent.
 *
 * Powers the in-canvas AI chat: the user talks to it like a design collaborator
 * ("make the headings bigger", "change the buttons to green", "rewrite this
 * title") and it edits the live site by calling its tools. Runs on the
 * configured provider/model (local Ollama + Kimi by default) via BaseAgent.
 */
class LiveEditAgent extends BaseAgent
{
    protected string $domain = 'liveedit';

    protected function setupTools(): void
    {
        $this->addTool(new GetPageContextTool($this->dependencies));
        $this->addTool(new ApplyCssTool($this->dependencies));
        $this->addTool(new GenerateImageTool($this->dependencies));
    }

    public function instructions(): string
    {
        return (string) new SystemPrompt(
            background: [
                'You are the Live Edit Assistant for a Microweber website.',
                'The user is editing their live website in a visual editor and will ask you, conversationally, to change its design and content — like a helpful design collaborator.',
                'You make real changes to the site by calling your tools. You do NOT just describe changes — you apply them.',
                'Your tools: get_page_context (read the current page: title, content, current custom CSS) — call it when you need to know what is on the page; apply_css (make visual/design changes by writing custom CSS); generate_image (create an image from a text prompt when the user wants a new/replacement image).',
            ],
            steps: [
                'Understand exactly what the user wants to change.',
                'If you need to know what is currently on the page (or the user refers to "this"/"the heading"/"the text"), call get_page_context first.',
                'For a visual/design change, write the minimal correct CSS rule(s) using selectors likely present on the page (body, section, h1-h6, p, a, .btn, .module, .container, img) and call apply_css.',
                'For an image request, call generate_image with a detailed prompt.',
                'If the request is ambiguous, make a sensible choice and proceed rather than asking many questions.',
                'After applying a change, briefly confirm in plain language what you changed.',
            ],
            output: [
                'A short, friendly confirmation of the change you made (one or two sentences).',
                'Do not dump raw CSS at the user unless they ask to see it.',
            ],
        );
    }
}
